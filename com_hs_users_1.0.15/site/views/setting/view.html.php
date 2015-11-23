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

//require_once JPATH_COMPONENT.'/models/category.php';




/**
 * HTML Contact View class for the Contact component
 *
 * @package     Joomla.Site
 * @subpackage  com_contact
 * @since       1.5
 */
class Hs_usersViewSetting extends JViewLegacy
{

	protected $pathBase = 'components/com_hs_users/views/setting/tmpl/';

	protected $_css = array('setting','setting_css3');

	protected $_js = array('setting');
	
	
	public function display($tpl = null)
	{
		
		$this->_prepareDocument();
		
		//
		
		$user = JFactory::getUser();
		
		if($user->guest){
			$app = JFactory::getApplication();
			$return = base64_encode(JUri::current());
			$app->redirect(JRoute::_('index.php?option=com_users&view=login&return='.$return));
			return false;
		}
		
		$this->providerList = $this->get('UnconnectedProviders');
		
		/*
		$layout = JRequest::getVar('layout');
		switch($layout){
			case 'edit':
				$this->form = $this->get('Form');
				break;
				
			default: 
				$this->data = $this->get('UserData');
				break;
		}
		*/
		
		//$this->data = $this->get('UserData');
		
		
		
		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		//load mootools
		JHtml::_('behavior.framework', true, true);
		
		$doc = JFactory::getDocument();
		
		//add Stylesheets
		foreach($this->_css as $fileName){
			$doc->addStyleSheet($this->pathBase.'css/'.$fileName.'.css');
			
		}
		
		//add JavaScripts
		foreach($this->_js as $fileName){
			$doc->addScript($this->pathBase.'js/'.$fileName.'.js');
		}
		
	}



	/*
	 * Prepare javascript to list items
	 * Pass initial data and add initializing script
	 */
	private function _startItemJs() {
		$doc = JFactory::getDocument();
		$user= JFactory::getUser();
		
		$data = json_encode($this->item);
		
		$data = json_encode(
			array(
			'id'=>(int)$this->item->id,
			'isGuest'=>$user->guest,
			'link'=>JRoute::_('index.php?option=com_hs_list&view=item&dgid='.$this->item->id)
			)
		);
		//$dataImages = json_encode($this->images);
		
		$intScript = <<<EOF
window.addEvent('domready',function(){
	new HSJS.coms.list.item({
		itemData:$data,
		pathToPaypalJS:'',
		options:{}
	});
});
EOF;

		//$doc->addScriptDeclaration($intScript);
		
			
		
	}
}
