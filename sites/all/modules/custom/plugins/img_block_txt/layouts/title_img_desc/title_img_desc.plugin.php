<?php

function title_img_desc_taxonomy_block_layout_handler(){
	return array('handler' => 'TitleImageDescLayoutHandler');
}


class TitleImageDescLayoutHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Title Image Description'),
			'description' => t('A layout with a title image and description'),
			'module' => 'img_block_txt',
			'parent plugin' => 'img_block_txt',
		);
	}
	
	public function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings){
		
	}
	
	public function displayOutput(&$content, $settings = array()){
		//dpm($content);
		$terms = array();
		$r = new ApiContent();
		$terms['wrapper'] = $r->type('container')->_class('ibt-wrapper')->r();
		foreach($content->terms as $key => $value){
			$terms['wrapper'][$key] = $r->type('container')->_class('ibt-single-wrapper')->r();
			if(!empty($value['term'])){
				$term = $value['term'];
				$path = !empty($term->field_link['und'][0]['value']) ? $term->field_link['und'][0]['value'] : current_path();
				$link = l($term->name, $path);
				$terms['wrapper'][$key]['title'] = $r->type('html_tag')->tag('div')->value($link)->_class('ibt-title-link')->r();
				if(!empty($term->field_image['und'][0])){
					$img = $term->field_image['und'][0];
					$img = field_view_value('taxonomy_term', $term, 'field_image', $img, 'full', $langcode = NULL);
					$terms['wrapper'][$key]['image'] = $r->type('html_tag')->tag('div')->value(render($img))->_class('ibt-img')->r();
				}
				$link = l(">", $path);
				$desc = $term->description . ' ' . $link;
				$terms['wrapper'][$key]['desc'] = $r->type('html_tag')->tag('div')->value($desc)->_class('ibt-description')->r();
			}
		}
		$content->taxonomy_term = $terms;
	}
	
	public function loadImage($term, &$html){
		if(!empty($term->field_link_image['und'])){
			$html['w']['imges'] = $this->r->type('container')->_class('ibt-stacked-images')->r();
			foreach($term->field_link_image['und'] as $delta => $value){
				$img = field_view_value('taxonomy_term', $term, 'field_link_image', $value, 'full', $langcode = NULL);
				$html['w']['imges'][$delta] = $this->r->type('html_tag')->tag('div')->value(render($img))->_class('ibt-stacked-img')->r();
			}
		}
		return $img;
	}
	
	private function addDependencies(){
		//drupal_add_css(drupal_get_path('module', 'img_block_txt') . '/css/img_block_txt.css');
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return array();
	}

}


?>