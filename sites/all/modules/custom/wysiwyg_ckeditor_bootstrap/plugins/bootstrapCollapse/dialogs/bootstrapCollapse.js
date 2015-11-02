/*
	Plugin	: Bootstrap Collapse
	Author	: Michael Janea (michaeljanea.byethost7.com)
	Version	: 1.0
*/

var bootstrapCollapse_template = ' 	<tbody> 		<tr> 			<td> 				<input type="button" class="bootstrapCollapse_up" value="&and;" onclick="boostrapCollapse_up(this)" /> 				<input type="button" class="bootstrapCollapse_down" value="&or;" onclick="bootstrapCollapse_down(this)" /> 			</td> 			<td><input type="text" class="bootstrapCollapse_title" value="Collapsible Group Item #1" /></td> 			<td><textarea class="bootstrapCollapse_content">Tab 1. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem, magnam, a suscipit quidem totam sunt exercitationem repudiandae hic corrupti aut optio sapiente qui molestias officia quibusdam ducimus cumque eius voluptatibus.</textarea></td> 			<td><input type="button" class="bootstrapCollapse_remove" value="x" onclick="bootstrapCollapse_remove(this)" /></td> 		</tr> 		<tr> 			<td> 				<input type="button" class="bootstrapCollapse_up" value="&and;" onclick="boostrapCollapse_up(this)" /> 				<input type="button" class="bootstrapCollapse_down" value="&or;" onclick="bootstrapCollapse_down(this)" /> 			</td> 			<td><input type="text" class="bootstrapCollapse_title" value="Collapsible Group Item #2" /></td> 			<td><textarea class="bootstrapCollapse_content">Tab 2. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem, magnam, a suscipit quidem totam sunt exercitationem repudiandae hic corrupti aut optio sapiente qui molestias officia quibusdam ducimus cumque eius voluptatibus.</textarea></td> 			<td><input type="button" class="bootstrapCollapse_remove" value="x" onclick="bootstrapCollapse_remove(this)" /></td> 		</tr> 		<tr> 			<td> 				<input type="button" class="bootstrapCollapse_up" value="&and;" onclick="boostrapCollapse_up(this)" /> 				<input type="button" class="bootstrapCollapse_down" value="&or;" onclick="bootstrapCollapse_down(this)" /> 			</td> 			<td><input type="text" class="bootstrapCollapse_title" value="Collapsible Group Item #3" /></td> 			<td><textarea class="bootstrapCollapse_content">Tab 3. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem, magnam, a suscipit quidem totam sunt exercitationem repudiandae hic corrupti aut optio sapiente qui molestias officia quibusdam ducimus cumque eius voluptatibus.</textarea></td> 			<td><input type="button" class="bootstrapCollapse_remove" value="x" onclick="bootstrapCollapse_remove(this)" /></td> 		</tr> 	</tbody> ';
function boostrapCollapse_up(a) {
	var b = bootstrapCollapse_index(a.parentNode.parentNode);
	if (b > 0) {
		var c = document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[b].children[1].firstChild.value;
		var d = document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[parseInt(b) - 1].children[1].firstChild.value;
		document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[parseInt(b) - 1].children[1].firstChild.value = c;
		document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[b].children[1].firstChild.value = d;
		var e = document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[b].children[2].firstChild.value;
		var f = document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[parseInt(b) - 1].children[2].firstChild.value;
		document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[parseInt(b) - 1].children[2].firstChild.value = e;
		document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[b].children[2].firstChild.value = f
	}
	return false
};
function bootstrapCollapse_down(a) {
	var b = bootstrapCollapse_index(a.parentNode.parentNode);
	if (b < document.getElementsByClassName('bootstrapCollapse_title').length - 1) {
		var c = document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[b].children[1].firstChild.value;
		var d = document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[parseInt(b) + 1].children[1].firstChild.value;
		document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[parseInt(b) + 1].children[1].firstChild.value = c;
		document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[b].children[1].firstChild.value = d;
		var e = document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[b].children[2].firstChild.value;
		var f = document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[parseInt(b) + 1].children[2].firstChild.value;
		document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[parseInt(b) + 1].children[2].firstChild.value = e;
		document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[b].children[2].firstChild.value = f
	}
	return false
};
function bootstrapCollapse_addNewItem() {
	var a = document.createElement('tr');
	a.innerHTML = ' 		<td> 			<input type="button" class="bootstrapCollapse_up" value="&and;" onclick="boostrapCollapse_up(this)" /> 			<input type="button" class="bootstrapCollapse_down" value="&or;" onclick="bootstrapCollapse_down(this)" /> 		</td> 		<td><input type="text" class="bootstrapCollapse_title" /></td> 		<td><textarea class="bootstrapCollapse_content"></textarea></td> 		<td><input type="button" class="bootstrapCollapse_remove" value="x" onclick="bootstrapCollapse_remove(this)" /></td> 	';
	document.getElementById('bootstrapCollapse_Container').getElementsByTagName('tbody')[0].appendChild(a);
	return false
};
function bootstrapCollapse_remove(a) {
	if (document.getElementsByClassName('bootstrapCollapse_title').length > 1) {
		if (confirm('Are you sure you want to delete this item?')) {
			a.parentNode.parentNode.parentNode.removeChild(a.parentNode.parentNode)
		}
	} else {
		alert('Bootstrap Collapse must contain at least 1 item!')
	}
	return false
};
function bootstrapCollapse_in_array(a, b) {
	for (var i in b) {
		if (b[i] == a) return true
	}
	return false
};
function bootstrapCollapse_index(a) {
	var b = a.parentNode.childNodes;
	var c = 0;
	for (var i = 0; i < b.length; i++) {
		if (b[i] == a) return c;
		if (b[i].nodeType == 1) c++
	}
	return - 1
};


