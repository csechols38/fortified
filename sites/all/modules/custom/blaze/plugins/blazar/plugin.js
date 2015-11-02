
var BlazarApiKey = 'b77c32f14134b4af9252c2688bf002c3';

var BlazarHelper = {
	
		init: function(){
			jQuery.getScript( "http://54.149.180.84/sites/all/libraries/blazar/blazar.js").done(function(){
			  console.log( "loading the blazar plugin ..." );
			  BlazarDialog.load();
			});
		},
		load: function(){
			// ...
		}
	}

	BlazarHelper.init();
	

( function($) {
		
	CKEDITOR.plugins.blazar = {
	  button: 'Blazar',
    	// the init() function is called upon the initialization of the editor instance
    init: function (editor) {
 
      // Register the dialog. The actual dialog definition is below.
      CKEDITOR.dialog.add('BlazarDialog', CKEDITOR.plugins.getPath('blazar') + 'dialogs/blazarDialog.js');
 
      // Now that CKEditor knows about our dialog, we can create a
      // command that will open it
      editor.addCommand('BlazarCommand', new CKEDITOR.dialogCommand( 'BlazarDialog' ));
			
			//CKEDITOR.document.appendStyleSheet(CKEDITOR.plugins.getPath('blazar') + 'css/style.css');
      // styles
      editor.addContentsCss('//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css');
 
      // Finally we can assign the command to a new button that we'll call youtube
      // Don't forget, the button needs to be assigned to the toolbar. Note that
      // we're CamelCasing the button name (YouTube). This is just because other
      // CKEditor buttons are done this way (JustifyLeft, NumberedList etc.)
      editor.ui.addButton( 'blazar',
        {
          label : 'Blazar',
          command : 'BlazarCommand',
          icon: this.path + 'images/icon.png'
        }
      );
      
     if ( editor.contextMenu ) {
		    editor.addMenuGroup('Blazar');
		    editor.addMenuItem('BlazarItem', {
		        label: 'Blazar :: Edit',
		        icon: this.path + 'images/icon.png',
		        command: 'BlazarCommand',
		        group: 'Blazar'
		    });
		    editor.contextMenu.addListener( function( element ) {
		        if ( $(element.$).parents('.blazar-element').get(0) ) {
		            return { BlazarItem: CKEDITOR.TRISTATE_OFF };
		        }
		    });
			} 
    },
	};
	

CKEDITOR.plugins.add( 'blazar', CKEDITOR.plugins.blazar);
  
 })(jQuery);