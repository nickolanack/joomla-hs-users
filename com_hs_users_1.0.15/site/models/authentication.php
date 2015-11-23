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

/**
 * Profile controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_hs_users
 */
class Hs_usersModelAuthentication extends JModelLegacy {
	
	

	
	
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   12.2
	 * @throws  Exception
	 */
	public function getTable($name = 'Authentications', $prefix = 'Hs_usersTable', $options = array())
	{
		return parent::getTable($name,$prefix,$options);
	}
	
	
	
	public function find_by_provider_uid($provider, $provider_uid){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('id,user_id,provider,email,display_name,first_name,last_name,profile_url,photo_url,website_url, description');
		$query->select('gender,language,age,birth_day,birth_month,birth_year,country');
		$query->from('#__users_authentications');
		$query->where('provider ='.$db->quote($provider));
		$query->where('provider_uid ='.$db->quote($provider_uid));
		$db->setQuery($query,0,1);		
		
		$r = $db->loadObject();
		
		if(empty($r)){
			return false;
		}
		
		
		$this->setState('authentication.data', $r);
		
		$user_id = (int)$r->user_id;

		
		return $user_id;
		
		//$table = $this->getTable();
		
		//return $table->findByProviderUid($provider,$uid);
	}
	
	public function create($d){
		$table = $this->getTable();
		$table->save($d);
		
	}	
	
	
	public function checkUserProfileIsUpToDate($newData){
		$eData = $this->setState('authentication.data', null);
		if(empty($eData)){
			return false;
		}
		
		$changedData = array();
		
		
		if(isset($newData->email)&&$newData->email!==$eData->email){
			$changedData['email'] = $newData->email;
		}
		
		if(isset($newData->webSiteURL)&&$newData->webSiteURL!==$eData->website_url){
			$changedData['website_url'] = $newData->webSiteURL;
		}
				
		if(isset($newData->photoURL)&&$newData->photoURL!==$eData->photo_url){
			$changedData['photo_url'] = $newData->photoURL;
		}
		
		if(isset($newData->displayName)&&$newData->displayName!==$eData->display_name){
			$changedData['display_name'] = $newData->displayName;
		}

		if(isset($newData->description)&&$newData->description!==$eData->description){
			$changedData['description'] = $newData->description;
		}

		if(isset($newData->firstName)&&$newData->firstName!==$eData->first_name){
			$changedData['first_name'] = $newData->firstName;
		}		
		if(isset($newData->lastName)&&$newData->lastName!==$eData->last_name){
			$changedData['last_name'] = $newData->lastName;
		}			
		if(isset($newData->gender)&&$newData->gender!==$eData->gender){
			$changedData['gender'] = $newData->gender;
		}	
		if(isset($newData->language)&&$newData->language!==$eData->language){
			$changedData['language'] = $newData->language;
		}	
		if(isset($newData->age)&&$newData->age!==$eData->age){
			$changedData['age'] = $newData->age;
		}			
		if(isset($newData->birthDay)&&$newData->birthDay!==(int)$eData->birth_day){
			$changedData['birth_day'] = $newData->birthDay;
		}			
		if(isset($newData->birthMonth)&&$newData->birthMonth!==(int)$eData->birth_month){
			$changedData['birth_month'] = $newData->birthMonth;
		}			
		if(isset($newData->birthYear)&&$newData->birthYear!==(int)$eData->birth_year){
			$changedData['birth_year'] = $newData->birthYear;
		}			
		
		
		if(isset($newData->country)&&$newData->country!==$eData->country){
			$changedData['country'] = $newData->country;
		}			
		
		
		//var_dump($changedData);
		
		if(count($changedData)>0){
			$table=$this->getTable();
			$changedData['id'] = $eData->id;
			$changedData['modified_at'] = JFactory::getDate()->toSql();
			$r=$table->save($changedData);
		}
		
	}


	public function login($user_id,$options=array()){
		
		if($user_id<1){
			return;
		}
		
		//TODO Is this correct?
		$instance = JUser::getInstance($user_id);
		
		
		if($instance->getError()){
			JError::raiseWarning('SOME_ERROR_CODE', $instance->getError());
			return false;			
		}
		
		// If the user is blocked, redirect with an error
		if ($instance->get('block') == 1) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('JERROR_NOLOGIN_BLOCKED'));
			return false;
		}

		// Authorise the user based on the group information
		if (!isset($options['group'])) {
			$options['group'] = 'USERS';
		}

		//FIXME Check the user can login.
		/*
		$result	= $instance->authorise($options['action']);
		if (!$result) {

			JError::raiseWarning(401, JText::_('JERROR_LOGIN_DENIED'));
			return false;
		}
		*/
		
		// Mark the user as logged in
		$instance->set('guest', 0);

		// Register the needed session variables
		$session = JFactory::getSession();
		$ck = $session->set('user', $instance);
		
		if($ck===false){
			$this->setError('ERRORRRRRRRRRR');
			return false;
		}


		$db = JFactory::getDBO();

		// Check to see the the session already exists.
		$app = JFactory::getApplication();
		$app->checkSession();

		// Update the user related fields for the Joomla sessions table.
		$db->setQuery(
			'UPDATE '.$db->quoteName('#__session') .
			' SET '.$db->quoteName('guest').' = '.$db->quote($instance->get('guest')).',' .
			'	'.$db->quoteName('username').' = '.$db->quote($instance->get('username')).',' .
			'	'.$db->quoteName('userid').' = '.(int) $instance->get('id') .
			' WHERE '.$db->quoteName('session_id').' = '.$db->quote($session->getId())
		);
		$db->execute();		
	}




	public function addNewAuthoricationToPreExistUser($d){
		$table = $this->getTable();
		$user = JFactory::getUser();
		
		$d->user_id = $user->get('id');
		$table->save($d);
		
		
	}
}