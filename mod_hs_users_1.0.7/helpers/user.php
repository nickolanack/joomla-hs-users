<?php
/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.2
 * @subpackage  mod_hs_users
 * @copyright   Copyright (C) 2012 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */

defined('_JEXEC') or die;

//require_once JPATH_SITE.'/components/com_hs_users/helpers/config.php';


/**
 * Users Route Helper
 *
 * @package     Joomla.Site
 * @subpackage  com_hs_users
 * @since       1.6
 */
class hs_mod_usersHelperUser
{
	
	static $_db = null;
	
	
	static $userImages = array();
	
	
	static $userImageUrls = array();
	
	
	static function getUserImageUrl($userId=null){
		if($userId===null){
			$user = JFactory::getUser();
			
			if($user->guest){
				return false;
			}
			$userId = $user->get('id');
		}
		
		if(empty(self::$userImageUrls[$userId])&&self::$userImageUrls[$userId]!==false){

			$db = self::_getDbo();
			$query = $db->getQuery(true);
			$query->select('photo_url');
			$query->from('#__users_authentications');
			$query->where('user_id='.$db->quote($userId));
			
			$db->setQuery($query,0,1);
			
			$r = $db->loadObject();
			
			if(empty($r)){
				self::$userImageUrls[$userId] = false;
				return false;
			}
			
			
			if(empty($r->photo_url)||mb_strlen($r->photo_url)<1){
				self::$userImageUrls[$userId] = false;
				return false;				
			}
			
			self::$userImageUrls[$userId] = $r->photo_url;
			
			
		}
		
		return self::$userImageUrls[$userId];
	}
	
	
	static function getUserImage($userId=null,$width=30,$height=30){
		if($userId===null){
			$user = JFactory::getUser();
			
			if($user->guest){
				return false;
			}
			$userId = $user->get('id');
		}
		
		$user_url = self::getUserImageUrl($userId);
		
		if(empty($user_url)){
			$user_url = 'modules/mod_hs_login/images/noimage2.png';
		}
		
		return '<img src="'.$user_url.'" alt="User Image" width="'.$width.'" height="'.$height.'" />'; 
	}
	
		
	static function getUserNoImage(){
		return false;
	}
	
	
	static function _getDbo(){
		if(self::$_db===null){
			self::$_db=JFactory::getDbo();
		}
		return self::$_db;
	}
}