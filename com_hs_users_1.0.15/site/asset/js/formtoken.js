/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.2
 * @package     Joomla.site	
 * @subpackage  com_hs_users
 * @copyright   Copyright (C) 2013 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */


var HSJS = HSJS||{};
HSJS.libs = HSJS.libs||{};
HSJS.libs.users = HSJS.libs.users||{};

/**
 * 
 * 
 */
HSJS.libs.users.formToken = new Class({
	Implements:[Options,Events],
	options:{
	},
	initialize:function(opt){
		this.setOptions(opt);
		this.els ={
			divToken:$('hs_social_token_form'),
			targets:$$('a.hs_token_target')
		};
		
		//get token
		this.token = this.els.divToken.getLast('input').get('name');
		
		//dispose the token element
		this.els.divToken.dispose();
		
		//add token to each elements
		this.els.targets.each(function(el,i){
			el.set('href', [el.get('href'), [this.token,1].join('=')].join('&'));
		},this);
		
		
	}	
});

//load script
window.addEvent('domready', function(){
	new HSJS.libs.users.formToken();
	
});
