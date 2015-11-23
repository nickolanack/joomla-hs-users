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

if (!JFactory::getUser()->authorise('core.manage', 'com_hs_users'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('Hs_usersHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/hs_users.php');

$controller	= JControllerLegacy::getInstance('Hs_users');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
