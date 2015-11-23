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
 * @subpackage  MOD_HS_USERS
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
//JHtml::_('bootstrap.tooltip');

$usersConfig = JComponentHelper::getParams('com_users');

//load default login module language
JFactory::getLanguage()->load('mod_login');

?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-inline">
	<?php if ($params->get('pretext')): ?>
		<div class="pretext">
		<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	<div class="userdata">
		
		<?php //########## Default Login Form #################?>
		<?php $hideDefault = (int)$params->get('hide_default_form', 1);?>
		<?php if($hideDefault>0):?>
		<div id="form-login-username" class="control-group">
			<div class="controls">
				<div class="input-prepend input-append">
					<span class="add-on">
						<i class="icon-user tip" title="<?php echo JText::_('MOD_HS_USERS_VALUE_USERNAME') ?>"></i>
						<label for="modlgn-username" class="element-invisible"><?php echo JText::_('MOD_HS_USERS_VALUE_USERNAME'); ?></label>
					</span>
					<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="1" size="18" placeholder="<?php echo JText::_('MOD_HS_USERS_VALUE_USERNAME') ?>" />
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>" class="btn hasTooltip" title="<?php echo JText::_('MOD_HS_USERS_FORGOT_YOUR_USERNAME'); ?>">
						<i class="icon-question-sign"></i>
					</a>
				</div>
			</div>
		</div>
		<div id="form-login-password" class="control-group">
			<div class="controls">
				<div class="input-prepend input-append">
					<span class="add-on">
						<i class="icon-lock tip" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>"></i>
						<label for="modlgn-passwd" class="element-invisible"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
					</span>
					<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="2" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>" class="btn hasTooltip" title="<?php echo JText::_('MOD_HS_USERS_FORGOT_YOUR_PASSWORD'); ?>">
						<i class="icon-question-sign"></i>						
					</a>
				</div>
			</div>
		</div>
		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="form-login-remember" class="control-group checkbox">
			<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/><label for="modlgn-remember" class="control-label"><?php echo JText::_('MOD_HS_USERS_REMEMBER_ME') ?></label> 
		</div>
		<?php endif; ?>
		<div id="form-login-submit" class="control-group">
			<div class="controls">
				<button type="submit" tabindex="3" name="Submit" class="btn btn-primary btn"><?php echo JText::_('JLOGIN') ?></button>
			</div>
		</div>
		
		
		<div class="clear"></div>
		
		<div class="hs_users_actions">
			<?php if((int)$params->get('display_links',1)>0):?>		
				<ul class="unstyled">
					<?php if ($usersConfig->get('allowUserRegistration')) : ?>					
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_hs_users&view=registration'); ?>">
						<?php echo JText::_('MOD_LOGIN_REGISTER'); ?> <i class="icon-arrow-right"></i></a>
					</li>
					<?php endif; ?>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
						  <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
							<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?>
						</a>
					</li>	
				</ul>
			<?php endif; ?>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="user.login" />
			<input type="hidden" name="return" value="<?php echo $return; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
		<?php endif; ?>
		
		
		
		
		<?php //########## SOCIAL LIST #################
		//if ($usersConfig->get('allowUserRegistration')) : ?>
		<div class="hs_users_socials">
			<?php 
				jimport('hs.user.html');
				echo HsUserHTML::getSocialList();
			?>				
		</div>
		<?php //endif; ?>
		
		
	</div>
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
	
	
	
	
	
</form>
