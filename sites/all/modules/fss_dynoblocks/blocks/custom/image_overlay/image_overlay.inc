<?php
  
  class ImageOverlay__form implements DynoBlockForm {

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
    $this->form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#value' => !empty($form_state['title']) ? ($form_state['title']) : '',
      '#size' => 40,
      '#maxlength' => 255,
    );
    $this->form['text'] = array(
      '#type' => 'textarea',
      '#title' => t('Text'),
      '#value' => !empty($form_state['text']) ? ($form_state['text']) : '',
      '#description' => t('<html> html and tokens are allowed.'),
      '#default_value' => '',
      '#format' => 'full_html',
    );
    $this->form['link'] = array(
      '#type' => 'textfield',
      '#title' => t('Link'),
      '#value' => !empty($form_state['link']) ? ($form_state['link']) : '',
      '#size' => 40,
      '#maxlength' => 255,
    );
    return $this;
  }

  public function formSubmit(&$form_state){
    // .. process form values
    if(!empty($form_state['fid'])){
      $file = file_load($form_state['fid']);
      // Change status to permanent.
      $file->status = FILE_STATUS_PERMANENT;
      // Save.
      $saved = file_save($file);
      // Record that the module (in this example, user module) is using the file.
      file_usage_add($file, 'bt_dynoblock', 'field', $form_state['bid']);
    }
  }

  public function render(){
    return render($this->form);
  }
}



class ImageOverlay__display implements DynoBlockLayout {

  public $layout = array();

  public function init($values){
    $this->layout['c'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('image-overlay'),
      ),
      '#attached' => array(
        'css' => array(
          $this->directory . "/css/image_overlay.css",
        ),
        'js' => array(
          $this->directory . "/js/image_overlay.js",
        ),
      ),
    );
    $this->layout['c']['inner'] = array(
    	'#type' => 'container',
    	'#attributes' => array(
    		'class' => array('overlay-inner'),
    	),
    );
    if(!empty($values['fid'])){
      $file = file_load($values['fid']);
      $url = file_create_url($file->uri);
      $this->layout['c']['inner']['img'] = array(
      	'#type' => 'html_tag',
      	'#tag' => 'img',
      	'#value' => '',
      	'#attributes' => array(
      		'src' => $url,
      	),
      );
    }
    
    $this->layout['c']['inner']['text_container'] = array(
      	'#type' => 'container',
      	'#attributes' => array(
      		'class' => array('overlay-text-container'),
      	),
      );
    $this->layout['c']['inner']['text_container']['inner'] = array(
      	'#type' => 'container',
      	'#attributes' => array(
      		'class' => array('overlay-text-container-inner'),
      	),
      );
      
      
    if(!empty($values['title'])){
      $this->layout['c']['inner']['text_container']['inner']['title'] = array(
      	'#type' => 'html_tag',
      	'#tag' => 'h1',
      	'#value' => $values['title'],
      	'#attributes' => array(
      		'class' => array('overlay-title'),
      	),
      );
    }
    
    if(!empty($values['text'])){
      $this->layout['c']['inner']['text_container']['inner']['text'] = array(
      	'#type' => 'html_tag',
      	'#tag' => 'div',
      	'#value' => $values['text'],
      	'#attributes' => array(
      		'class' => array('overlay-text'),
      	),
      );
    }
    
    if(!empty($values['link'])){
      $link = l(t('Learn More'), $values['link'], array(
        'attributes' => array(
          'class' => array('btn btn-primary'),
        ),
      ));
      $this->layout['c']['inner']['text_container']['inner']['path'] = array(
      	'#type' => 'markup',
      	'#markup' => $link,
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