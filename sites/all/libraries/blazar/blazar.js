



var BlazarDialog  = {
	
	ajaxCommands: [],
	host: "http://54.149.180.84/blaze",
	history: [],
	
	init: function(){
		console.log("dialog initialized ...");
		BlazarDialog.version = "1.0";
	},
	
	createStylesheet: function(path, s_class){
		var element = document.createElement("link");
		element.setAttribute("rel", "stylesheet");
		element.setAttribute("type", "text/css");
		element.setAttribute("href", path);
		element.setAttribute("class", s_class);
		document.getElementsByTagName("head")[0].appendChild(element);
	},
	
	removeStyleSheet: function(element){
		jQuery(element).remove();
	},
	
	sendData: function(data, callback){		
		data.obj = this.data;
		var send = jQuery.post(this.host, data);
		send.done(function(response){
			BlazarDialog.data = response;
			console.log('external data loaded ...');
			if(callback){
				callback();
			}
		}, "json");
	},
	
	load: function(data){
		console.log('loading blazar data ...');
		var data = {
			action: 'init',
			api_key: "4828490238432840dsadasd",
		}
		BlazarDialog.sendData(data, this.init);
	},
	
	applyStelysheets: function(){
		var data = BlazarDialog.data;
		for(var s_type in  data.stylesheets){
			switch (s_type) {
			  case 'document':
			  	for(var i = 0; i < data.stylesheets['document'].length; i++){
				  	var stylesheet = data.stylesheets['document'][i];
						BlazarDialog.createStylesheet(stylesheet, "stylesheet1");
			  	}
			    break;  
			  case 'editor':
			  
			    break;
			}
		}
	},
	
	navigate: function(to){
		var data = {
			'action': 'navigate',
			'navigate' : to,
		}
		this.sendData(data, BlazarDialog.runAjaxCommands);
	},
	
	addAjaxCommands: function(commands){
		var com = this.ajaxCommands;
		com.push(commands);
		this.ajaxCommands = com;
	},
	
	addHistory: function(step){
		this.history = step;
	},
	
	formSubmitObject : function(action, type){
		var form = jQuery('#blazar-widget-form form').serialize();
		return {
			action: action,
			type: type,
			form: form,
			form_state: BlazarDialog.data.current_widget.form_state,
			obj: BlazarDialog.data,
		};
	},
	
	processWidgetForm: function(){
		var data  = this.formSubmitObject('formSubmit', 'widget');
		var process = jQuery.post(BlazarDialog.host, data);
		process.done(function(response){
			BlazarDialog.data = response;
			if(jQuery(BlazarDialog.inputElement.$).get(0)){
				jQuery(BlazarDialog.inputElement.$).html(BlazarDialog.data.output);
			} else {
				jQuery(BlazarDialog.inputElement).html(BlazarDialog.data.output);
			}
		}, "json");
	},
	
	ajaxProcessForm: function(triggering_element){
		var data  = this.formSubmitObject('formSubmit', 'rebuild');
		data.form_state.triggering_element = triggering_element;
		this.sendData(data, this.runAjaxCommands);
	},
	
	setInputElement: function(element){
		this.inputElement = element;
	},
	
	runAjaxCommands: function(){
		var commands = BlazarDialog.ajaxCommands;
		var plugins = BlazarDialog.data.plugins;
		for(var i = 0; i < commands.length; i++){
			for(var c = 0; c < commands[i].length; c++){
				var command = commands[i][c];
				switch (command.method) {
				  case 'replace':
				  var plugin = plugins[command.plugin];
				  var html;
				  switch(command.target){
						case 'plugin':
							html = plugin.html;
							break;
						case 'widget':
							html = BlazarDialog.data.current_widget.form;
							break;
						}
						console.log("running ajax commands");
				  	jQuery(command.selector).html(html);
				    break; 
				     
				  case 'prepend':
				  	jQuery(command.selector).prepend(command.html);
				  	break;
				  case 'js':
				  	switch(command.target){
					  	case 'widget':
					  		if(typeof(BlazarDialog.data.current_widget.js) != 'undefined'){
						  		BlazarDialog.loadWidgetJs(BlazarDialog.data.current_widget.js);
					  		}
					  		break;
				  	}
				  	break;
				}
			}
		}
		// remove old ajax commands
		BlazarDialog.ajaxCommands = [];
	},
	
	loadWidgetJs: function(js){
		jQuery.each(js, function(type, files){
			switch(type){
				case 'document':
					for(var i = 0; i < files.length; i++){
						var script = document.createElement("script");
					  script.type = 'text/javascript';  
					  script.src = files[i];  
					  jQuery('.cke_wysiwyg_frame').contents().find('head')[0].appendChild(script);  
					}
					break;
			}
		});
	},
	
	loadWidget: function(plugin, widget){
		console.log("loading widget: " + widget + " from plugin: " + plugin);
		var data = {
			action: 'widget',
			plugin: plugin,
			widget: widget,
		};
		this.sendData(data, BlazarDialog.runAjaxCommands);
	},
	
	setUp: function(element){
		var plugin = jQuery(element).data('plugin');
		var widget = jQuery(element).data('widget');
		var values = [];
		var i = 0;
		jQuery(element).find('*').each(function(){
				if(jQuery(this).data('fid')){
					var key = jQuery(this).data('fid');
					values[i] = {
						id: key,
						value: jQuery(this).text(),
					}
					i++;
				}
		});
		var data = {
			obj: BlazarDialog.data,
			action: "formDefaults",
			widget: {
				type: "widget",
				widget: widget,
				plugin: plugin,
				form_state: values,
			},
		};
		var process = jQuery.post(BlazarDialog.host, data);
		process.done(function(response){
			BlazarDialog.data = response;
			var commands = [
				{
					method: 'replace',
					selector: "#action-container",
					target: 'widget',
					widget: widget,
					plugin: plugin,
				},
				{
					method: 'js',
					selector: "document",
					target: 'widget',
					widget: widget,
					plugin: plugin,
				},
			];
			BlazarDialog.addAjaxCommands(commands);
			BlazarDialog.runAjaxCommands();
		}, "json");
	},
	loadDefaults: function(){
		console.log(BlazarDialog);
	}
	
	
	
};


