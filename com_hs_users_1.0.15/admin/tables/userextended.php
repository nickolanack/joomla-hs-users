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
class Hs_usersTableUserextended extends JTable
{
	
	//public $id;
	
	
	//public $user_id;
	
	
	//public $image_folder;
	
	
	/**
	 * Constructor
	 *
	 * @param  JDatabaseDriver  &$db  Database object
	 *
	 * @since  2.5
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__users_extended', 'id', $db);
	}		

}