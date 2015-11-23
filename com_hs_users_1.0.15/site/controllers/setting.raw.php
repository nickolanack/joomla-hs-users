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


	public function sendDesc(){
		
		
		
		
		//FIXME Disabled
		return;
		
		
		
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$user = JFactory::getUser();
		if($user->guest){
			
			$this->setError(JText::_('COM_HS_USERS_CONTROLLER_SETTING_ERROR_LOGIN'));
			$this->_echoError();
			return false;
		}
		
		
		$model = $this->getModel();
		$model->updateDesc();
		
		
		echo json_encode(array('error'=>false, 'msg'=>JText::_('COM_HS_USERS_CONTROLLER_SETTING_DESC_IS_SAVED')));
	}
	
	
	private function _echoError(){
		$msg = $this->getError();
		
		if(empty($msg)){
			$msg = JText::_('COM_HS_USERS_CONTROLLER_SETTING_ERROR');
		}
		
		echo json_encode(array('error'=>true, 'msg'=>$msg));
	}
}
