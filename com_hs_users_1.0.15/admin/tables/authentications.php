<?php

/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.2
 * @package     Joomla.admin
 * @subpackage  com_hs_users
 * @copyright   Copyright (C) 2012 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */
 
defined('_JEXEC') or die;

/**
 * Authentications table class
 *
 * @package     Joomla.Administrator
 * @subpackage  com_hs_users
 */
class Hs_usersTableAuthentications extends JTable
{
	/**
	 * Constructor
	 *
	 * @param  JDatabaseDriver  &$db  Database object
	 *
	 * @since  2.5
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__users_authentications', 'id', $db);
	}	
	
	



	public function findByProviderUid( $provider, $provider_uid,$select='id,user_id,provider,email' ){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select($select);
		$query->from($this->getTableName());
		$query->where('provider ='.$db->quote($provider));
		$query->where('provider_uid ='.$db->quote($provider_uid));
		$db->setQuery($query,0,1);
		
		
		return $db->loadObject();		
	}

	
	public function findByUserId( $user_id,$select='id,user_id,provider,email' ){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select($select);
		$query->from($this->getTableName());
		$query->where('user_id ='.$db->quote((int)$user_id));
		$db->setQuery($query,0,1);
		
		
		return $db->loadObject();			
	} 
	
	

}