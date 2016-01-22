<?php
  
  
class ImageDivider__display implements DynoBlockLayout {

  public $layout = array();

  public function init($values){
    if(!empty($values['fid'])){
      $file = file_load($values['fid']);
			$url = file_create_url($file->uri);
			$this->layout['w'] = array(
				'#type' => 'container',
				'#attributes' => array(
					'class' => array('image-divider'),
				),
			);
      $this->layout['w']['img'] = array(
      	'#type' => 'html_tag',
      	'#tag' => 'div',
      	'#value' => '',
      	'#attributes' => array(
        	'class' => array('divider'),
        	'id' => 'divider_' . $values['bid'],
      	),
      );
      drupal_add_css(
      '#divider_' . $values['bid'] .'::before {
        content: " ";
        height: 100%;
        width: 100%;
        display: block;
        background: url('. base_path() .'sites/default/files/'. $file->filename .') no-repeat;
        background-position: center;
        position: absolute;
        background-size: 100% 41px;
      }
      ',
      array('type' => 'inline')
    );
    }
    return $this;
  }

  public function preRender($values){
    return $this->layout;
  }

  public function render(){
    return render($this->layout);
  }

}


class ImageDivider__form implements DynoBlockForm {

  public $form = array();

  public function init(){
    return $this;
  }

  public function build($form_state = array()){
    $this->form['image'] = array(
      '#type' => 'media',
      '#title' => t( 'Upload An Image' ),
      '#description' => t( 'Allowed extensions: gif png jpg jpeg' ),
      '#default_value' => !empty($form_state['fid']) ? array('fid' => $form_state['fid']) : '',
      '#upload_location' => 'public://',
      '#upload_validators' => array(
        'file_validate_extensions' => array( 'gif png jpg jpeg' ),
      ),
    );
    return $this;
  }

  public function formSubmit(&$form_state){
    // .. process form values
  }

  public function render(){
    return render($this->form);
  }
}