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

HSJS.plugins.profile = new Class({
	Implements:[Options,Events],
	options:{
		duration:300,
		language:{
			close:'Close'
		}
	},
	initialize:function(password,pass1,pass2, opts){
		
		[$(pass1),$(pass2)].each(function(ipt){
			//var ipt = $(['pass',n].join(''));
			ipt.set('value', password);
			
			//Only work with default view....
			//joomla 3.0 or later
			var p = ipt.getParent('div.control-group');
			
			if(typeOf(p)==='element'){
				//for joola 3.0
				p.setStyle('display', 'none');
			}else{
				
				//for joomla 2.5
				p=ipt.getParent('dd');
				if(typeOf(p)==='element'){
					var pre = p.getPrevious('dt');
					if(typeOf(pre)==='element'){
						p.setStyle('display', 'none');
						pre.setStyle('display', 'none');
					}					
				}
				
			}
		});		
	}
});


