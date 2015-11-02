/*
	Plugin	: Bootstrap Collapse
	Author	: Michael Janea (michaeljanea.byethost7.com)
	Version	: 1.0
*/



( function($) {
	/*
	

	CKEDITOR.plugins.add('bootstrapCollapse', {
		requires: 'widget',
		//icons: 'bootstrapCollapse',
		init: function (e) {
			
			
			
			

			e.widgets.add('BootstrapCollapse', {
				button: 'Bootsrap Collapse',
				template: '<div class="panel-group" role="tablist" aria-multiselectable="true" id="">' + '<div class="panel panel-default">' + '<div class="panel-heading" role="tab" id="">' + '<h4 class="panel-title">' + '<a class="collapsed" data-toggle="collapse" data-parent="" href="" aria-expanded="false" aria-controls=""></a>' + '</h4>' + '</div>' + '<div id="" class="panel-collapse collapse" role="tabpanel" aria-labelledby="">' + '<div class="panel-body"></div>' + '</div>' + '</div>' + '</div>',
				allowedContent: 'div(*)[*];h4(*);a(*)[*]',
				dialog: 'bootstrapCollapseDialog',
				upcast: function (a) {
					return a.name == 'div' && a.hasClass('panel-group')
				},
				init: function () {
					for (var a = 0; a < this.element.$.children.length; a++) {
						eval("this.setData('bootstrapCollapse_item" + (a + 1) + "', '" + this.element.$.children[a].firstChild.firstChild.firstChild.innerHTML + "')");
						eval("this.setData('bootstrapCollapse_content" + (a + 1) + "', '" + this.element.$.children[a].lastChild.firstChild.innerHTML + "')")
					}
				},
				data: function () {
					var a = new Date();
					var b = a.getTime();
					var c = '';
					for (var d = 0; d <= 100; d++) {
						eval("bootstrapCollapse_title = this.data.bootstrapCollapse_item" + d);
						eval("bootstrapCollapse_content = this.data.bootstrapCollapse_content" + d);
						if (bootstrapCollapse_title) {
							c += ' 							<div class="panel panel-default"> 								<div class="panel-heading" role="tab" id="heading' + d + '"> 									<h4 class="panel-title"> 										<a class="collapsed" data-toggle="collapse" data-parent="#collapse' + b + '" href="#collapse' + d + '" aria-expanded="false" aria-controls="collapse' + d + '"> 											' + bootstrapCollapse_title + ' 										</a> 									</h4> 								</div> 								<div id="collapse' + d + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading' + d + '"> 									<div class="panel-body"> 										' + bootstrapCollapse_content + ' 									</div> 								</div> 							</div> 						'
						}
					}
					this.element.setAttribute('id', 'collapse' + b);
					this.element.$.innerHTML = c
				}
			});

			// Register the dialog. The actual dialog definition is below.
      CKEDITOR.dialog.add('bootstrapCollapseDialog', CKEDITOR.plugins.getPath('bootstrapCollapse') + 'dialogs/bootstrapCollapse.js');
 
      // Now that CKEditor knows about our dialog, we can create a
      // command that will open it
      e.addCommand('BootstrapCollapseCommand', new CKEDITOR.dialogCommand( 'bootstrapCollapseDialog' ));
      
      // styles
      CKEDITOR.document.appendStyleSheet(CKEDITOR.plugins.getPath('bootstrapCollapse') + 'css/style.css');
			
			e.ui.addButton( 'bootstrapCollapse',
        {
          label : 'Collapse',
          command : 'BootstrapCollapseCommand',
          icon: this.path + 'images/icon.png'
        }
      );
			
			
     
			
			
		},
	});
*/



 // All CKEditor plugins are created by using the CKEDITOR.plugins.add function
  // The plugin name here needs to be the same as in hook_ckeditor_plugin()
  // or hook_wysiwyg_plugin()
 

  CKEDITOR.plugins.add( 'bootstrapCollapse',
  {
	  button: 'Bootsrap Collapse',
	  template: '<div class="panel-group" role="tablist" aria-multiselectable="true" id="">' + '<div class="panel panel-default">' + '<div class="panel-heading" role="tab" id="">' + '<h4 class="panel-title">' + '<a class="collapsed" data-toggle="collapse" data-parent="" href="" aria-expanded="false" aria-controls=""></a>' + '</h4>' + '</div>' + '<div id="" class="panel-collapse collapse" role="tabpanel" aria-labelledby="">' + '<div class="panel-body"></div>' + '</div>' + '</div>' + '</div>',
    	// the init() function is called upon the initialization of the editor instance
    init: function (editor) {
 
      // Register the dialog. The actual dialog definition is below.
      CKEDITOR.dialog.add('bootstrapCollapseDialog', CKEDITOR.plugins.getPath('bootstrapCollapse') + 'dialogs/bootstrapCollapse.js');
 
      // Now that CKEditor knows about our dialog, we can create a
      // command that will open it
      editor.addCommand('BootstrapCollapseCommand', new CKEDITOR.dialogCommand( 'bootstrapCollapseDialog' ));
      
      // styles
      CKEDITOR.document.appendStyleSheet(CKEDITOR.plugins.getPath('bootstrapCollapse') + 'css/style.css');
 
      // Finally we can assign the command to a new button that we'll call youtube
      // Don't forget, the button needs to be assigned to the toolbar. Note that
      // we're CamelCasing the button name (YouTube). This is just because other
      // CKEditor buttons are done this way (JustifyLeft, NumberedList etc.)
      editor.ui.addButton( 'bootstrapCollapse',
        {
          label : 'Collapse',
          command : 'BootstrapCollapseCommand',
          icon: this.path + 'images/icon.png'
        }
      );
      
     if ( editor.contextMenu ) {
		    editor.addMenuGroup( 'accordionGroup' );
		    editor.addMenuItem( 'AccordionItem', {
		        label: 'Edit Accordion',
		        icon: this.path + 'images/icon.png',
		        command: 'BootstrapCollapseCommand',
		        group: 'accordionGroup'
		    });
		    editor.contextMenu.addListener( function( element ) {
		        if ( $(element.$).parents('.panel-group').get(0) ) {
		            return { AccordionItem: CKEDITOR.TRISTATE_OFF };
		        }
		    });
			}

      
      
      for (var a = 0; a < editor.element.$.children.length; a++) {
						eval("this.setData('bootstrapCollapse_item" + (a + 1) + "', '" + this.element.$.children[a].firstChild.firstChild.firstChild.innerHTML + "')");
						eval("this.setData('bootstrapCollapse_content" + (a + 1) + "', '" + this.element.$.children[a].lastChild.firstChild.innerHTML + "')")
					}
      
 
    },
    data: function () {
					var a = new Date();
					var b = a.getTime();
					var c = '';
					for (var d = 0; d <= 100; d++) {
						eval("bootstrapCollapse_title = this.data.bootstrapCollapse_item" + d);
						eval("bootstrapCollapse_content = this.data.bootstrapCollapse_content" + d);
						if (bootstrapCollapse_title) {
							c += ' 							<div class="panel panel-default"> 								<div class="panel-heading" role="tab" id="heading' + d + '"> 									<h4 class="panel-title"> 										<a class="collapsed" data-toggle="collapse" data-parent="#collapse' + b + '" href="#collapse' + d + '" aria-expanded="false" aria-controls="collapse' + d + '"> 											' + bootstrapCollapse_title + ' 										</a> 									</h4> 								</div> 								<div id="collapse' + d + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading' + d + '"> 									<div class="panel-body"> 										' + bootstrapCollapse_content + ' 									</div> 								</div> 							</div> 						'
						}
					}
					editor.element.setAttribute('id', 'collapse' + b);
					editor.element.$.innerHTML = c
				}
  });

  
  
  
			
			
			
			
		
  
  
  
  
	
	})(jQuery);
	
	
	
	
