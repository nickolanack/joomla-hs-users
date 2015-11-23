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


jimport('hs.user.user');
$userEx = HsUser::getInstance();

?>

<div id="hs_users_setting">
	<div id="hsuser_image" class="setting_box">
		<h3 class="box_title">
			<?php echo JText::_('COM_HS_USERS_VIEW_SETTING_USER_IMAGE_TITLE');?>
		</h3>
		
		
		
		<div id="user_image_box">
		<?php if(isset($userEx->image)):?>
			
				<?php echo $userEx->get('image');?>
			
		<?php else:?>
			
				<img src="components/com_hs_users/asset/images/noimage.png" width="50" height="50" />
			
		<?php endif;?>
		</div>
		
		<div id="hs_uploadbox">
			<div id="btn_change_image" class="hs_buttons">
				<span><?php echo JText::_('COM_HS_USERS_VIEW_SETTING_CHANGE_IMAGE');?></span>
			</div>
			<div id="hidden_change_image">
				<h3 class="inner_title"><?php echo JText::_('COM_HS_USERS_VIEW_SETTING_CHANGE_IMAGE_TITLE');?></h3>
				<form id="userimage_form" action="<?php echo JRoute::_('index.php');?>" method="post" enctype="multipart/form-data">
					<input type="file" id="hs_userimage" name="userimage" value="" />
					<input type="hidden" name="option" value="com_hs_users" />
					<input type="hidden" name="task" value="setting.uploadImage" />
					<?php echo JHtml::_('form.token'); ?>
				</form>		
			</div>
			
			<?php if(mb_strlen($userEx->image)>0):?>
				<div id="btn_delete_image" class="hs_buttons" title="<?php echo JText::_('COM_HS_USERS_VIEW_SETTING_DELETE_IMAGE_CONF');?>">
					<span><?php echo JText::_('COM_HS_USERS_VIEW_SETTING_DELETE_IMAGE');?></span>
				</div>			
				<div id="hidden_delete_image">
					<form id="delete_userimage_form" action="<?php echo JRoute::_('index.php');?>" method="post" enctype="multipart/form-data">
						<input type="hidden" name="option" value="com_hs_users" />
						<input type="hidden" name="task" value="setting.deleteImage" />
						<?php echo JHtml::_('form.token'); ?>
					</form>		
				</div>
			<?php endif;?>	
					
		</div>
		
		<div class="clear"></div>
	</div>
	
	
	
	
	<?/* FIXME Disable description function
	 * Should be add user-defined fields
	 * 
	<div id="hsuser_desc" class="setting_box">
		<div>
			<span id="hs_actual_text">
			<?php if(isset($this->data->description)):?>
				<?php echo $this->data->description;?>			
			<?php endif;?>
			</span>			
			
			
			<span id="hs_notext"><?php echo JText::_('COM_HS_USERS_VIEW_SETTING_NO_DESC');?></span>
		</div>
		
	</div>
	*/?>
	
	
	<div class="clear"></div>
	

	
	
	
	
	<div id="other_connections" class="setting_box">
		<h3 class="box_title">
			<?php echo JText::_('COM_HS_USERS_VIEW_SETTING_OTHER_CONNECTION_TITLE');?>
		</h3>		
		
		
		
		<?php if(count($this->providerList)>0):?>
			<?php
				jimport('hs.user.html');
				//load css
				HsUserHTML::loadAssetCSS();
				$token = JSession::getFormToken();
				$linkBase = 'index.php?option=com_hs_users&task=authentications.addNewAuthenticationWith&'.$token.'=1&provider=';
				
			?>
			
			<ul class="hs_social_list">
				
				<?php foreach($this->providerList as $row):?>
				<li class="socials <?php echo $row->name;?>">
					<?php if($row->connected):?>
						<span class="hbox connected">
							<span class="icon"></span>
							<?php echo JText::sprintf('COM_HS_USERS_VIEW_SETTING_ALREADY_CONNECTTED_WITH', $row->name);?>
						</span>						
					
					<?php else:?>
						<a class="hbox unconnected" href="<?php echo $linkBase.$row->name;?>">
							<span class="icon"></span>
							<?php echo JText::sprintf('COM_HS_USERS_VIEW_SETTING_CONNECT_WITH', $row->name);?>
						</a>
						
					<?php endif;?>
					
				</li>				
				<?php endforeach;?>
			</ul>
		
		<?php endif;?>
	</div>
	
	
	
	
	<form action="<?php echo JRoute::_('index.php');?>" method="post" id="hs_form_sec">
		<?php echo JHtml::_('form.token'); ?>
	</form>	
	
</div>