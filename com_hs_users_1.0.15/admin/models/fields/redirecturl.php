<?php

/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.2
 * @package     Joomla.admin
 * @subpackage  com_hs_users
 * @copyright   Copyright (C) 2012 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('text');

/**
 * Supports an HTML select list of contacts
 *
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 * @since       1.6
 */
class JFormFieldRedirecturl extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'Redirecturl';	
	
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{		
		JHtml::_('behavior.framework', true, true);
		$doc = JFactory::getDocument();
		$path = 'components/com_hs_users/asset';
		$doc->addScript($path.'/js/hs_users.js');
		$doc->addStyleSheet($path.'/css/hs_users.css');
		
		
		$ids = implode("','",
			array($this->id . '-off', 
				$this->id . '-on'));
		
		$a = <<<EOF
window.addEvent('domready', function(){
	new HSJS.plugin.profile(['$ids']);
});			
EOF;
		
		$doc->addScriptDeclaration($a);
		
		// http://localhost/100jpy/201210/component/hs_users/?task=authentications.endpoint&hauth.done=Google
		$provider = $this->element['provider'];
		
		
		//$redirectUrl = str_replace('/administrator', '', JRoute::_('index.php?option=com_hs_users&task=authentications.endpoint',false, -1));

		//SEF OFF
		$redirectUrl1 = 'http://your_domain_name.com/index.php/component/hs_users/?task=authentications.endpoint';
		
		//SEF ON
		$redirectUrl2 = 'http://your_domain_name.com/component/hs_users/?task=authentications.endpoint';
				
		//var_dump($redirectUrl);
		
		if(isset($provider)){
			$redirectUrl1.= '&hauth.done='.ucfirst($provider);
			$redirectUrl2.= '&hauth.done='.ucfirst($provider);
			//$redirectUrl .= '&hauth.done='.ucfirst($provider);
		}
		
		
		$desc = '';
		if(isset($this->element['explain'])){
			$v = explode('.',JVERSION);
			
			
			if($v[0]<3){
				$desc = '<p class="hs_desc v2">'.JText::_('COM_HS_USERS_REDIRECT_URL_GENERAL_DESC').'</p>';
			}else{
				$desc = '<p class="hs_desc">'.JText::_('COM_HS_USERS_REDIRECT_URL_GENERAL_DESC').'</p>';
			}		
		}		
		
		
		

		return '<label class="redirecturl_label">SEO URL rewriting is OFF</label>'.
			   '<textarea class="redirect_url" readonly="readonly" id="' . $this->id . '-off">'.$redirectUrl1.'</textarea>'.
			   '<label class="redirecturl_label">SEO URL rewriting is ON</label>'.
			   '<textarea class="redirect_url" readonly="readonly" id="' . $this->id . '-on">'.$redirectUrl2.'</textarea>';
		
		
		return '<textarea class="redirect_url" readonly="readonly" id="' . $this->id . '">'.$redirectUrl.'</textarea>'.$desc;
	}	
}