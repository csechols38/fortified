<?php

class BTaxonomyBlock extends BtPluginManager {

	public $fields = array();
	public $field_values = array();
	public $content = array();
	public $tid;
	public $vid;
	public $settings;
	public $view_mode;
	public $custom_display_settings;
	public $taxonomy_term;
	public $vocabulary_machine_name;
	public $plugins;
	public $plugin_type;
	public $layout_plugin;
	public $load_term_plugin = false;

	private function queryTermData(){
		$query = db_query('SELECT * FROM bt_taxonomy_block WHERE tid = :tid', array(':tid' => $this->tid));
		$record = $query->fetchObject();
		$this->settings = $record;
	}

	public function __construct($plugin, $settings = array()){
		foreach($plugin as $p_type => $id){
			switch($p_type){
			case 'term':
				$this->tid = $id;
				$this->hook = 'taxonomy_block_plugin_terms_info';
				$this->term = taxonomy_term_load($this->tid);
				$vocabulary = taxonomy_vocabulary_load($this->term->vid);
				$this->queryTermData();
				$this->plugin_type = 'term';
				$this->view_mode = !empty($this->settings->view_mode) ? $this->settings->view_mode : '';
				break;
			case 'vocabulary':
				$this->vid = $id;
				$this->hook = 'taxonomy_block_plugin_vocabularys_info';
				$vocabulary = taxonomy_vocabulary_load($this->vid);
				$this->plugin_type = 'vocab';
				$settings = bt_taxonomy_load_vocabulary_settings($id);
				$this->block_title = !empty($settings['bt_taxonomy_block_vocab_title']) ? $settings['bt_taxonomy_block_vocab_title'] : '';
				$this->block_desc = !empty($settings['bt_taxonomy_block_vocab_desc']) ? $settings['bt_taxonomy_block_vocab_desc'] : '';
				$this->view_mode = !empty($settings['taxonomy_block_view_mode']) ? $settings['taxonomy_block_view_mode'] : '';
				$this->settings = new stdClass();
				$this->settings->settings = serialize(array($this->view_mode => $settings['settings']));
				$this->terms = $this->loadVocabularyChildren();
				break;
			}
		}
		$this->vocabulary_machine_name = $vocabulary->machine_name;
	}

	private function loadVocabularyChildren($load_plugin = false){
		$children = taxonomy_get_tree($this->vid);
		$terms = array();
		if(!empty($children)){
			foreach($children as $delta => $child){
				if($term = _bt_taxonomy_load_term_settings($child->tid)){
					if(!empty($term->settings)){
						$term->settings = unserialize($term->settings);
					}
					$terms[$term->tid] = array(
						'term' => taxonomy_term_load($term->tid),
						'term_settings' => $term,
					);
				}
			}
		}
		return $terms;
	}
	
	private function loadVocabularyChildrenPlugins(){
		if(!empty($this->terms)){
			foreach($this->terms as $tid => &$data){
				if(!empty($data['term_settings'])){
					$t = new BTaxonomyBlock(array('term' => $tid), $data['term_settings']);
					$t->buildDisplay();
					$data['term_display'] = $t->renderTaxonomyBLock();
				}	
			}
		}
	}

	public function buildDisplay(){
		$view_modes = array();
		$l_settings = unserialize($this->settings->settings);
		
		$plugin = $this->loadPluginFromCache($this->view_mode, $this->plugin_type);
		
		$this->plugin = $plugin;
		
		if($plugin){
			
			$load_layout = false;
			if(!empty($l_settings[$this->view_mode]['layout_plugins'])){
				// for layout_plugin
				$layout_plugin_name = $l_settings[$this->view_mode]['layout_plugins'];
				$this->layout_plugin = $this->loadLayoutFromCache($this->plugin_type, $this->view_mode, $layout_plugin_name);
				if(!empty($this->layout_plugin)){
					$load_layout = true;
				}
			}
			
			// for master plugin
			if(!empty($this->settings->settings)){
				$this->settings->settings = $l_settings;
				$class = !empty($plugin['handler']) ? new $plugin['handler'] : NULL;
				if(!empty($this->plugin['load_term_plugins'])){
					$this->loadVocabularyChildrenPlugins();
				}
				if($class){
					$display = new $class();
					$class->displayOutput($this, $this->settings->settings);
					if(!empty($this->html)){
						$this->taxonomy_term = $this->html;
					}
					$this->loadStyleSheets($this->plugin);
				}
			}

			// for layout plugins
			if($load_layout){
				$layout = new $this->layout_plugin['handler']();
				if(!empty($this->layout_plugin['load term plugins'])){
					$this->loadVocabularyChildrenPlugins();
				}
				$layout->displayOutput($this, $l_settings);
				if(!empty($this->html)){
					$this->taxonomy_term = $this->html;
				}
				$this->loadStyleSheets($this->layout_plugin);
			}
			

		} else if($this->view_mode == 'full' || $this->view_mode == 'default'){
				$view_term = taxonomy_term_view($this->term, $this->view_mode, $langcode = NULL);
				$view_term['#settings'] = $this->settings;
				$this->taxonomy_term = theme('tb_custom', array('taxonomy_term' => $view_term));
		}
			
		drupal_alter('bt_taxonomy_block_display', $this->taxonomy_term, $this);
	}

	public function renderTaxonomyBLock(){
		switch($this->view_mode){
		case 'custom':
			$d_class = drupal_html_id($this->term->name);
			$class = array(
				'bt-taxonomy-term', 
				'taxonomy-term-'.$this->tid, 
				"taxonomy-vocabulary-$this->vocabulary_machine_name-$d_class",
				"taxonomy-block-$d_class",
			);
			break;
		default:
			
			$class = array(
				"taxonomy-block", 
				"taxonomy-block-$this->plugin_type", 
				"taxonomy-block-$this->view_mode", 
			);
			if(!empty($this->tid)){
				$class[] = "taxonomy-block-$this->view_mode-$this->tid";
			}
			if(!empty($this->vocabulary_machine_name)){
				if(!empty($this->tid)){
					$class[] = "taxonomy-block-term-$this->vocabulary_machine_name";
				} else if(!empty($this->vid)){
					$class[] = "taxonomy-block-vocab-$this->vocabulary_machine_name";
				}
			}
			if(!empty($this->vid)){
				$class[] = "taxonomy-block-vocab-$this->vid";
			}
			if(!empty($this->layout_plugin['machine'])){
				$layout_machine = $this->layout_plugin['machine'];
				$class[] = "layout-$layout_machine";
			}
			break;
		}
		$this->content['container'] = array(
			'#type' => 'container',
			'#attributes' => array(
				'class' => $class,
			),
		);
		if(!empty($this->taxonomy_term)){
			$this->content['container'][$this->tid] = $this->taxonomy_term;
		}
		return $this->content;
	}
}