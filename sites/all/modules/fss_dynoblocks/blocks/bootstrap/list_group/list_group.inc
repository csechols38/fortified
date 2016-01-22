<?php

class BootstrapListGroup__form implements DynoBlockForm {

  public $form = array();

  public function init(){
    return $this;
  }

  public function build($form_state = array()){
    $values = !empty($this->rebuild) ? $form_state['input'] : $form_state;
    $list = !empty($values['list']) ? $values['list'] : array();

    $this->form['title'] = array(
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => 'List Group',
      '#attributes' => array(
        'class' => array(''),
      ),
    );

    $this->form['list'] = array(
      '#type' => 'container',
      '#tree' => TRUE,
      '#attributes' => array(
        'id' => 'list-group-wrapper',
      ),
    );

    for($i = 0; $i <= count($list); $i++){
      $this->form['list'][$i] = array(
        '#type' => 'fieldset',
        '#title' => t('List Item'),
        '#collapsed' => false,
        '#collapsible' => true,
        //'#group' => '',
      ) + $this->addListItem($values, $i);
    }

    $this->form['add_another'] = array(
      '#type' => 'submit',
      //'#button_type' => 'button',
      '#submit' => array(DynoBlockForms::ajaxSubmit()),
      '#value' => t('Add Another'),
      '#ajax' => array(
        'wrapper' => 'list-group-wrapper',
        'callback' => DynoBlockForms::ajaxCallback(),
        'method' => 'replace',
        'effect' => 'fade',
      ),
      '#attributes' => array(
        '#type' => 'add',
      ),
    );

    return $this;
  }

  public function formSubmit(&$form_state){
    // ...
  }

  public function render(){
    return render($this->form);
  }

  public function addListItem($values = array(), $id){
    $item['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#value' => !empty($values['list'][$id]['title']) ? ($values['list'][$id]['title']) : '',
      '#description' => t('This blocks title. set to <none> to not display the title.'),
      '#size' => 60,
      '#maxlength' => 255,
    );
    $item['body'] = array(
      '#type' => 'textarea',
      '#title' => t('Body'),
      '#value' => !empty($values['list'][$id]['body']) ? ($values['list'][$id]['body']) : '',
      '#description' => t('<html> html and tokens are allowed.'),
      '#default_value' => '',
      '#format' => 'full_html',
    );
    if($id > 0){
      $item['remove'] = array(
        '#type' => 'submit',
        //'#button_type' => 'button',
        '#submit' => array(DynoBlockForms::ajaxSubmit()),
        '#value' => t('Remove'),
        '#attributes' => array(
          '#id' => $id,
          '#type' => 'remove',
          'class' => array('btn-danger'),
        ),
        '#ajax' => array(
          'wrapper' => 'list-group-wrapper',
          'callback' => DynoBlockForms::ajaxCallback(),
          'method' => 'replace',
          'effect' => 'fade',
        ),
      );
    }

    return $item;
  }


  public function ajaxCallback($form, &$form_state){
    return array('commands' => NULL, 'return_element' => $form['list']);
  }

  public function ajaxSubmit($form, &$form_state){
    $type = $form_state['triggering_element']['#attributes']['#type'];
    switch ($type) {
    case 'remove':
      $id = $form_state['triggering_element']['#attributes']['#id'];
      $new_values = array();
      foreach($form_state['input']['list'] as $key => $data){
        if($key != $id){
          $new_values[$key] = $data;
        }
      }
      $form_state['input']['list'] = $new_values;
      break;
    case 'add':
      $form_state['input']['list'][] = array();
      break;
    default:
    }
  }
}



class BootstrapListGroup__display implements DynoBlockLayout {

  public $layout = array();

  public function init($values){
    $this->layout['container'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('list-group'),
      ),
      '#attached' => array(
        'css' => array(
          $this->directory . "/css/list_group.css",
        ),
      ),
    );
    if(!empty($values['list'])){

      foreach($values['list'] as $delta => $list){
        $item = array();
        if(!empty($list['title'])){
          $item['title'] = array(
            '#type' => 'html_tag',
            '#tag' => 'h4',
            '#value' => token_replace($list['title']),
            '#attributes' => array(
              'class' => array('list-group-item-heading'),
            ),
          );
        }

        if(!empty($list['body'])){
          $item['body'] = array(
            '#type' => 'html_tag',
            '#tag' => 'p',
            '#value' => token_replace($list['body']),
            '#attributes' => array(
              'class' => array('list-group-item-text'),
            ),
          );
        }
        
        $this->layout['container'][$delta]['inner'] = array(
        	'#type' => 'html_tag',
        	'#tag' => 'a',
        	'#value' => render($item),
        	'#attributes' => array(
        		'class' => array('list-group-item'),
        		'href' => '#',
        	),
        );

      }
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