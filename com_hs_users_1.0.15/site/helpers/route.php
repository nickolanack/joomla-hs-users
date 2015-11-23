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

defined('_JEXEC') or die;

/**
 * Contact Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_contact
 * @since       1.5
 */
class HsuHelperRoute
{
	
	
	static $_db = null;
	
	
	static $_aliasCache = array();
	
	static $_idCache = array();
	
	
	protected static $lookup;
	
	

	
	
	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_hs_users');
			$items		= $menus->getItems('component_id', $component->id);
			//var_dump($items);
			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];
					
					if (!isset(self::$lookup[$view])) {
						self::$lookup[$view] = array();
					}
					
					
					switch($view){
						case 'setting':
							self::$lookup[$view]['top'] = $item->id;
							
							break;
						case 'registration':
							self::$lookup[$view]['top'] = $item->id;
							
							break;		
						case 'login':
							self::$lookup[$view]['top'] = $item->id;
							
							break;													
					}
					
								
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $filters)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach($filters as $filter=>$value){
						if (isset(self::$lookup[$view][$value])) {
							return self::$lookup[$view][$value];
						}						
					}

					
				}
			}
		}
		else
		{
			$active = $menus->getActive();
			if ($active && $active->component == 'com_hs_list') {
				return $active->id;
			}
		}

		return null;
	}	
	
	
	
	static public function getSetting(){
		
		$needles = array('setting'=>array('top'));
		$link = 'index.php?option=com_hs_users&view=setting';
		$itemId = self::_findItem($needles);
		
		if(isset($itemId)){
			$link = 'index.php?Itemid='.$itemId;
		}
		
		return $link;
	}

	static public function getRegistration(){
		
		$needles = array('registration'=>array('top'));
		$link = 'index.php?option=com_hs_users&view=registration';
		$itemId = self::_findItem($needles);
		
		if(isset($itemId)){
			$link = 'index.php?Itemid='.$itemId;
		}
		
		return $link;
	}	
	
	
	
	
	static public function getLogin(){
		
		$needles = array('login'=>array('top'));
		$link = 'index.php?option=com_hs_users&view=login';
		$itemId = self::_findItem($needles);
		
		if(isset($itemId)){
			$link = 'index.php?Itemid='.$itemId;
		}
		
		return $link;
	}		
}
