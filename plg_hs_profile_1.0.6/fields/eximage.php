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
/**
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of contacts
 *
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 * @since       1.6
 */
class JFormFieldEximage extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Ordering';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$html = array();
		
		$app = JFactory::getApplication();
		$data = $app->getUserState('plg_hs_profile.data',array());
		if(empty($data['user_id'])){
			
			return JText::_('PLG_USER_HS_PROFILE_NO_USER_IMAGE');
		}
		
		
		
		
		jimport('hs.user.user');
		$exUser = HsUser::getInstance($data['user_id']);
		
		//$img = $exUser->getImageTag(50,50,false);
		if(isset($exUser->imagePath)){
			$html[] = '<div class="hs_user_image"><img src="../'.$exUser->imagePath.'" alt="User Image" /></div>';
			$html[] = '<div class="hs_user_image_infp">Path: '.$exUser->imagePath.'</div>';
			
		}else{
			$html[] = JText::_('PLG_USER_HS_PROFILE_NO_USER_IMAGE');
		}
		
		
		return implode($html);
	}
}
