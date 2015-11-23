<?php
/**
 * 
 * The author is Hayato Sugimoto. https://hs-shelf.com
 * 
 * @version		1.0.2
 * @subpackage  mod_hs_users
 * @copyright   Copyright (C) 2012 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die ;

JHtml::_('behavior.keepalive');

jimport('hs.user.user');
$userEx = HsUser::getInstance();		
?>
<form action="<?php echo JRoute::_('index.php', true, $params -> get('usesecure')); ?>" method="post" id="login-form" class="form-vertical">

	<?php if ($params->get('user_photo', 1)) :?>
	<?php
	
	?>
	<?php if(isset($userEx->image)):?>
	<div class="hs_user_photo_box">
		<div class="inner">
			<?php echo $userEx->image; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?>

	<div class="username_box">
		<div class="inner">
			<span class="user_name">
				<?php
				if ($params -> get('name') == 0) : {
						echo htmlspecialchars($user -> get('name'));
					}
				else : {
						echo htmlspecialchars($user -> get('username'));
					}
				endif;
				?></span>
			
		</div>

	</div>
	<div class="clear"></div>

	<div class="logout-button">
		<input type="submit" name="Submit" class="btn btn-primary logout" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<!-- <input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGOUT'); ?>" /> -->
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
