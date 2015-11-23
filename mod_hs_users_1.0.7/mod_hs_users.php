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
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


// Include the login functions only once
require_once JPATH_SITE . '/modules/mod_hs_users/helper.php';

$user	= JFactory::getUser();
$layout = $params->get('layout', 'default');
$type	= modHsuserHelper::getType();
$return	= modHsuserHelper::getReturnURL($params, $type);

// Logged users must load the logout sublayout
if (!$user->guest)
{
	$layout .= '_logout';
}
$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/mod_hs_users/tmpl/css/mod_hs_users.css');

require JModuleHelper::getLayoutPath('mod_hs_users', $layout);
