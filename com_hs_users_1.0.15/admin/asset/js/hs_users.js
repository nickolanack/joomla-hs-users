
/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.3
 * @package     Joomla.admin
 * @subpackage  com_hs_users
 * @copyright   Copyright (C) 2012 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */
var HSJS = HSJS||{};
HSJS.plugin = HSJS.plugin||{};

/*
 * NOT WORK
 * 
 * 
 */
HSJS.plugin.profile = new Class({
	Implements:[Options,Events],
	options:{

	},
	initialize:function(ts){
		ts.each(function(el,i){
			var t = $(el);
			//var d = t.get('value');
			t.addEvents({
				'click':function(){
					if(typeOf(t.select)=='function'){
						t.select();
					}
				}
			});
		});
	}
});

