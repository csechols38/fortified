<?php

class SocialIconsTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		$settings['icon'] = array(
			'#type' => 'select',
			'#title' => t('Social Icon'),
			'#options' => array(
				'facebook' => 'Facebook',
				'twitter' => 'Twitter',
				'google_plus' => 'Google Plus',
				'linkedin' => 'Linkedin',
				'dribble' => 'Dribble',
			),
			'#default_value' => !empty($defaults['icon']) ? $defaults['icon'] : '',
		);
		$settings['path'] = array(
			'#type' => 'textfield',
			'#title' => t('Social Media Path'),
			'#default_value' => !empty($defaults['path']) ? $defaults['path'] : '#',
		);
		$settings['method'] = array(
			'#type' => 'select',
			'#title' => t('Icon Type'),
			'#options' => array(
				'glyphicon' => 'Glyphicon',
			),
			'#default_value' => !empty($defaults['method']) ? $defaults['method'] : 'glyphicon',
		);
	}

	public function displayOutput(&$content, $settings = array()){
		$icon = !empty($settings['social_icons']['icon']) ? $settings['social_icons']['icon'] : NULL;
		$method = !empty($settings['social_icons']['method']) ? $settings['social_icons']['method'] : 'glyphicon';
		if($icon){
			$class = social_icons_glyphicon_class_list($icon);
			switch($method){
				case 'glyphicon':
					$glyphicon = array(
						'#theme' => 'link',
						'#path' => !empty($settings['social_icons']['path']) ? $settings['social_icons']['path'] : '',
						'#text' => '<i class="'. $class .'"></i>',
						'#options' => array(
							'html' => TRUE,
							'attributes' => array(
								'class' => array('btn-social btn-outline'),
							),
						),
					);
					$content->taxonomy_term = render($glyphicon);
					break;
			}
		}
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_image',
				'label' => 'Icon',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}

}


class SocialIconsVocabsHandler extends BtVocabularyHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		$w_options = array(
			NULL => 'None',
		);
		for($i = 1; $i <= 12; $i++){
			$w_options["col-lg-$i"] = "$i columns";
		}
		$settings['wrapper'] = array(
			'#type' => 'select',
			'#title' => t('Wrapper Columns'),
			'#default_value' => !empty($defaults['wrapper']) ? $defaults['wrapper'] : '_none',
			'#options' => $w_options,
		);
	}
	
	public function displayOutput(&$content, $settings = array()){
		$html = array();
		$wrapper_class = !empty($settings['social_icons']['wrapper']) ? $settings['social_icons']['wrapper'] : NULL;
		$html['wrapper'] = BtApi::Container(array('social-icons-wrapper', $wrapper_class));
		$title = !empty($s_settings['title']) ? $s_settings['title'] : '';
		if(!empty($settings['social_icons']['layout_plugins'])){
			switch($settings['social_icons']['layout_plugins']){
				case 'icons_inline':
						$html['wrapper']['icons'] = BtApi::Container(array('social-icons-inline'));
						$content->html = $html;
					break;
			}
		}
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_image',
				'label' => 'Icon',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}
	
}