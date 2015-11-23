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
HSJS.coms = HSJS.coms||{};
HSJS.coms.users = HSJS.coms.users||{};
/*
 * NOT WORK
 * 
 * 
 */
HSJS.coms.users.openid = new Class({
	Implements:[Options,Events],
	options:{
		language:{
			enterUrlTitle:'Enter your OpenID URL',
			btnSubmit:'Authenticate',
			errorMissedField:'Woops! OpenID is missed!!'
		}
	},
	initialize:function(opt){
		this.setOptions(opt);
		this.els ={
			targets:$$('li.socials.openid')
		};
		
		this.linkBase = null;
		
		
		this.els.targets.each(function(li,i){
			var a = li.getFirst('a');
			a.addEvents({
				'click':function(e){
					e.preventDefault();
					
					this.linkBase = a.get('href');
					this.openBox();
					
					
				}.bind(this)
			})
		},this);
	},
	openBox:function(){
		if(typeOf(this.els.fakebg)!=='element'){
			this.els.actualBox = new Element('div#hsu_actual');
			new Element('h3.hsu_title',{
				'text':this.options.language.enterUrlTitle
			}).inject(this.els.actualBox);
			
			this.els.openid =new Element('input.hsu_openid_url', {
				'type':'text'
			}).inject(this.els.actualBox);
			
			this.els.btn =new Element('input.hsu_openid_url', {
				'type':'submit',
				'value':this.options.language.btnSubmit,
				events:{
					'click':function(){
						var oid = this.els.openid.get('value');
						
						if(oid.length<1){
							alert(this.options.language.errorMissedField);
							return;
						}
						
						location.href = [this.linkBase, '&identifier=',oid].join('');
						
					}.bind(this)
				}
			}).inject(this.els.actualBox);					
			
			
			this.els.fakebg = new Element('div#hsu_fake',{
				styles:{
					'opacity':0.6
				},
				events:{
					'click':function(){
						this.els.fakebg.dispose();
						this.els.actualBox.dispose();
					}.bind(this)
				}
			});		
		}
		
		var w = window.getSize();
		
		this.els.fakebg.setStyles({
			'width':w.x,
			'height':w.y
		});
		
		
		this.els.actualBox.setStyles({
			'left':(w.x - 260 )/2,
			'top':(w.y - 200)/2
		});
		
		this.els.fakebg.inject(document.body);
		
		this.els.actualBox.inject(document.body);
		
	}
	
});



