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
class JFormFieldSelector extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Selector';

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

		$userData = $app->getUserState('plg_hs_profile.data', null);
		
		if(empty($userData)){
			return;
		}
		
		$userId = (int)$userData['user_id'];
		
		if($userId<1){
			return;
		}
		
		
		$doc = JFactory::getDocument();
		$pathBase = '../plugins/user/hs_profile';
		$doc->addScript($pathBase.'/js/admin_selector.js');
		$doc->addStyleSheet($pathBase.'/css/admin.css');
		$doc->addStyleSheet($pathBase.'/css/admin_css3.css');
		
		//sync to hs_socials profile
		$fields = array('id', 'profile_url','provider', 'provider_uid', 'email', 'display_name', 'first_name', 
			'last_name', 'profile_url', 'photo_url', 'website_url', 'description', 
			'gender', 'language', 'age', 'birth_day', 'birth_month', 'birth_year', 'country', 'created_at');
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(implode(',', $fields));
		$query->from('#__users_authentications');
		$query->where('user_id = '.$db->quote($userId));
		$db->setQuery($query);
		
		$r = $db->loadObjectList();
		
		
		$reordered = array();
		
		if(isset($r)){
			foreach($r as $row){
				$reordered[$row->provider] = $row;
			}
		}
		
		
		
		$d = new stdClass;
		$d->userData = $reordered;
		$d->formName = $this->formControl;
		$d->fieldsetName = $this->group;
		$d->fields = $fields;
		$d->insertTarget = $this->id;
		
		$p = json_encode($d);
		
		$act = <<<EOF
window.addEvent('domready',function(){
	new HSJS.plugins.profileAdmin($p);
});	
				
EOF;
		
		$doc->addScriptDeclaration($act);
		
/*		
		//jform
		var_dump($this->formControl);
		
		//hs_socilas
		var_dump($this->group);
		
		//selector
		var_dump($this->fieldname);		
*/		
		
		
		$html[] = '<div id="'.$this->id.'"></div>';
		
		
		return implode($html);
	}
}
