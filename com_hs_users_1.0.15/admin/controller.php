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
 * Users master display controller.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_hs_users
 */
class Hs_usersController extends JControllerLegacy
{
	
	
	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $default_view ='top';
	
		
	/**
	 * Checks whether a user can see this view.
	 *
	 * @param	string	$view	The view name.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function canView($view)
	{
		$canDo	= Hs_usersHelper::getActions();

		switch ($view)
		{
			// Special permissions.
			case 'top':
				return $canDo->get('core.admin');
				break;

			// Default permissions.
			default:
				return true;
		}
	}

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view   = JRequest::getVar('view', 'top');
		$layout = JRequest::getVar('layout', 'default');
		$id     = JRequest::getVar('id');

		if (!$this->canView($view)) {
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));

			return;
		}


		return parent::display();
	}
}
