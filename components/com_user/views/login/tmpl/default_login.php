<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php if(JPluginHelper::isEnabled('authentication', 'openid')) :
		$lang = &JFactory::getLanguage();
		$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
		$langScript = 	'var JLanguage = {};'.
						' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
						' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
						' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
						' var comlogin = 1;';
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration( $langScript );
		JHTML::_('script', 'openid.js');
endif; ?>
<center>
<div class="loginbox" style="background:none repeat scroll 0 0 #f0f0f0;margin:8px 0;border-radius:7px 7px 7px 7px;padding-top:20px;border:1px solid #e4e4e4;width:70%">

<form action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure')); ?>" method="post" name="com-login" id="com-form-login">
<fieldset class="input" style="border:0px;text-align:left;width:70%;">
	<h1 style="background:url("images/login.jpg") no-repeat scroll left top transparent;padding-left:50px;height:36px;color:black;">Login</h1>
	<p id="com-form-login-username">
		<label for="username"><b><?php echo JText::_('Username') ?></b></label><br/>
		<input name="username" id="username" type="text" class="inputbox" alt="username" style="width:350px;padding-bottom:6px;;border:1px solid #CCCCCC;border-radius:5px 5px 5px 5px;height:15px;" />
	</p>
	<p id="com-form-login-password">
		<label for="passwd"><b><?php echo JText::_('Password') ?></b></label><br/>
		<input type="password" id="passwd" name="passwd" class="inputbox" size="18" alt="password" style="width:350px;padding-bottom:6px;;border:1px solid #CCCCCC;border-radius:5px 5px 5px 5px;height:15px;"  />
	</p>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	<!--<p id="com-form-login-remember">
		<label for="remember" style="text-align:left;display: block;float: left;margin: 2px 4px 6px 4px;"><?php echo JText::_('Remember me') ?></label>
		<input type="checkbox" id="remember" name="remember" class="inputbox" value="yes" alt="Remember Me" /> 
	</p>-->	
	<?php endif; ?> 		
	<ul style="text-align:left;display: block;float: left;margin: 2px 4px 6px 4px;font-size:11px;">
	<li>
		<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
		<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
	</li>
	<li>
		<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
		<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
	</li>
	<?php
	$usersConfig = &JComponentHelper::getParams( 'com_users' );
	if ($usersConfig->get('allowUserRegistration')) : ?>
	<li>
		<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>">
			<?php echo JText::_('REGISTER'); ?></a>
	</li>
	<?php endif; ?>
</ul>
<label style="width:150px;align:right;display: block;float: left;margin: 2px 4px 6px 4px;text-align: right;"></label>
<input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" style="font-weight:bold;"/>
</fieldset>
</div>
</center>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