(function($){
	// doc ready
	$(document).ready(function(){
		
		// navigating
		$(document).on('click', '.blazar-navbar button', function(){
			console.log(CKEDITOR.plugins.blazar);
			var machine = $(this).data('parent');
			var commands = [
				{
					method: 'replace',
					selector: "#action-container",
					target: 'plugin',
					plugin: machine,
				},
			];
			BlazarDialog.addAjaxCommands(commands);
			BlazarDialog.navigate(machine);
		});
		// load widget
		$(document).on('click', '.load-widget a', function(){
			var plugin = $(this).data('plugin');
			var widget = $(this).data('widget');
			var history = '';
			history = '<div id="hostory">';
			history	+= '<div><button id="history-back" class="btn btn-default"><a href="javascript:void(0)">Back</a></button></div>';
			history += '</div>';
			var commands = [
				{
					method: 'replace',
					selector: "#action-container",
					target: 'widget',
					widget: widget,
					plugin: plugin,
				},
				{
					method: 'js',
					selector: "document",
					target: 'widget',
					widget: widget,
					plugin: plugin,
				},
				{
					method: 'prepend',
					selector: "#action-container",
					html: history,
				},
			];
			BlazarDialog.addAjaxCommands(commands);
			BlazarDialog.loadWidget(plugin, widget);
			BlazarDialog.addHistory(commands);
			// add nav controlls
			
		});
		
		
		// ajax button listener
		$(document).on('click', '.blazar-ajax', function(){
			var commands = [
				{
					method: 'replace',
					selector: "#action-container",
					target: 'widget',
					widget: BlazarDialog.data.current_widget.widget,
					plugin: BlazarDialog.data.current_widget.plugin,
				},
			];
			BlazarDialog.addAjaxCommands(commands);
			var element = {
				name : $(this).attr('name'),
				action: $(this).data('action'),
				settings: $(this).data('settings')
			};
			BlazarDialog.ajaxProcessForm(element);
			
		});
		
	});

})(jQuery);