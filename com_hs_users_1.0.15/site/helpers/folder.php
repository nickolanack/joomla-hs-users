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
defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');


/**
 * Contact Component Category Tree
 *
 * @package     Joomla.Site
 * @subpackage  com_contact
 * @since       1.6
 */
class HsuHelperFolder extends JObject
{
	
	static $maxLayer = 3;
	
	
	
	/**
	 * 
	 * 
	 */
	static function getBasePath(){
		return 'images/hsu';
	}
	
	
	
	
	
	static function getFolder(){
		$pathBase = JPATH_ROOT.'/'.self::getBasePath();
		
		if(!is_dir($pathBase)){
			$folderName;
			JFolder::create($pathBase);
			file_put_contents($pathBase . '/' . 'index.html', '<!DOCTYPE html><title></title>');
			for($i=0;$i<self::$maxLayer;$i++){
				$folderName = str_repeat('/00', $i+1);
				JFolder::create($pathBase.$folderName);
				file_put_contents($pathBase.$folderName . '/' . 'index.html', '<!DOCTYPE html><title></title>');
			}
			
			return preg_replace('/^\//', '', $folderName);
		}
		
		
		//self::getLastFolder();
		$layer = 0;
		$layerPath = '';
		
		while($layer<self::$maxLayer){
			$folders = JFolder::folders($pathBase.'/'.$layerPath);
				
					
			//check file count
			$nextFolder = end($folders);
			$subFolders = JFolder::folders($pathBase.'/'.$layerPath.$nextFolder);

			if($layer===self::$maxLayer-1){
				$files = JFolder::files($pathBase.'/'.$layerPath.$nextFolder);
				
				//var_dump($files);
				if(count($files)>100){
					$nextFolder = ((int)$nextFolder)+1;
					
					if($nextFolder<10){
						$nextFolder = '0'.$nextFolder;
					}	
					JFolder::create($pathBase.'/'.$layerPath.$nextFolder);
					file_put_contents($pathBase.'/'.$layerPath.$nextFolder . '/' . 'index.html', '<!DOCTYPE html><title></title>');					
									
				}	
				
				return $layerPath.$nextFolder;
				break;
			}
			
						
			
			if(count($subFolders)>100){
				$nextFolder = ((int)$nextFolder)+1;
				
				if($nextFolder<10){
					$nextFolder = '0'.$nextFolder;
				}
				JFolder::create($pathBase.'/'.$layerPath.$nextFolder);
				file_put_contents($pathBase.'/'.$layerPath.$nextFolder . '/' . 'index.html', '<!DOCTYPE html><title></title>');
				
				$deeperFolderName;
				for($layer=$layer+1;$layer<self::$maxLayer;$layer++){
					$deeperFolderName = str_repeat('/00', $layer+1);
					JFolder::create($pathBase.'/'.$layerPath.$nextFolder.$deeperFolderName);
					file_put_contents($pathBase.'/'.$layerPath.$nextFolder.$deeperFolderName . '/' . 'index.html', '<!DOCTYPE html><title></title>');
					
					return $layerPath.$nextFolder.$deeperFolderName;
					break;
				}				
			}
			
			$layerPath = $layerPath.$nextFolder.'/';
			
			
			$layer++;
		}
		
		
		
		
	}


	static function _getLastFolder($layer){
		
		
	}
}