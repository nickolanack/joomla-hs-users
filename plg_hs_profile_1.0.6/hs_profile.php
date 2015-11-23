<?php
/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.2
 * @package 	pkg_hs_users
 * @subpackage  plg_user_hs_profile
 * @copyright   Copyright (C) 2012 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */
defined('JPATH_BASE') or die ;

/**
 * This plugin is for Social Authorizations
 *
 * @package     Joomla.Plugin
 * @subpackage  User.hs_profile
 */
class plgUserHs_profile extends JPlugin {
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this -> loadLanguage();
		JFormHelper::addFieldPath(__DIR__ . '/fields');
	}

	/**
	 * @param	string	$context	The context for the data
	 * @param	int		$data		The user id
	 * @param	object
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	public function onContentPrepareData($context, $data) {

		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile'))) {
			return true;
		}

		if (!is_object($data)) {
			return true;
		}
		
		if(empty($data -> id)){
			return;
		}
		
		
		$userId = (int)$data -> id;

		if ($userId < 1) {
			return;
		}
		
		//extended data
		$db = JFactory::getDbo();
		$query = $db -> getQuery(true);
		$query -> select('id AS extended_id, image_folder, image_name, image_raw_name, created_at, modified_at, description');

		$query -> from('#__users_extended');
		$query -> where('user_id=' . $db -> quote($userId));

		$db -> setQuery($query, 0, 1);

		$dataExtended = $db -> loadObject();
		
		$data -> hs_extended = $dataExtended;		
		
		
		//pass data to session
		$app = JFactory::getApplication();
		//$app->setUserState('plg_hs_profile.data', array('user_id'=>$userId, 'auth'=>$dataAuth, 'extended'=>$dataExtended));
		$app->setUserState('plg_hs_profile.data', array('user_id'=>$userId,  'extended'=>$dataExtended));
		
		
		return true;
	}

	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @param	array	$user		Holds the user data
	 * @param	array	$options	Array holding options (remember, autoregister, group)
	 *
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	public function onUserLogin($user, $options = array()) {

	}

	/**
	 * @param	JForm	$form	The form to be altered.
	 * @param	array	$data	The associated data for the form.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	public function onContentPrepareForm($form, $data) {

		if (!($form instanceof JForm)) {
			$this -> _subject -> setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Check we are manipulating a valid form.
		$name = $form -> getName();
		if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile', 'com_users.registration'))) {
			return true;
		}
		
		//check there is authentication data
		$app = JFactory::getApplication();
		
		if ($app -> isSite()) {
			$this -> _onContentPrepareFormSite($form, $data);
		} else {
			$this -> _onContentPrepareFormAdmin($form, $data);
		}

		return true;
	}

	private function _onContentPrepareFormSite($form, $data) {
		$app = JFactory::getApplication();
		$data = $app -> getUserState('com_hs_users.registration.data', null);
		
		if (empty($data)) {
			return;
		}
		//JHTML::_('behavior.mootools',true,true);
		JHtml::_('behavior.framework', true, true);
		$doc = JFactory::getDocument();
		
		$version = explode('.',JVERSION);
		if($version[0]>2){
			$doc -> addScript('plugins/user/hs_profile/js/hs_profile3.js');
			
			
			$password = $data -> password;
			$pass1 = $form -> getField('password1') -> id;
			$pass2 = $form -> getField('password2') -> id;
			
			
			$ac = <<<EOF
				window.addEvent('domready', function(){
					new HSJS.plugins.profile('$password', '$pass1','$pass2');
				});			
EOF;
			$doc -> addScriptDeclaration($ac);
		}else{
			$doc -> addScript('plugins/user/hs_profile/js/hs_profile2.js');
			
			$name = $data->display_name;
			$elName = $form -> getField('name') -> name;
			
			$username = $data->display_name;
			$elUsername = $form -> getField('username') -> name;
			
			$password = $data -> password;
			$elPass1 = $form -> getField('password1') -> name;
			$elPass2 = $form -> getField('password2') -> name;
			
			$ac = <<<EOF
				window.addEvent('domready', function(){
					new HSJS.plugins.profile(['$elName', '$elUsername', '$elPass1', '$elPass2'],
											['$name', '$username', '$password','$password']
											);
				});			
EOF;

			
			$doc -> addScriptDeclaration($ac);

		}


	}
	
	
	
	/**
	 * Form for admin
	 * 
	 * 
	 */
	private function _onContentPrepareFormAdmin($form, $data) {
		$app = JFactory::getApplication();
		// Check we are manipulating a valid form.
		$name = $form -> getName();
		if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile', 'com_users.registration'))) {
			return true;
		}
		
		$userId = (int)$data -> id;
		if ($userId < 1) {
			return;
		}
		
		// Add the registration fields to the form.
		JForm::addFormPath(__DIR__ . '/profiles');
		//$form -> loadFile('hs_profile', true);
		$form -> loadFile('hs_socials', true);
		$form -> loadFile('hs_extended', true);
	}
	
	
	
	
	/**
	 * 
	 * 
	 * 
	 */
	public function onUserAfterSave($data, $isNew, $result, $error) {
		$userId = JArrayHelper::getValue($data, 'id', 0, 'int');

		$app = JFactory::getApplication();
		$authData = $app -> getUserState('com_hs_users.registration.data', null);
						
		if(!isset($userId)  || !isset($authData)){
			return true;
		}	
		
		
		//TODO Change stream depending on new user or existing user?
		//if ($isNew) {
			JTable::addIncludePath('administrator/components/com_hs_users/tables');
			$table = JTable::getInstance('Authentications', 'Hs_usersTable');

			$d = new stdClass;
			$d -> id = null;
			$d -> user_id = $userId;
			$d -> provider = $authData -> provider;
			$d -> provider_uid = $authData -> provider_uid;
			$d -> email = $authData -> email;
			$d -> first_name = $authData -> first_name;
			$d -> last_name = $authData -> last_name;
			$d -> display_name = $authData -> display_name;
			$d -> website_url = $authData -> website_url;
			$d -> profile_url = $authData -> profile_url;
			$d -> photo_url = $authData -> photo_url;
			$d -> description = $authData -> description;
			$d -> gender = $authData -> gender;
			$d -> language = $authData -> language;
			$d -> age = $authData -> age;
			$d -> birth_day = $authData -> birth_day;
			$d -> birth_month = $authData -> birth_month;
			$d -> birth_year = $authData -> birth_year;
			$d -> country = $authData -> country;

			$d -> created_at = JFactory::getDate() -> toSql();

			$table -> save($d);
			

			//get table_id and update user_id if it exists
			//$app = JFactory::getApplication();
			$userImageId = $app->getUserState('com_hs_users.user_image_id', 0);				
			if($userImageId>0){
				$tableUserEx = JTable::getInstance('Userextended', 'Hs_usersTable');
				$d = new stdClass;
				$d->id = $userImageId;
				$d->user_id = $userId;
				$tableUserEx->save($d);
			}		
			
			//clear session data
			$app -> setUserState('com_hs_users.registration.data', null);
			$app->setUserState('com_hs_users.user_image_id', null);		
			
			
			//set user id into session to pass the id to controller
			$app->setUserState('com_hs_users.user_id', $userId);
		//}			


		return true;
	}


	/**
	 * Remove all user profile information for the given user ID
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param	array		$user		Holds the user data
	 * @param	boolean		$success	True if user was succesfully stored in the database
	 * @param	string		$msg		Message
	 */
	public function onUserAfterDelete($user, $success, $msg) {
		if (!$success) {
			return false;
		}

		$userId = JArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId) {
			//JTable::addIncludePath('administrator/components/com_hs_users/tables');
			//$table = JTable::getInstance('Authentications', 'Hs_usersTable');

			try {
				$db = JFactory::getDbo();
				
				
				//auth
				$db -> setQuery('DELETE FROM #__users_authentications WHERE user_id = ' . $db -> quote($userId) . "");
				$db -> execute();
				
				//user extended
				jimport('hs.user.user');
				$exUser = HsUser::getInstance($userId);				
				
				
				if(isset($exUser->imagePath)){					
					//
					jimport('joomla.filesystem.file');
					if(file_exists(JPATH_SITE.'/'.$exUser->imagePath)){
						JFile::delete(JPATH_SITE.'/'.$exUser->imagePath);
					}
				}
				
				if(isset($exUser->db_exid)){
					$db -> setQuery('DELETE FROM #__users_extended WHERE id = ' . $db -> quote($exUser->db_exid) . ' AND user_id = ' . $db -> quote($userId));
					$db -> execute();					
				}
				
				
			} catch (Exception $e) {
				$this -> _subject -> setError($e -> getMessage());
				return false;
			}

		}

		return true;
	}
	
}
