<?php
  
  class BootstrapMediaObject__form implements DynoBlockForm {

  public $form = array();

  public function init(){
    return $this;
  }

  public function build($form_state = array()){
    $this->form['position'] = array(
      '#type' => 'select',
      '#title' => t('Position'),
      '#description' => t('Image left or right.'),
      '#options' => array(
        'left' => 'Left',
        'right' => 'Right',
      ),
      '#default_value' => !empty($form_state['position']) ? $form_state['position'] : 'left',
    );
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
    $this->form['style'] = array(
      '#type' => 'select',
      '#title' => t('Image Style'),
      '#options' => image_style_options(),
      '#default_value' => !empty($form_state['style']) ? ($form_state['style']) : '',
    );
    $this->form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#value' => !empty($form_state['title']) ? ($form_state['title']) : '',
      '#description' => t('This blocks title. set to <none> to not display the title.'),
      '#size' => 60,
      '#maxlength' => 255,
      '#name' => 'title',
    );
    $this->form['body'] = array(
      '#type' => 'textarea',
      '#title' => t('Body'),
      '#value' => !empty($form_state['body']) ? ($form_state['body']) : '',
      '#description' => t('<html> html and tokens are allowed.'),
      '#default_value' => '',
      '#format' => 'full_html',
      '#name' => 'body',
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



class BootstrapMediaObject__display implements DynoBlockLayout {

  public $layout = array();

  public function init($values){
    $this->layout['c'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('media', 'dyno-media'),
      ),
      '#attached' => array(
        'css' => array(
          $this->directory . "/css/media.css",
        ),
      ),
    );
    $this->layout['c']['position'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('media-' . $values['position']),
      ),
      '#weight' => $values['position'] == 'left' ? 0 : 2
    );

    if(!empty($values['fid'])){
      $file = file_load($values['fid']);
      $variables = array(
				'style_name' => $values['style'],
				'path' => $file->uri,
			);
			$image = theme_image_style($variables);
      $this->layout['c']['position']['img'] = array(
      	'#type' => 'markup',
      	'#markup' => render($image),
      );
    }

    $this->layout['c']['body'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('media-body', 'media-middle'),
      ),
       '#weight' => 1,
    );
    $this->layout['c']['body']['title'] = array(
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $values['title'],
      '#attributes' => array(
        'class' => array('media-heading'),
      ),
    );
    $this->layout['c']['body']['body'] = array(
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => $values['body'],
      '#attributes' => array(
        'class' => array('media-desc'),
      ),
    );

    return $this;
  }

  public function preRender($values){
    return $this->layout;
  }

  public function render(){
    return render($this->layout);
  }

}