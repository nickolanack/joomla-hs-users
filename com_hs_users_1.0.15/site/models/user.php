<?php
/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.15
 * @package     Joomla.site	
 * @subpackage  com_hs_users
 * @copyright   Copyright (C) 2013 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */
defined('_JEXEC') or die ;

//require_once JPATH_COMPONENT.'/controller.php';

/**
 * Profile controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_hs_users
 */
class Hs_usersModelUser extends JModelLegacy {
	
	
	
	public function find_by_email($email){
		$db = $this->getDbo();
		
		$query = $db->getQuery(true);
		$query->select('id,name,username');
		$query->from('#__users');
		$query->where('BINARY email='.$db->quote($email));
		
		$db->setQuery($query,0,1);
		
		$result = $db->loadObject();
		
		if(isset($result)){
			$user_id = (int)$result->id;
			if($user_id>0){
				//the value is used on login
				JFactory::getApplication()->setUserState('com_hs_users.user_id', $user_id);
			}			
		}

		
				
		return $result;
	}
	
	
	public function prepareRegistration($passedData){
//var_dump($passedData);return;		
		$data = array();
		
		if(empty($passedData->name)||empty($passedData->user_name)){
			//new user
			if(mb_strlen($passedData->first_name)>0&&mb_strlen($passedData->last_name)>0){
				$data['name'] = $passedData->last_name.' '.$passedData->first_name;
			}else if(mb_strlen($passedData->first_name)>0&&empty($passedData->last_name)){
				$data['name'] =$passedData->first_name;
			}else{
				$data['name'] =$passedData->display_name;
			}
			
			$data['username'] =$passedData->display_name;			
		}else{
			
			//existing user
			$data['id'] = $passedData->id;
			$data['username'] = $passedData->user_name;
			$data['name'] = $passedData->name;
		}


		$data['email1'] =$passedData->email;
		$data['email2'] =$passedData->email;
		
		//TODO
		$password = $this->_generateRandomString();
		$passedData->password = $password;
		$data['password1'] =$password;
		$data['password2'] =$password;
		
		//save data into juser session
		$app = JFactory::getApplication();
		$app->setUserState('com_users.registration.data', $data);
		$app->setUserState('com_hs_users.registration.data', $passedData);
		
	}
	
	
	/**
	 * Check user name.
	 * Only for new registration user.
	 * 
	 * Changed 2013-12-23 : added a function to replace prohibited words to -
	 * 
	 */
	public function checkUserName(){
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_users.registration.data', null);
		
		
		$passedUserName = $data['username'];
		
		//replaces prohibited words to safe word
		$passedUserName = str_replace(array('<','>','%',';','(',')','&',"'",'"'), '-', $passedUserName);
		
		//NEW USER
		$ck = $this->_checkNotExistUserName($passedUserName);
		
		if($ck===false){
			$ck = false;
			for($i=0;$i<10;$i++){
				$newUsername = $passedUserName.'_'.$this->_generateRandomString(5+$i,false);			
				$ck = $this->_checkNotExistUserName($newUsername);
				if($ck===true){
					break;
				}
				
			}
			
			if($ck===false){
				$this->setError('COM_HS_USERS_ERROR_FAILED_TO_GENERATE_USERNAME');
				return false;
			}
			
			

		}else{
			
			//if the user name is unique
			$newUsername = $passedUserName;
		}			
		
		
		

		$data['username'] = $newUsername;
		$app->setUserState('com_users.registration.data', $data);		
		
		
		return true;
	}
	

	public function getPassword(){
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_users.registration.data', null);
		if(gettype($data)!=='array'){
			$this->setError('COM_HS_USERS_MODEL_USER_CANNOT_FIND_REGISTRATION_DATA');
			return false;	
		}	
		
		if(empty($data['password1'])){
			$this->setError('COM_HS_USERS_MODEL_USER_CANNOT_FIND_PASSWORD_DATA');
			return false;				
		}
		
		return $data['password1'];
	}	
	
	
	/**
	 * get data for com_users profile.save
	 * 
	 * 
	 */
	public function getJProfileData(){
		$app = JFactory::getApplication();
		return $app->getUserState('com_users.registration.data', null);
		
	}
	
	
	public function _checkNotExistUserName($userName){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__users');
		$query->where('username='.$db->quote($userName));
		$db->setQuery($query,0,1);		
		$userId = (int)$db->loadResult();
		
		if($userId>0){
			return false;
		}
		
		return true;
	}
	
	
	
	
	
	
	
	private function _generateRandomString($charLength=15, $symbols=true){
		$sCharList = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		if($symbols){
			$sCharList .= '!"#$%&()="+<>?_-[]';
		}
		
		
		mt_srand();
		$randomString = '';
		$max = strlen($sCharList);
		for($i = 0; $i < $charLength; $i++){
			$randomString .= $sCharList[mt_rand(0,  $max - 1)];
		}
		
		return $randomString;			
	}	
	
	
	
	
	

}