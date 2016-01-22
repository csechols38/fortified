<?php
  
  
class DynoBlockDefaultLayout implements DynoBlockLayout {

  public $layout = array();

  public function init($values){
    $this->layout['container'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('dyno-default-container'),
      ),
    );
    if(!empty($values['title']) || (!empty($values['title']) && $values['title'] != '<none>')){
      $this->layout['container']['title'] = array(
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => token_replace($values['title']),
        '#attributes' => array(
          'class' => array('dynoblock-title'),
        ),
      );
    }
    if(!empty($values['body'])){
      $this->layout['container']['body'] = array(
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' => token_replace($values['body']),
        '#attributes' => array(
          'class' => array('dynoblock-body'),
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


class DynoBlockDefaultForm implements DynoBlockForm {

  public $form = array();

  public function init(){
    return $this;
  }

  public function build($form_state = array()){
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
  }

  public function render(){
    return render($this->form);
  }
}