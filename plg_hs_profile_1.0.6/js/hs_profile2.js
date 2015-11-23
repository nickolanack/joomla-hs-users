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
	initialize:function(elNames, elValues){
		//var pass1 = document.body.getElement(['input[name=', pass1, ']'].join(''));
		
		
		elNames.each(function(elname, i){
			
			var el = document.body.getElement(['input[name=', elname, ']'].join(''));
			
			
			el.set('value', elValues[i]);
			
			//hide only password
			if(i>1){
				var tr = el.getParent('tr');
				
				if(typeOf(tr)==='element'){
					
					tr.setStyle('display', 'none');
				}else{
					
					
				}				
			}

		});				
	}	
		

});


