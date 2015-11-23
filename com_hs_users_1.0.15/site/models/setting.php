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
	jimport('joomla.application.component.modeladmin');
}





/**
 * Profile controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_hs_users
 */
class Hs_usersModelSetting extends JModelAdmin {
	
	protected $acceptableMimes = array('image/jpeg'=>'jpg','image/gif'=>'gif','image/png'=>'png');
	
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState() {
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app -> getParams();
		$this -> setState('params', $params);
		
		//$this->setState('request.data', null);
	}
	
	
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
	public function getTable($name = 'Userextended', $prefix = 'Hs_usersTable', $options = array())
	{
		return parent::getTable($name,$prefix,$options);
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) {

		
		// Get the form.
		$form = $this -> loadForm('com_hs_users.userextended', 'userextended', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}	
	
	
	public function getUserData(){
		$userEx = $this->getState('userex.data', false);
		if($userEx===false){
			$user = JFactory::getUser();
			
			$db = $this->getDbo();
			$query =$db->getQuery(true);
			
			$query->select('id, image_folder, image_name, external, created_at, modified_at, description');
			$query->from('#__users_extended');
			$query->where('user_id='.$db->quote($user->get('id')));
			$db->setQuery($query,0,1);
	
			
			$userEx = $db->loadObject();		
			
			$this->setState('userex.data',$userEx)	;
		}
		
		return $userEx;
	}
	
	
	
	public function updateDesc(){
		//TODO HTML
		$desc = JRequest::getVar('desc');
		
		
		$userEx = $this->getUserData();
		
		$table = $this->getTable();
		
		$d = new stdClass;
		
		if(isset($userEx)){
			$d->id = $userEx->id;
		}
		
		$d->description = $desc;
		
		$table->save($d);
		
		return true;
		
	}
	
	
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 *
	 * @since   12.2
	 */
	protected function loadFormData()
	{
		
		return (array)$this->getUserData();
	}		
	/**
	 * 
	 * Delete File
	 * 
	 * 
	 */
	public function deleteImage(){
		jimport('joomla.filesystem.file');	
		
		$user = JFactory::getUser();
		$db= $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('id, image_folder, image_name, image_raw_name');		
		$query->from('#__users_extended');
		$query->where('user_id='.$db->quote($user->get('id')));
		
		$db->setQuery($query,0,1);
		
		$imgData = $db->loadObject();
		
		if(empty($imgData)){
			$this->setError('COM_HS_USERS_IMAGE_DATA_WAS_NOT_FOUND');
			return;
		}
		
		//delete image file
		jimport('joomla.filesystem.file');	
		require_once JPATH_COMPONENT_SITE.'/helpers/folder.php';
		$basePath = HsuHelperFolder::getBasePath();
		
		if(file_exists(JPATH_ROOT.'/'.$basePath.'/'.$imgData->image_folder.'/'.$imgData->image_name)){
			JFile::delete(JPATH_ROOT.'/'.$basePath.'/'.$imgData->image_folder.'/'.$imgData->image_name);
		}
		
		
		//delete table record
		$table = $this->getTable();
		$d = new stdClass;
		$d->id = $imgData->id;
		$d->image_folder = '';
		$d->image_name = '';
		$d->image_raw_name = '';
		$table->save($d);
		
		
		//var_dump($d);
		return true;
	}	
	
	
	
	/**
	 * 
	 * Upload File
	 * 
	 * 
	 */
	public function uploadImage($fileUrl=null){
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');			
		
		
		if($fileUrl===null){
			//## Image from uploading form
			$uploadedArr = $_FILES['userimage'];
			
			//upload file?
			if(!is_uploaded_file($uploadedArr['tmp_name'])){
				$this->setError('COM_HS_USERS_ERROR_STRANGE_ACCESS');
				return false;
			}	
					
			//check errors
			if($uploadedArr['error']){
				$this->setError('COM_HS_USERS_ERROR_FAILED_TO_UPLOAD');
				return false;			
			}			
			
		}else{
			
			//## download image from social web services
			$image = file_get_contents($fileUrl);
			if($image===false){
				$this->setError('COM_HS_USERS_MODEL_SETING_ERROR_FAILED_TO_GET_IMAGE');
				return false;
			}
			
			$tmp_name = uniqid('st_'.date('YmdHis').'_');
			$tmp_folder = JPATH_ROOT.'/tmp/hsu';
			if(!is_dir($tmp_folder)){
				JFolder::create($tmp_folder);
			}
			$tmp_path = $tmp_folder.'/'.$tmp_name;
			file_put_contents($tmp_path, $image);
			
			$uploadedArr['tmp_name'] = $tmp_path;
			$uploadedArr['name'] = uniqid('ssi_').'.'.JFile::getExt($fileUrl);
		}	


			
		$pathTmpFile = $uploadedArr['tmp_name'];	

		
		
		$content = file_get_contents($pathTmpFile);
		
		
		//security check
		mb_regex_encoding( "ASCII" );
		if (mb_eregi('^.*<\\?php.*$', $content)) {
			$this->setError('STRANGE_FILE');
			return false;
		}		
		
		
		$rawName = JFile::makeSafe($uploadedArr['name']);
		$onlyName = JFile::getName($rawName);
		//$type = $uploadedArr['type'];	
		//$size =	$uploadedArr['size'];	
		
		

		
		
		
		require_once JPATH_COMPONENT_SITE.'/helpers/folder.php';
		require_once JPATH_COMPONENT_SITE.'/helpers/image.php';


		//get image data		
		$imgData = getimagesize($pathTmpFile);
		if($imgData===false){
			$this->setError('COM_HS_USERS_MODEL_SETTING_ERROR_CANNOT_GET_IMAGE_DATA');
			return false;			
		}		
		
		//get image
		$imgResource = imagecreatefromstring(file_get_contents($pathTmpFile));
		if($imgResource===false){
			$this->setError('COM_HS_USERS_MODEL_SETTING_ERROR_FAILED_TO_READ_DATA');
			return false;			
		}		
		

		
		//$this->setAcceptableMimes();
		
		//check extension
		if(!array_key_exists($imgData['mime'], $this->acceptableMimes)){
			$this->setError('COM_HS_USERS_MODEL_SETTING_ERROR_MIME_TYPE_ERROR');
			return false;			
		}
		
		//get extension name
		$extension = $this->acceptableMimes[$imgData['mime']];
		
		//convert original image to png or jpg
		if($extension==='png'){
			$r = imagejpeg($imgResource,$pathTmpFile,100);
		}else{
			$r = imagepng($imgResource,$pathTmpFile,0);
		}
		
		imagedestroy($imgResource);
		
		if($r===false){
			$this->setError('COM_HS_USERS_MODEL_SETTING_ERROR_FAILED_TO_CONVERT_FILE');
			return false;			
		}		


		//reload
		$imgResource = imagecreatefromstring(file_get_contents($pathTmpFile));		
		
		//delete
		JFile::delete($pathTmpFile);

		//add extension name
		switch($extension){
			case 'gif':
				imagegif($imgResource,$pathTmpFile);
				break;
			case 'png':
				imagepng($imgResource,$pathTmpFile,0);
				break;
			case 'jpg':
			default:
				imagejpeg($imgResource,$pathTmpFile,100);
				break;				
		}
		
		imagedestroy($imgResource);
				

		//determines actual folder
		$preData = $this->getUserData();
		
		$basePath = HsuHelperFolder::getBasePath();
		
		if(empty($preData)){			
			$folder = HsuHelperFolder::getFolder();
		}else{
			$folder = $preData->image_folder;
			if(mb_strlen(trim($folder))<1){
				$folder = HsuHelperFolder::getFolder();
			}
		}	
		
				
		
		//thumbnail
		require_once JPATH_COMPONENT.'/asset/phpthumb/ThumbLib.inc.php';
		
		$thumb = phpThumbFactory::create($pathTmpFile);
		
		//FIXME size
		$thumb->adaptiveResize(50, 50);
		
		//$saveName = $this->_getUniqueImageName($basePath.'/'.$folder, $extension);
		
		
		//find not duplicated file name....
		$files = JFolder::files(JPATH_ROOT.'/'.$basePath.'/'.$folder);
		
		
		$notFound=true;
		$c=5;
		$saveName = '';
		while($notFound){
			$saveName = $this->_getRandomString($c).'.'.$extension;
			if(!in_array($saveName, $files)){
				$notFound = false;
				break;
			}
			$saveName = $this->_getRandomString($c).'.'.$extension;
			if(!in_array($saveName, $files)){
				$notFound = false;
				break;
			}			
			$c++;
		}
		
		
		
		$thumb->save(JPATH_ROOT.'/'.$basePath.'/'.$folder.'/'.$saveName);			
		
		
		$length = mb_strlen($onlyName);
		//check name string
		if($length>45){
			$onlyName = mb_substr($onlyName, 0,45);
			$rawName  = $onlyName.'.'.$extension;
		}else if($length<1){
			$rawName  = 'noname.'.$extension;
		}
		
		
				
		$table = $this->getTable();
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		$d = new stdClass;
		
		if(empty($preData)){
			//new
			$d->image_folder = 	$folder;
			$d->image_name = 	$saveName;
			$d->image_raw_name = $rawName;
			$d->created_at = $date->toSql();
			$d->user_id = $user->get('id');
		}else{
			//have a record
			
			//delete previous file
			if(mb_strlen($preData->image_name)>0){
				$dPath = JPATH_ROOT.'/'.$basePath.'/'.$preData->image_folder.'/'.$preData->image_name;
				if(file_exists($dPath)){
					@JFile::delete($dPath);
				}				
			}

			
			
			
			//
			$d->id = 	$preData->id;
			$d->image_folder = 	$folder;
			$d->image_name = 	$saveName;
			$d->image_raw_name = $rawName;
			$d->modified_at = $date->toSql();
		}	
		
		//save data into database		
		$table->save($d);
		
		
		//save table_id to session
		$app = JFactory::getApplication();
		$app->setUserState('com_hs_users.user_image_id', (int)$table->id);		
		
		
		//delete temp
		JFile::delete($pathTmpFile);
		
		
		
	}



	protected function _getUniqueImageName($folder, $extension){
		$files = JFolder::files($folder);

	}
	
	private function _getRandomString($charLength){
		$sCharList = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		mt_srand();
		$sRes = '';
		$max = strlen($sCharList);
		for($i = 0; $i < $charLength; $i++){
			$sRes .= $sCharList[mt_rand(0,  $max - 1)];
		}
		
		return $sRes;	
	}	
	
	
	
	
	
	/**
	 * Get a provider list which is not linked to the user
	 * 
	 * 
	 * 
	 */
	public function getUnconnectedProviders(){
		jimport('hs.user.lib.hybrid_config');
		$providers = HsUserHybridConfig::getActiveProviders();
		
		$user = JFactory::getUser();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('provider');
		$query->from('#__users_authentications');
		$query->where('user_id='.$db->quote($user->get('id')));
		
		$db->setQuery($query);
		
		$r = $db->loadObjectList();
		
		$connectedProviders = array();
		if(isset($r)){
			foreach($r as $row){
				$connectedProviders[] = $row->provider;
			}
		}
		
		$returnArr = array();
		
		foreach($providers as $provider){
			$p = new stdClass;
			$p->name = $provider;
			if(in_array($provider, $connectedProviders)){
				$p->connected = true;
			}else{
				$p->connected = false;
			}
			
			$returnArr[] = $p;
		}
		
		return $returnArr;
	}
}