(function ($) {
/*
  Drupal.behaviors.bt_dynoblock = {
    attach: function (context, settings) {
*/
$(document).ready(function(){
			
			
			var sortHoverTimer,
		    actionsHoverTimer
		  
			/*
  		 * Dynamic blocks controller
  		 */
			var DynoBlocks = {
  			
  			regions: [],
  			
  			init: function(){
    			this.regions = [];
    			this.loadRegions();
  			},
  			
  			loadRegions: function(){
    		  var _this = this;
    		  $('.dynoblock-region').each(function(){
      		  var region = new DynoRegion($(this));
      		  _this.regions.push(region);
      		  region.init();
    		  });
    		  return this;
  			},
  			
  			getRegion: function(rid){
    			for(var rrid in this.regions){
      			if(this.regions[rrid].rid == rid){
      			  return this.regions[rrid];
      			 }
    			}
  			},
  			
  			getBlock: function(rid, bid){
    			for(var rrid in this.regions){
      			if(this.regions[rrid].rid == rid){
      			  for(var bbid in this.regions[rrid].blocks){
        			  if(this.regions[rrid].blocks[bbid].bid == bid){
          			  return this.regions[rrid].blocks[bbid];
        			  }
      			  }
      		  }
    			}
  			},
  			
  			getSelector: function(callback){
    			this.getData('/dynoblock/selector', function(data){
      			callback(data);
    			});
  			},
  			
  			getLayout: function(layout, callback){
          this.getData('/dynoblock/generate/' + layout, function(data){
      			callback(data);
    			});
  			},
  			
  			getData: function(url, callback){
    			$.get(url).done(function(data) {
            if(callback){
              callback(data);
            }
          });
  			},
  			
  			postData: function(url, data, callback){
    			$.post(url, data).done(function(data) {
            if(callback){
              callback(JSON.parse(data));
            }
          }, 'json');
  			},
  			
  			saveBlock: function(form_state, method, callback){
    			this.postData('/dynoblock/save/' + method, form_state, function(data){
      			if(callback){
        			callback(data);
      			}
    			});
  			},
  			
  			editBlock: function(rid, bid, callback){
    			this.getData('/dynoblock/edit/' + rid + '/' + bid, function(data){
      			if(callback){
        			callback(data);
      			}
    			});
  			},
  			
  			removeBlock: function(rid, bid, callback){
    			this.postData('/dynoblock/remove/' + rid + '/' + bid, [], function(data){
      			if(callback){
        			callback(data);
      			}
    			});
  			},
  			
  			updateWeight: function(rid, bid, data, callback){
    			this.postData('/dynoblock/update/' + rid + '/' + bid, data, function(data){
      			if(callback){
        			callback(data);
      			}
    			});
  			},
  			
			}
			
			/*
  		 * Dynamic region object/class controller
  		 */
			function DynoRegion(region){
  			this.blocks = [];
  			this.region = region;
  			this.rid = region.data('dyno-rid');
  			
  			this.init = function(){
    			this.loadDynoBlocks();
  			}
  			
  			this.loadDynoBlocks = function(){
    			var _this = this;
      		this.region.children('.dynoblock').each(function(){
            _this.addBlock($(this), $(this).data('dyno-bid'), $(this).data('dyno-rid'), $(this).data('dyno-handler'));
      		});
        	this.showAddBlock();
      		return this.dynoblocks;
  			}
  			
  			this.showAddBlock = function(){
    			var _this = this;
    			this.removeAddBlock();
          this.blockAddElement(function(selector){
            _this.region.append(selector);
          });
  			}
  			
  			this.removeAddBlock = function(){
    			this.region.children('.dyno-add-block').remove();
  			}
  			
  			this.renderForm = function(dyno_form, form_id){
    			this.removeAddBlock();
    			var bid = this.generateBlockId();
    			var form = $(this.blockForm(dyno_form, form_id, bid));
    			this.region.append(form);
  			}
  			
  			this.blockForm = function(dyno_form, form_id, bid, type){
    			type = !type ? 'save' : type;
    			var form = '<form id="'+ this.rid +'_form" class="dynoblock-form" data-dyno-rid="'+ this.rid +'" data-dyno-bid="'+ bid +'">';
    			form += '<div class="dynoblock-form-container">';
    			form += dyno_form;
    			form += '<input type="hidden" name="rid" value="'+ this.rid +'"/>';
    			form += '<input type="hidden" name="bid" value="'+ bid +'"/>';
    			form += '<input type="hidden" name="form_id" value="'+ form_id +'"/>';
    			form += '<div class="btn-toolbar dynoblock-form-actions">';
    			form += '<button type="button" class="btn btn-success dyno-'+type+'">Save</button>';
    			form += '<button type="button" data-dyno-for="'+type+'" class="btn btn-danger dyno-cancel">Cancel</button>';
    			form += '</div>';
    			form += '</div>';
    			form += '</form>';
    			return form;
  			}
  			
  			
  			this.editBlock = function(rid, bid, callback){
    			var _this = this;
    			var block = this.findBlock(bid);
    			if(block){
      			DynoBlocks.editBlock(rid, bid, function(result){
        			if(result){
          			_this.removeAddBlock();
          			var form = _this.blockForm(result, block.handler, bid, 'edit');
          			block.old_html = block.element.html();
          			_this.sortSupport(true);
          			block.element.html($(form));
          			if(callback){
            			callback(result);
          			}
          		}
      			});
    			}
  			}
  			
  			this.cancelEdit = function(bid, method){
    			this.showAddBlock();
    			var block = this.findBlock(bid);
          if(block){
            if(method == 'edit'){
              block.element.html(block.old_html);
            }
          } else {
            this.removeBlockForm();
          }
  			}
  			
  			this.addBlock = function(block, bid, rid, handler){
    			this.blocks.push(new DynoBlock(block, bid, rid, handler));
  			}
  			
  			this.removeBlock = function(bid){
    		  var block = this.findBlock(bid);
    		  if(bid){
      		  // # TODO remove block from this classes this.blocks object
            DynoBlocks.removeBlock(block.rid, block.bid, function(result){
              if(result.removed){
                console.log('block removed');
                block.element.remove();
              }
            });
    		  }
  			}
  			
  			this.showBlock = function(bid, replace, new_html){
    			var _this = this;
    			var block = this.findBlock(bid);
    			if(block){
      			this.removeBlockForm();
      			if(replace && new_html){
        			block.element.html(new_html);
        		} else {
              this.region.append(block.element);
            }
    			}
  			}
  			
  			this.removeBlockForm = function(){
    			$('#' + this.rid + '_form').remove();
  			}
  			
  			this.findBlock = function(bid){
    			for(var bbid in this.blocks){
      			if(this.blocks[bbid].bid == bid){
      			  return this.blocks[bbid];
      		  }
    			}
    		}
  			
  			this.saveBlock = function(form_state, method, callback){
    			var _this = this;
    			DynoBlocks.saveBlock(form_state, method, function(result){
      			if(result.saved){
        			console.log('block saved');
        			var block = result.block;
          		if(method != 'edit'){
        			  _this.addBlock($(block), result.bid, result.rid, result.handler);
        		  }
        		  _this.showAddBlock();
        			if(callback){
          			callback(result.block);
        			}
      			}
    			});
  			}
  			
  			this.blockAddElement = function(callback){
    			var _this = this;
    			DynoBlocks.getSelector(function(selector){
    			  var add = '<div class="dyno-add-block" data-dyno-rid="'+_this.rid+'">';
    			  add += selector;
    			  add += '</div>';
    			  callback(add);
    		  });
  			}
  			
  			this.generateBlockId = function(){
    			return Math.floor(Date.now() / 1000);
  			}
  			
  			this.sortSupport = function(action){
    			var _this = this;
    			if(!action){
      			if(!this.region.find('.dynoblock-form').get(0)){
        			for(var bid in this.blocks){
          			var element = this.blocks[bid].element;
          			if(!element.find('.dyno-drag-element').get(0)){
          		    element.prepend('<a href="#" class="tabledrag-handle tabledrag-handle-hover dyno-drag-element" title="Drag to re-order"><div class="handle">&nbsp;</div></a>');
          		  }
        			}
        			// sortable regions
              this.region.addClass('dyno-sortable active').sortable({
                stop: function(event, ui){
                  var i = 0;
                  _this.region.find('.dynoblock').each(function(){
                    var bid = $(this).data('dyno-bid');
                    DynoBlocks.updateWeight(_this.rid, bid, {"weight" : i}, function(result){
                      console.log(result);
                    });
                    i++;
                  });
                },
              });
              this.region.disableSelection();
            }
      		} else {
        		if(this.region.hasClass('dyno-sortable')){
        		  this.region.removeClass('dyno-sortable active').find('.dyno-drag-element').remove();
              this.region.sortable('destroy');
            }
      		}
  			}
  			
			}
			
			/*
  		 * Dynamic block object/class
  		 */
			function DynoBlock(block, bid, rid, handler){
  			this.element = block;
  			this.bid = bid;
  			this.handler = handler;
  			this.rid = rid;
  			this.init = function(){}
  			this.old_html = null;
  			
  			this.showActions = function(){
    			this.element.append(this.actionForm());
  			}
  			
  			this.removeActions = function(){
    			this.element.find('.dynoblock-actions').remove();
  			}
  			
  			this.actionForm = function(){
    			var actions = '<div class="dynoblock-actions" data-dyno-rid="'+ this.rid +'" data-dyno-bid="'+ this.bid +'">';
    			actions += '<div class="dynoblock-actions-inner">';
    			actions += '<div class="btn-toolbar">';
    			actions += '<button type="button" class="btn btn-primary dynoblock-edit"><span class="glyphicon glyphicon-pencil">Edit</span></button>';
    			actions += '<button type="button" class="btn btn-danger dynoblock-remove"><span class="glyphicon glyphicon-remove">Remove</span></button>';
    			actions += '</div>';
    			actions += '</div>';
    			actions += '</div>';
    			return $(actions);
  			}
  			
			}
			
			// run dyno blocks :)
			DynoBlocks.init();
			
			
			function get_dyno_block_on_hover(element){
  			var rid = element.data('dyno-rid');
    		var bid =  element.data('dyno-bid');
    		var block = DynoBlocks.getBlock(element.data('dyno-rid'), element.data('dyno-bid'));
    		return block;
			}
			
			function get_dyno_region_on_hover(element){
  			var rid = element.data('dyno-rid');
    		return DynoBlocks.getRegion(rid);
			}
			
			$(document).on('mouseenter mouseleave', '.dynoblock-region', function(e){
    		var action = (e.type == 'mouseenter') ? false : true;
    		var timeout = (e.type == 'mouseenter') ? 500 : 500;
    		var region = get_dyno_region_on_hover($(this));
    		clearTimeout(sortHoverTimer);
    		sortHoverTimer = setTimeout(function(){
          region.sortSupport(action);
        }, timeout);
  		});
  		
  		$(document).on('mouseenter', '.dynoblock', function(){
    		var _this = $(this);
    		actionsHoverTimer = setTimeout(function(){
      		_this.addClass('active');
      		var block = get_dyno_block_on_hover(_this);
      		if(block){
        		block.showActions();
      		}
        }, 500);
  		});
  		
  		$(document).on('mouseleave', '.dynoblock', function(){
    		var _this = $(this);
    		clearTimeout(actionsHoverTimer);
    		setTimeout(function(){
      		_this.removeClass('active');
      		var block = get_dyno_block_on_hover(_this);
      		if(block){
        		block.removeActions();
      		}
        }, 500);
  		});
  		
  		$(document).on('click', '.dynoblock-remove', function(){
    		var rid = $(this).closest('.dynoblock-actions').data('dyno-rid');
  			var bid = $(this).closest('.dynoblock-actions').data('dyno-bid');
  			region = DynoBlocks.getRegion(rid);
  			if(region){
    			region.removeBlock(bid);
  			}
  		});
  		
  		$(document).on('click', '.dynoblock-edit', function(){
    		var rid = $(this).closest('.dynoblock-actions').data('dyno-rid');
  			var bid = $(this).closest('.dynoblock-actions').data('dyno-bid');
  			region = DynoBlocks.getRegion(rid);
  			if(region){
    			region.editBlock(rid, bid);
  			}
  		});
			
			$(document).on('click', '.dynoblock-layout', function(){
  			var form_id = $(this).data('dyno-lid');
  			var rid = $(this).closest('.dyno-add-block').data('dyno-rid');
  			var region = DynoBlocks.getRegion(rid);
  			if(region){
    			DynoBlocks.getLayout(form_id, function(form){
      			if(form){
        			region.renderForm(form, form_id);
      			}
    			});
  			}
  		});
  		
  		$(document).on('mouseenter mouseover', '.dyno-add-block', function(){
  			clearTimeout(sortHoverTimer);
  		});
  		
  		$(document).on('click', '.dyno-save', function(){
    		var form = $(this).closest('.dynoblock-form');
  			var rid = form.data('dyno-rid');
  			var bid = form.data('dyno-bid');
  			var form_state = $('#' + rid + '_form').serialize();
  			var region = DynoBlocks.getRegion(rid);
  			if(region){
    			region.saveBlock(form_state, 'new', function(block){
      			region.showBlock(bid);
    			});
  			}
  		});
  		
  		
  		$(document).on('click', '.dyno-edit', function(){
    		var form = $(this).closest('.dynoblock-form');
  			var rid = form.data('dyno-rid');
  			var bid = form.data('dyno-bid');
  			var form_state = $('#' + rid + '_form').serialize();
  			var region = DynoBlocks.getRegion(rid);
  			if(region){
    			region.saveBlock(form_state, 'edit', function(block){
      			console.log(block);
      			region.showBlock(bid, true, block);
    			});
  			}
  		});
  		
  		$(document).on('click', '.dyno-cancel', function(){
    		var form = $(this).closest('.dynoblock-form');
  			var rid = form.data('dyno-rid');
  			var bid = form.data('dyno-bid');
  			var method = $(this).data('dyno-for');
  			var region = DynoBlocks.getRegion(rid);
  			if(region){
          region.cancelEdit(bid, method);
  			}
  		});
  		
  		$(document).on('click', '.dyno-add-block', function(e){
    		e.preventDefault();
      });
			
/*
    }
  };
*/

});

})(jQuery);