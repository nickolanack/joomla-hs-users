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

defined('_JEXEC') or die ;

//require_once JPATH_COMPONENT.'/controller.php';


$v = explode('.', JVERSION);
if($v[0]<3){
	jimport('joomla.application.component.controllerform');
}


/**
 * Profile controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_hs_users
 */
class Hs_usersControllerSetting extends JControllerForm {


	protected $linkBase = 'index.php?option=com_hs_users&view=setting';
	
	
	

	

	public function deleteImage(){
		//check token	
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		
		$user = JFactory::getUser();
		
		if($user->guest){
			return;
		}
		
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$ck = $model->deleteImage();
		
		
				
		if($ck===false){
			$error = $model->getError();
			
			$app->redirect(JRoute::_($this->linkBase), JText::_($error), 'warning');
			return false;
		}
		
		
		
		$app->redirect(JRoute::_($this->linkBase));		
				
		
	}
	
		
	public function uploadImage(){
		//check token	
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		
		$user = JFactory::getUser();
		
		if($user->guest){
			return;
		}
		
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$ck = $model->uploadImage();
		
						
		if($ck===false){
			$error = $model->getError();
			
			$app->redirect(JRoute::_($this->linkBase), JText::_($error), 'warning');
			return false;
		}
		
		
		//$app->redirect(JRoute::_($this->linkBase), JText::_('COM_HS_USERS_CONTROLLER_SETTING_IMAGE_WAS_SAVED'));
		$app->redirect(JRoute::_($this->linkBase));
	}
}
