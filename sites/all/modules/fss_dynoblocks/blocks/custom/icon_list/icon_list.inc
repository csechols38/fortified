<?php
  
  class CustomIconList__form implements DynoBlockForm {

  public $form = array();

  public function init(){
    return $this;
  }

  public function build($form_state = array()){
    $values = !empty($this->rebuild) ? $form_state['input'] : $form_state;
    $this->form['container'] = array(
    		'#type' => 'fieldset',
    		'#title' => t('Header'),
    		'#collapsed' => false,
    		'#collapsible' => true,
    		//'#group' => '',
    	);
    $this->form['container']['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#value' => !empty($values['title']) ? ($values['title']) : '',
      '#description' => t('This blocks title. set to <none> to not display the title.'),
      '#size' => 60,
      '#maxlength' => 255,
      '#name' => 'title',
    );
    $this->form['container']['body'] = array(
      '#type' => 'textarea',
      '#title' => t('Body'),
      '#value' => !empty($values['body']) ? ($values['body']) : '',
      '#description' => t('<html> html and tokens are allowed.'),
      '#default_value' => '',
      '#format' => 'full_html',
      '#name' => 'body',
    );
    $this->form['icon_list'] = array(
    		'#type' => 'fieldset',
    		'#title' => t('Icon List'),
    		'#collapsed' => false,
    		'#collapsible' => true,
    		'#attributes' => array(
          'id' => array('icon-list-wrapper'),
        ),
        '#tree' => TRUE,
    	);

    for($i = 0; $i <= count($values['icon_list']); $i++){
      $this->form['icon_list'][$i] = array(
      	'#type' => 'container',
      	'#attributes' => array(
      		'class' => array('icon-item'),
      	),
      ) + $this->iconItem(!empty($values['icon_list'][$i]) ? $values['icon_list'][$i] : array(), $i);
    }
    
    
    $this->form['add_another'] = array(
      '#type' => 'submit', 
      //'#button_type' => 'button',
      '#submit' => array(DynoBlockForms::ajaxSubmit()),
      '#value' => t('Add Another'),
      '#ajax' => array(
        'wrapper' => 'icon-list-wrapper',
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
  
  public function ajaxCallback($form, &$form_state){
    return array('commands' => NULL, 'return_element' => $form['icon_list']);
  }
  
  public function ajaxSubmit($form, &$form_state){
    $type = $form_state['triggering_element']['#attributes']['#type'];
    switch ($type) {
      case 'remove':
        $id = $form_state['triggering_element']['#attributes']['#id'];
        $new_values = array();
        foreach($form_state['input']['icon_list'] as $key => $data){
          if($key != $id){
            $new_values[$key] = $data;    
          }
        }
        $form_state['input']['icon_list'] = $new_values;
        break;  
      case 'add':
        $form_state['input']['icon_list'][] = array();
        break;
      default:
    }
  }
  
  private function iconItem($values = array(), $id){
    $item = array();
    $item['icon'] = array(
        '#type' => 'textfield',
        '#title' => t('Icon'),
        '#value' => !empty($values['icon']) ? ($values['icon']) : '',
        '#size' => 40,
        '#maxlength' => 255,
      );
      $item['body'] = array(
        '#type' => 'textarea',
        '#title' => t('Body'),
        '#value' => !empty($values['body']) ? ($values['body']) : '',
        '#description' => t('<html> html and tokens are allowed.'),
        '#default_value' => '',
        '#format' => 'full_html',
      );
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
        'wrapper' => 'icon-list-wrapper',
        'callback' => DynoBlockForms::ajaxCallback(),
        'method' => 'replace',
        'effect' => 'fade',
      ),
    );
      return $item;
  }

  public function formSubmit(&$form_state){
    // ...
  }

  public function render(){
    return render($this->form);
  }
}



class CustomIconList__display implements DynoBlockLayout {

  public $layout = array();

  public function init($values){
    $this->layout['container'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array('icon-list-group'),
      ),
      '#attached' => array(
        'css' => array(
          $this->directory . "/css/icon_list.css",
        ),
      ),
    );
    $this->layout['container']['header'] = array(
    	'#type' => 'container',
    	'#attributes' => array(
    		'class' => array('icon-list-head'),
    	),
    );
    if(!empty($values['title'])){
      $this->layout['container']['header']['title'] = array(
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => token_replace($values['title']),
        '#attributes' => array(
          'class' => array('list-group-item-heading'),
        ),
      );
    }
    if(!empty($values['body'])){
      $this->layout['container']['header']['body'] = array(
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => token_replace($values['body']),
        '#attributes' => array(
          'class' => array('list-group-item-text'),
        ),
      );
    }
    $this->layout['container']['list'] = array(
    	'#type' => 'container',
    	'#attributes' => array(
    		'class' => array('icon-list'),
    	),
    );
    if(!empty($values['icon_list'])){
      unset($values['icon_list']['num_of_icons']);
      $chunks = array_chunk($values['icon_list'], ceil((count($values['icon_list']) -1) / 2));
      foreach($chunks as $delta => $icons){
          $this->layout['container']['list'][$delta] = array(
          	'#type' => 'container',
          	'#attributes' => array(
          		'class' => array('col-sm-6'),
          	),
          );
          foreach($icons as $key => $icon){
            $this->layout['container']['list'][$delta][$key] = array(
            	'#type' => 'container',
            	'#attributes' => array(
            		'class' => array('media', 'icon-media'),
            	),
            );
            $this->layout['container']['list'][$delta][$key]['left'] = array(
            	'#type' => 'container',
            	'#attributes' => array(
            		'class' => array('media-left',),
            	),
            );
            $this->layout['container']['list'][$delta][$key]['left']['icon'] = array(
            	'#type' => 'html_tag',
            	'#tag' => 'span',
            	'#value' => '',
            	'#attributes' => array(
            		'class' => array($icon['icon'], 'icon', 'media-object'),
            	),
            );
            $this->layout['container']['list'][$delta][$key]['body'] = array(
            	'#type' => 'container',
            	'#attributes' => array(
            		'class' => array('media-body', 'media-middle'),
            	),
            );
            $this->layout['container']['list'][$delta][$key]['body']['body'] = array(
            	'#type' => 'html_tag',
            	'#tag' => 'p',
            	'#value' => $icon['body'],
            	'#attributes' => array(
            		'class' => array('icon-body'),
            	),
            );
          }
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