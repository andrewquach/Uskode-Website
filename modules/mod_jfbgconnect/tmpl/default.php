<?php 
/**
* @package 		FacebookGraphConnect module for joomla 1.5.x
* @copyright	Copyright (C) Computer - http://www.sikkimonline.info. All rights reserved.
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* @author		Saran Chamling (saaraan@gmail.com)
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.methods' );

$style = '<style type="text/css">'.$params->get('additionalcss').'</style>';
$document =& JFactory::getDocument();
$document->addCustomTag($style);

echo "

<!-- Skm Facebook Fancy Lgin module 1D, com 1.7 Start -->

";


$user = & JFactory::getUser();
$logintype = (!$user->get('guest')) ? 'logout' : 'login';
$showinitial = ($params->get('author-initial')==1)?  '<a href="http://www.sikkimonline.info" target="_new" title="Sikkim Online"><img src="'.JURI::base().'modules'.DS.'mod_jfbgconnect'.DS.'tmpl'.DS.'pwd.png" border="0" /></a>' : '';

if ($params->get('show-only-button',0)==0)
{

	if($logintype=='logout')
	{
		if(isset($list["me"]) && $list["me"])
		{
			echo '<div class="fb_profile" style="text-align:center">';
			if ($params->get('fbshowprofileimage','1')==1)
			{
				if ($params->get('linkprofileimage','1')==1)
				{
				echo '<a href="http://www.facebook.com/profile.php?id='.$list["uid"].'" target="_new" title="My Facebook Profile"><img src="https://graph.facebook.com/me/picture?type='.$params->get('fbavatarimgsize','large').'&access_token='.$list["acctoken"].'" border="0" /></a>';
				}else{
				echo '<img src="https://graph.facebook.com/me/picture?type='.$params->get('fbavatarimgsize','large').'&access_token='.$list["acctoken"].'" />';
				}
			
			}
			echo '<div class="fb_welocme_txt">'.JText::_('WELCOME').' '.$user->name.'</div>';
			echo '<div class="fb_logout_txt"><a href="#" onclick="javascript:fb_logout(); return false;">'.JText::_('LOG_OUT').'</a></div>';
			echo '</div>';
		}
		else
		{
			echo '<div class="fb_welocme_txt">'.JText::_('WELCOME').' '.$user->name.'</div>';
			echo '<div class="fb_logout_txt"><a href="index.php?option=com_user&task=logout&return='.base64_encode(JURI::current()).'">'.JText::_('LOG_OUT').'</a></div>';
		}		
	}
	else
	{
		echo 	'<form action="'.JRoute::_( 'index.php', true, $params->get('usesecure')).'" method="post" name="login" id="form-login" >
			 	<fieldset class="input">
				<p id="form-login-username">
				<label for="modlgn_username">'.JText::_('USERNAME').'</label><br />
				<input id="modlgn_username" type="text" name="username" class="inputbox" alt="'.JText::_('USERNAME').'" size="18" />
				</p>
				<p id="form-login-password">
				<label for="modlgn_passwd">'.JText::_('PASSWORD').'</label><br />
				<input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="'.JText::_('PASSWORD').'" />
				</p>';
				if(JPluginHelper::isEnabled('system', 'remember'))
				{
					echo '<p id="form-login-remember">
					<label for="modlgn_remember">'.JText::_('REMEMBERME').'</label>
					<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="'.JText::_('REMEMBERME').'" />
					</p>';
                }
				echo '<input type="submit" name="Submit" class="button" value="'.JText::_('LOG_IN').'" />
				</fieldset>';
				
				echo '<ul style="margin-left:'.$params->get('link-text-margin-left',0).'px;margin-right:'.$params->get('link-text-margin-right',0).'px;">';
					if($params->get('show_forgotpass')==1)
					{
					echo '<li><a href="'.JRoute::_( 'index.php?option=com_user&view=reset' ).'">'.JText::_('FORGOTPASSWORD').'</a></li>';
					}
					if($params->get('show_forgotusername')==1)
					{
					echo '<li><a href="'.JRoute::_( 'index.php?option=com_user&view=remind' ).'">'.JText::_('FORGOTUSERNAME').'</a></li>';
					}
					$usersConfig = &JComponentHelper::getParams( 'com_users' );
					if($params->get('show_manualreg')==1){
						if ($usersConfig->get('allowUserRegistration'))
						{
						echo '<li><a href="'.JRoute::_( 'index.php?option=com_user&view=register' ).'">'.JText::_('CREATEACCOUNT').'</a></li>';
						}
					}
				echo '</ul>';
			
			
			
			echo '<input type="hidden" name="option" value="com_user" /><input type="hidden" name="task" value="login" /><input type="hidden" name="return" value="'.base64_encode(JURI::current()).'" />
			'.JHTML::_( 'form.token' ).'</form><noscript>'.JText::_('ENABLEJAVASCRIPT').'</noscript>
			<div class="fb_login_button" style="margin-left:'.$params->get('link-text-margin-left',0).'px;margin-right:'.$params->get('link-text-margin-right',0).'px;"><a href="#" onclick="requestExtPerm(); return false;"><img src="'.JURI::base().'modules'.DS.'mod_jfbgconnect'.DS.'tmpl'.DS.$params->get('facebook-button-type','small-blue.gif').'" border="0" /></a>'.$showinitial.'</div>';


	}

}
else 
{
	if($logintype=='logout')
	{
		if($list["me"])
		{
			echo '<div class="fb_profile" style="text-align:center">';
			if ($params->get('fbshowprofileimage','1')==1)
			{
				echo '<img src="https://graph.facebook.com/me/picture?type='.$params->get('fbavatarimgsize','large').'&access_token='.$list["acctoken"].'" />';
			}
			echo '<div class="fb_welocme_txt">'.JText::_('WELCOME').' '.$user->name.'</div>';
			echo '<div class="fb_logout_txt"><a href="#" onclick="javascript:fb_logout(); return false;">'.JText::_('LOG_OUT').'</a></div>';
			echo '</div>';
		}
		else
		{
			echo '<div class="fb_welocme_txt">'.JText::_('WELCOME').' '.$user->name.'</div>';
			echo '<div class="fb_logout_txt"><a href="index.php?option=com_user&task=logout&return='.base64_encode(JURI::current()).'">'.JText::_('LOG_OUT').'</a></div>';
		}	
	}
	else
	{
		echo '<div class="fb_login_button" style="margin-left:'.$params->get('link-text-margin-left',0).'px;margin-right:'.$params->get('link-text-margin-right',0).'px;"><a href="#" onclick="requestExtPerm(); return false;"><img src="'.JURI::base().'modules'.DS.'mod_jfbgconnect'.DS.'tmpl'.DS.$params->get('facebook-button-type','small-blue.gif').'" border="0" /></a>'.$showinitial.'</div>';
	}
}

echo '<span id="fb-root"></span>';

if($list["appid"]<1)
{
echo JText::_('REQUIRES_APPID');
}
echo '<img src="'.JURI::base().'modules/mod_jfbgconnect/ajax-loader.gif" style="display:none;" /><script type="text/javascript"> window.fbAsyncInit = function() {FB.init({ appId   : \''.$list["appid"].'\',session:'.json_encode($list["session"]).',status:true,cookie:true,xfbml: true});FB.Event.subscribe(\'auth.login\', function() {createDiv();window.location.reload();});};(function(){var e = document.createElement(\'script\');e.src = document.location.protocol + \'//connect.facebook.net/en_US/all.js#'.$list["appid"].'\';e.async = true;document.getElementById(\'fb-root\').appendChild(e);}());function fb_logout(){FB.logout(function(response) {logoutDiv();window.location = "index.php?option=com_user&task=logout&return='. base64_encode(JURI::current()).'"});}function requestExtPerm(){FB.login(function(response) {if (response.session) {if (response.perms) {window.location.reload();} else {}} else {}}, {perms: \'publish_stream,email\'});}function createDiv(){var skmfbdiv = document.createElement("div");skmfbdiv.id = "skmdiv";skmfbdiv.setAttribute("align","center");skmfbdiv.className ="skmfbdivstyle";skmfbdiv.innerHTML = \'<img src="'.JURI::base().'modules/mod_jfbgconnect/ajax-loader.gif" width="16" height="16" /> '.JText::_('CONNECTINGTOFACEBOOK').'\';document.body.appendChild(skmfbdiv);}function logoutDiv(){var skmfbdiv = document.createElement("div");skmfbdiv.id = "skmdiv";skmfbdiv.setAttribute("align","center");skmfbdiv.className ="skmfbdivstyle";skmfbdiv.innerHTML = \'<img src="modules/mod_jfbgconnect/ajax-loader.gif" width="16" height="16"> '.JText::_('LOGGINGOUTOFFACEBOOK').'\';document.body.appendChild(skmfbdiv);}</script>';



echo "

<!-- Skm Facebook Fancy Lgin module 1D, com 1.7 End -->

";
?>