/*
	Plugin	: Blazar
	Author	: csechols
	Version	: 1.0
*/



(function($){

CKEDITOR.dialog.add('BlazarDialog', function (editor) {
	return {
		
		title: BlazarDialog.data.dialogTitle,
		minWidth: BlazarDialog.data.minWidth,
		minHeight: BlazarDialog.data.minHeight,
		resizable: BlazarDialog.data.resizable,
		buttons: [ CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton ],
		
		onLoad: function(e){
			//BlazarDialog.load();
		},
		onCancle: function(){
			console.log("here");
		},
		
		onShow: function(){
			
			// load blazar stylesheets
			BlazarDialog.applyStelysheets();
			
			var selection = editor.getSelection();
			var element = selection.getStartElement();
			if (element) {
				element = $(element.$).parents('.blazar-element');
				if (!element || !element.get(0)) {
						element = editor.document.createElement( 'div' );
				    this.insertMode = false;
				} else {
					this.element = element;
					this.insertMode = true;
				}
			}
			if (this.insertMode){
				this.setupContent( element );
			}
		},
		
		onOk: function(){
			
			if(!this.element){
				var abbr = editor.document.createElement( 'div' );  
				var id = Math.round(Math.floor(Math.random()*50101));
		    // set the html
		    abbr.setHtml("<div id="+id+"></div>");
				BlazarDialog.setInputElement(abbr);
		    // insert the html
		    editor.insertElement( abbr );
				// process the widget form
				BlazarDialog.processWidgetForm();
			} else {
				BlazarDialog.setInputElement(this.element);
				BlazarDialog.processWidgetForm();
			}
		},
		
		contents: [{
			id: 'blazar',
			label: 'Blazar',
			elements: [{
				type: 'html',
				html: '<div id="blazar"> '+ BlazarDialog.data.template +' </div>',
				commit: function (collapse_html, accordion_id) {
					
				},
				setup: function (element) {
					BlazarDialog.setUp(element);
				},
			}]
		}]
	}
});

})(jQuery);




