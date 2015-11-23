<?php
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

defined('_JEXEC') or die ;

class HsuHelperImage{
		
		
	
	protected $acceptableMimes = array('image/jpeg'=>'jpg','image/gif'=>'gif','image/png'=>'png');
	
	
	
	
	private $_loadedLibThumb = false;
	
	
	
	protected function _getThumbObj($path){
		if(empty($this->_loadedLibThumb)){
			require_once JPATH_COMPONENT.'/asset/phpthumb/ThumbLib.inc.php';
			$this->_loadedLibThumb = true;
		}
		
		$signiture = serialize($path);
	
		if(empty($this->_objThumbs[$signiture])){
			$this->_objThumbs[$signiture] = PhpThumbFactory::create($path);
		}
		
		return $this->_objThumbs[$signiture];	
	}
		
	
}
