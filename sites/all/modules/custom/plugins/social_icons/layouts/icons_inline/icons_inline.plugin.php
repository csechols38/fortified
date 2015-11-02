<?php

function icons_inline_taxonomy_block_layout_handler(){
	return array('handler' => 'IconsInlinecLayoutHandler');
}


class IconsInlinecLayoutHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Inline Icons'),
			'machine' => 'icons_inline',
			'description' => t('Social Media Icons Displayed Inline'),
			'module' => 'social_icons',
			'instances' => TRUE,
			'parent plugin' => 'social_icons',
			'stylesheets' => array(
				'less' => array(
					'path' => 'less/',
					'excludes' => array(),
				),
			),
		);
	}
	
	public function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings){
		$settings['theme'] = array(
			'#type' => 'select',
			'#title' => t('Theme'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['theme']) ? $defaults['layout_plugin_settings']['theme'] : 'light',
			'#options' => array(
				'light' => 'Light',
				'dark' => 'Dark',
			),
		);
	}
	
	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms)){
			$this->iconsInLine($content, $settings['social_icons']['layout_plugin_settings']['theme']);
		}
	}
	
	private function iconsInLine(&$content, $theme){
			$wrapper = &$content->html['wrapper']['icons'];
			$list = '<ul class="list-inline social-icons theme-'. $theme .'">';
			$this->addIcons($content, $list);
			$list .= '</ul>';
			$icon_list = array(
				'#type' => 'markup',
				'#markup' => $list,
			);
			$wrapper['output'] = $icon_list;
	}
	
	
	private function addIcons(&$content, &$list){
		foreach($content->terms as $tid => $data){
				$term_display = new SocialIconsTermsHandler;
				$c = new stdClass;
				$c->term = $data['term'];
				$term_display->displayOutput($c, $data['term_settings']->settings);
				if(!empty($c->taxonomy_term)){
					$list .= "<li>$c->taxonomy_term</li>";
				}
			}
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		
	}

}


?>