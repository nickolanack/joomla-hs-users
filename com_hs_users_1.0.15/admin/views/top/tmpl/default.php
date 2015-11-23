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

defined('_JEXEC') or die;



?>
<h3>Redirect URL</h3>
<p>
	<?php 
	echo str_replace('/administrator', '', JRoute::_('index.php?option=com_hs_users&task=authentications.endpoint',true, -1));
		//FIXME administrator
		//echo JUri::base().'index.php?option=com_hs_users&task=authentications.endpoint';
	?>
</p>