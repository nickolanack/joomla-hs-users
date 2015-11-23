/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.2
 * @package     Joomla.site	
 * @subpackage  com_hs_users
 * @copyright   Copyright (C) 2012 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */

var HSJS = HSJS||{};
HSJS.comUsers = HSJS.comUsers||{};


HSJS.comUsers.setting = new Class({
	Implements:[Options,Events],
	options:{
		request:{
			desc:{
				'option':'com_hs_users',
				'task':'setting.sendDesc',
				'format':'raw'
			}
		}
	},
	initialize:function(opts){
		this.setOptions(opts);
		
		this.els ={
			iptFile:$('hs_userimage'),
			btnChangeImage:$('btn_change_image'),
			hiddenChangeImage:$('hidden_change_image'),
			btnDeleteImage:$('btn_delete_image'),
			formFile:$('userimage_form'),
			descBox:$('hsuser_desc'),
			descHiddenTextArea:new Element('textarea.hs_desc_textarea'),
			descActualText:$('hs_actual_text'),
			descNoText:$('hs_notext')
		};
		
		this.data ={};
		
		this.status = {
			sending:false
			
		};
		
		
		this.token = this.els.formFile.getLast('input').get('name');
		
		this.els.btnChangeImage.addEvents({
			'click':function(){
				if(typeOf(this.els.fakeBg)!=='element'){
					var w = window.getSize();
					this.els.fakeBg = new Element('div.fakebg',{
						styles:{
							'position':'fixed',
							'background-color':'#000000',
							'opacity':0.5,
							'top':0,
							'left':0,
							'z-index':998,
							'width':w.x,
							'height':w.y							
						},
						events:{
							'click':function(){
								this.els.fakeBg.dispose();
								this.els.hiddenChangeImage.setStyles({
									'display':'none'
								});								
							}.bind(this)
						}
					}).inject(document.body);
					var p = this.els.btnChangeImage.getPosition(document.body);
					console.log(p);
					this.els.hiddenChangeImage.setStyles({
						'display':'block',
						'position':'absolute',
						'top':p.y,
						'left':p.x,
						'z-index':999
					});					
					
					this.els.hiddenChangeImage.inject(document.body);
				}else{
					this.els.fakeBg.inject(document.body);
				}
				
				
				
				this.els.hiddenChangeImage.setStyles({
					'display':'block'
				});
			}.bind(this)
		});
		
		
		this.els.iptFile.addEvents({
			'change':function(){
				this.els.formFile.submit();
			}.bind(this)
		});
		
		
		
		
		
		if(typeOf(this.els.btnDeleteImage)==='element'){
			this.els.btnDeleteImage.addEvents({
				'click':function(){
					var c = confirm(this.els.btnDeleteImage.get('title'));
					if(!c){
						return;
					}
					
					$('delete_userimage_form').submit();
					
				}.bind(this)
			});
		}
		
		
		
		//desc
		/* FIXME Disabled description function
		this.descCheckTextExist();
		
		this.els.descHiddenTextArea.inject(this.els.descBox);
		
		this.data.desc = {
			preText:this.els.descActualText.get('text').trim()
		};
		
		this.els.descBox.addEvents({
			'mouseenter':function(){
				var txt = this.els.descActualText.get('text').trim();
				
				this.els.descActualText.setStyles({
					'display':'none'
				});
				this.els.descNoText.setStyles({
					'display':'none'
				});	
							
				this.els.descHiddenTextArea.set('value', txt);
				this.els.descHiddenTextArea.setStyles({
					'display':'block'
				});
			}.bind(this),
			'mouseleave':function(){
				var txt = this.els.descHiddenTextArea.get('value').trim();
				
				//Ajax
				if(this.data.desc.preText!==txt && this.status.sending!==true){
					this.status.sending = true;
					this.descSendData(txt);
				}
				
				//update pre-text
				this.data.desc.preText = txt;
				
				
				//style
				this.els.descActualText.set('text', txt);
				this.els.descActualText.setStyles({
					'display':'block'
				});
							
				
				this.els.descHiddenTextArea.setStyles({
					'display':'none'
				});		
				
				this.descCheckTextExist();		
			}.bind(this)			
		});
		*/
	},
	
	descCheckTextExist:function(){
		var txt = this.els.descActualText.get('text').trim();
		
		if(txt.length<1){
			this.els.descNoText.setStyles({
				'display':'block'
			});
		}else{
			this.els.descNoText.setStyles({
				'display':'none'
			});			
		}		
	},
	
	
	
	descSendData:function(text){
		var d = [];
		
		Object.each(this.options.request.desc, function(v,k){
			d.push([k,v].join('='));
		},this);
		
		d.push([this.token,1].join('='));
		d.push(['desc',encodeURIComponent(text)].join('='));
		
		new Request.JSON({
			url:'index.php',
			data:d.join('&'),
			onError:function(){
				this.status.sending = false;
			}.bind(this),
			onSuccess:function(t){
				this.status.sending = false;
				console.log(t);
				if(t.error){
					alert(t.msg);
					return;
				}
			}.bind(this)
		}).send();
	}
	
	
	
});



/*
 * Activate the class!
 * 
 */
window.addEvent('domready',function(){
	new HSJS.comUsers.setting();
});
