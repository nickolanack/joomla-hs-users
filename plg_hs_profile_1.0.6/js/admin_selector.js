/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.2
 * @package 	pkg_hs_users
 * @subpackage  plg_user_hs_profile
 * @copyright   Copyright (C) 2012 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */

var HSJS = HSJS||{};
HSJS.plugins = HSJS.plugins||{};

HSJS.plugins.profileAdmin = new Class({
	Implements:[Options,Events],
	options:{

	},
	initialize:function(data){
		
		
		//holds social data
		this.socials = data.userData;
		
		//no data
		if(this.socials.length<1){
			return;
		}
		
		this.status ={
			'current':null
		};
		
		
		//get elements
		this.els = {
			insertTarget:$(data.insertTarget)
		};
		
		
		//form elements
		this.els.fields = {};
		data.fields.each(function(name){
			this.els.fields[name] = $([data.formName, data.fieldsetName, name].join('_'));
		},this);
		
		
		
		
		
		
		
		//prepare tabs
		//tab title target
		this.els.tabTitleBase = new Element('ul.hs_socials_list').inject($(data.insertTarget));
		
		//clear
		new Element('div.clear').inject(this.els.tabTitleBase, 'after');
		
		
		this.els.tabTitles = {};
		
		Object.each(this.socials, function(d, provider){
			
		
			
			
			//prepare tab
			this.els.tabTitles[provider] = new Element('li.hs_socials',{
				'text':provider,
				events:{
					'click':function(){
						if(this.status.current === provider){
							return;
						}
						this.changeData(provider);
						this.els.tabTitles[provider].addClass('current');
						
						if(typeOf(this.status.current)!=='null'){
							this.els.tabTitles[this.status.current].removeClass('current');
						}
						this.status.current = provider;
						
					}.bind(this)
				}
			}).inject(this.els.tabTitleBase);


			//load first item
			if(this.status.current === null){
				this.els.tabTitles[provider].addClass('current');
				this.changeData(provider);
				this.status.current = provider;
			}	
						
		},this);
		
		
		
		
	},
	changeData:function(provider){
		var d = this.socials[provider];
		
		Object.each(this.els.fields, function(el,name){
			el.set('value', d[name]);
		},this);
	}	
});