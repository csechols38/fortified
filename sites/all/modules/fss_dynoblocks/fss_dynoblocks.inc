<?php

class BootstrapJumbotron__display implements DynoBlockLayout {

  public $layout = array();

  public function init($values){
    $this->layout['c'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('jumbotron'),
      ),
    );
    if(!empty($values['title'])){
      $this->layout['c']['title'] = array(
      	'#type' => 'html_tag',
      	'#tag' => 'h1',
      	'#value' => !empty($values['title']) ? $values['title'] : '',
      	'#attributes' => array(
      		'class' => array(''),
      	),
      );
    }
    if(!empty($values['body'])){
      $this->layout['c']['body'] = array(
      	'#type' => 'html_tag',
      	'#tag' => 'p',
      	'#value' => !empty($values['body']) ? $values['body'] : '',
      	'#attributes' => array(
      		'class' => array(''),
      	),
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


class BootstrapJumbotron__from implements DynoBlockForm {

  public $form = array();

  public function init(){
    return $this;
  }

  public function build($form_state = array()){
    $this->form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#description' => t('The title you enter here appears on the page.'),
      '#size' => 40,
      '#maxlength' => 255,
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
     // ... :)
  }

  public function render(){
    return render($this->form);
  }
}