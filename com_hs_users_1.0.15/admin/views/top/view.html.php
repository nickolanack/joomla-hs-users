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
 * View class for a list of users.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_hs_users
 */
class Hs_usersViewTop extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		Hs_usersHelper::addSubmenu('top');
		
		$this->addToolbar();
		
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$canDo	= Hs_usersHelper::getActions();

		JToolbarHelper::title(JText::_('COM_USERS_VIEW_HS_USERS_TITLE'), 'user');

		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_hs_users');
			JToolbarHelper::divider();
		}
	}
}
