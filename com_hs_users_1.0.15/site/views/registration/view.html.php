<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Registration view class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class Hs_usersViewRegistration extends JViewLegacy
{
	protected $data;

	protected $form;

	protected $params;

	protected $state;

	/**
	 * Method to display the view.
	 *
	 * @param	string	The template file to include
	 * @since	1.6
	 */
	public function display($tpl = null)
	{
		
		//load required files
		JForm::addFormPath(JPATH_SITE.'/components/com_users/models/forms');
		JForm::addFieldPath(JPATH_SITE.'/components/com_users/models/fields');
		require_once JPATH_SITE.'/components/com_users/models/registration.php';
		
		//load com_users language
		$lang = JFactory::getLanguage();
		$lang->load('com_users');
		
		
		$model = JModelLegacy::getInstance('Registration', 'UsersModel');
		$app	= JFactory::getApplication();
		
		// Get the view data.
		$this->data		= $model->getData();
		$this->form		= $model->getForm();
		//$this->state	= $model->get('State');
		$this->params	= $app->getParams('com_hs_users');

				
		// Check for errors.
		if (count($errors = $model->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Check for layout override
		$active = JFactory::getApplication()->getMenu()->getActive();
		if (isset($active->query['layout'])) {
			$this->setLayout($active->query['layout']);
		}

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

		$this->prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document.
	 *
	 * @since	1.6
	 */
	protected function prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$title 		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_USERS_REGISTRATION'));
		}

		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
		
		
		//load asset CSS
		$this->document->addStyleSheet('components/com_hs_users/asset/css/asset.css');
		
	}
}
