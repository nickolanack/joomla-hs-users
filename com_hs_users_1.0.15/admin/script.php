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
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of HS IMAGE SHOW component
 */
class com_hs_usersInstallerScript {

	protected $packages = array();
	protected $sourcedir;
	protected $installerdir;
	protected $manifest;

	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) {

	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) {

	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {
		

	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) {

	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}

}