(function($){

CKEDITOR.dialog.add('bootstrapCollapseDialog', function (editor) {
	return {
		title: 'Bootstrap Collapse',
		minWidth: 600,
		minHeight: 400,
		resizable: false,
		//buttons : [ CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton ],
		onShow: function(){
			var selection = editor.getSelection();
			var element = selection.getStartElement();
			
			
			if (element) {
				element = $(element.$).parents('.panel-group');
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
			} else {
				document.getElementById('bootstrapCollapse').getElementsByTagName('tbody')[0].innerHTML = bootstrapCollapse_template;
			}
			
			
		},
		onOk: function(){
			var accordion_id = Math.round(Math.floor(Math.random()*10120));
			var collapse_html = [];
			var according_html = '<div class="panel-group" id="accordion'+accordion_id+'" role="tablist">';
			// commit the elements
      this.commitContent(collapse_html, accordion_id);
      
      // turn array into string
      for(var i = 0; i < collapse_html.length; i++){
	      according_html += collapse_html[i];
      }
      according_html += "</div>";
      
      if(!this.insertMode){
	      // create element to place new html
	      var abbr = editor.document.createElement( 'div' );
	      
	      // set the html
	      abbr.setHtml(according_html);
	      // insert the html
	      editor.insertElement( abbr );
	    } else {
		    this.element.html(according_html);
	    }
      
		},
		contents: [{
			id: 'BootstrapCollapseTab',
			label: 'BootstrapCollapseTab',
			elements: [{
				type: 'html',
				html: ' 							<div id="bootstrapCollapse"> 								<div id="bootstrapCollapse_Container"> 									<table> 										<thead> 											<tr> 												<th></th> 												<th>Title</th> 												<th>Content (HTML is allowed)</th> 												<th></th> 											</tr> 										</thead> 										' + bootstrapCollapse_template + ' 									</table> 								</div> 								<input type="button" value="+ Add New Item" class="bootstrapCollapse_addNew" onclick="bootstrapCollapse_addNewItem()" /> 							</div> 						',
				commit: function (collapse_html, accordion_id) {
					var b = document.getElementsByClassName('bootstrapCollapse_title');
					var c = document.getElementsByClassName('bootstrapCollapse_content');
					var bb = new Date();
					
					for (var d = 0; d <= b.length; d++) {
						var collapse = d == 0 ? 'collapse in' : 'collapse';
						var default_class = d == 0 ? 'not-collapsed' : 'collapsed';
						var h = bb.getTime();
						var l = Math.round(Math.floor(Math.random()*1010)) + d;
						if (b[d]) {
						var accordion;
							accordion  = '<div class="panel panel-default">';
							accordion += '<div class="panel-heading" role="tab" id="heading' + l + '">';
							accordion += '<h4 class="panel-title">';
							accordion += '<a class="'+default_class+'" data-toggle="collapse" data-parent="#accordion'+accordion_id+'" href="#collapse' + l + '" aria-expanded="false" aria-controls="collapse' + l + '">';
							accordion += b[d].value;
							accordion += '</a>';
							accordion += '</h4>';
							accordion += '</div>';
							accordion += '<div id="collapse' + l + '" class="panel-collapse '+collapse+'" role="tabpanel" aria-labelledby="heading' + l + '">';
							accordion += '<div class="panel-body">';
							accordion += c[d].value;
							accordion += "</div>";
							accordion += "</div>";
							accordion += "</div>";
							
							collapse_html[d] = accordion;

						} else {
							collapse_html[d] = "";
						}
					}
				},
				setup: function (element) {
					if(element){
					var dialog = $('#bootstrapCollapse');
					var accordions = "";
						element.find('.panel-default').each(function(){
							var title = $(this).find("h4 a").text();
							var body = $(this).find(".panel-collapse").text();

							accordions += ' 											<tr> 												<td> 													<input type="button" class="bootstrapCollapse_up" value="&and;" onclick="boostrapCollapse_up(this)" /> 													<input type="button" class="bootstrapCollapse_down" value="&or;" onclick="bootstrapCollapse_down(this)" /> 												</td> 												<td><input type="text" class="bootstrapCollapse_title" value="' + title + '" /></td> 												<td><textarea class="bootstrapCollapse_content">' + body + '</textarea></td> 												<td><input type="button" class="bootstrapCollapse_remove" value="x" onclick="bootstrapCollapse_remove(this)" /></td> 											</tr> 										'
							

						});
					
					
					document.getElementById('bootstrapCollapse').getElementsByTagName('tbody')[0].innerHTML = accordions;
				} else {
					document.getElementById('bootstrapCollapse').getElementsByTagName('tbody')[0].innerHTML = bootstrapCollapse_template;
				}
				
					
				}
			}]
		}]
	}
});

})(jQuery);




