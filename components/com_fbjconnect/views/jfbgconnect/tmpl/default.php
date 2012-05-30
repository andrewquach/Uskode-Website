<?php 
/**
* @package 		FacebookGraphConnect
* @version 		1.2
* @copyright	Copyright (C) Computer - http://www.sikkimonline.info. All rights reserved.
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* @author		Saran Chamling (saaraan@gmail.com)
* @download URL	http://www.sikkimonline.info/joomla-facebook-graph-connect
*/

defined('_JEXEC') or die('Restricted access');
$document 		=& JFactory::getDocument();
list($uid,$me,$session) = JfbgconnectController::try_connect();
$myparams 		= &JComponentHelper::getParams('com_fbjconnect');
$viewlayout		= $myparams->get('viewlayout',1);
$viewstyle		= $myparams->get('cssstyle',1);
$rndstrnglength = $myparams->get('length-of-randstring',0);

$document->addStyleSheet('components'.DS.'com_fbjconnect'.DS.'fbstyle.css');

if(JfbgconnectController::count_this_user($uid)==0)
{
//variables used here in this template, just copy/paste variables where u need them.
$fbflnames 					= $me["first_name"]." ".$me["last_name"];
$full_name_of_user_with_txt	= JText::sprintf(JText::_('YOUARECONNECTED'),$fbflnames, $mainframe->getCfg('sitename'));
$note_text_below_heading 	= JText::_('NOTETEXT');
$return_url 				= JfbgconnectController::getreturnurl();
$new_visitor_heading 		= JText::_('NEWVISITORS');
$username_text 				= JText::_('ENTERUSERNAME');
$password_text 				= JText::_('ENTERPASSWORD');
$email_will_be_sent_to 		= JText::sprintf(JText::_('EMAILWILLBESENTTO'),$me["email"]);
$returning_member_heading 	= JText::_('RETURNINGMEMBERS');
$create_new_user_button 	= JText::_('CREATENEWUSEBUTT');
$link_my_account_button		= JText::_('LINKMYACCOUNTBUTT');
$randomusername				= strtolower($me['first_name']).JfbgconnectController::random_str($rndstrnglength);

if(strlen($me["email"])<1)
{
$additionalpermissionhead	= JText::_('ADDITIONALHEAD');
$fbauthlink 				= '<a href="https://www.facebook.com/dialog/oauth?client_id='.$myparams->get('appid').'&redirect_uri='.JURI::current().'&scope=email,read_stream">Click Here</a>';
$additional_permiss_txt		= JText::sprintf(JText::_('EXTENDEDPERMISSIOINREQUIRED'),$fbauthlink);
##################### EDIT HTML BELOW THIS LINE TO CUSTOMIZE YOUR FB REGISTRATION LOOK ##################
?>
<!-- Show this div box if we need additional info, such as.. email address. -->
<div class="additional_permission_class">
<h2><?php echo $additionalpermissionhead; ?></h2>
	<ul>
	<li>
		<?php echo  $additional_permiss_txt; ?></li>
	</ul>
</div>
<?php
}
?>

<div align="center">
<h1><?php echo $full_name_of_user_with_txt; ?></h1>
<p class="notic"><?php echo $note_text_below_heading; ?></p>
</div>
<?php if($viewlayout==1) {?>

<table border="0" cellpadding="5" align="center">
  <tr>
    <td>
	<form method="post" action="index.php?option=com_fbjconnect&re=<?php echo $return_url; ?>">
	<table id="box-table-a" width="100%" border="0" cellpadding="5" class="table_bg">
	  <thead>
      <tr>
        <th colspan="2" class="heading_class"><?php echo $new_visitor_heading; ?></th>
        </tr> 
        <thead>
        <tbody>  
      <tr>
        <td align="right"><?php echo $username_text; ?></td>
        <td align="left"><input type="text" name="newusername" value="<?php echo $randomusername; ?>"/></td>
      </tr>
      <tr>
        <td colspan="2" align="center">
		<input name="" type="submit" class="submitbutt" value="<?php echo $create_new_user_button; ?>"/>
		<input type="hidden" name="newacc" value="1" /><?php echo $email_will_be_sent_to; ?></td>
        </tr>
        </tbody>
    </table></form>	<span class="or_class"></span></td>
  </tr>
  <tr>
    <td align="center">OR</td>
  </tr>
  <tr>
    <td><form method="post" action="index.php?option=com_fbjconnect&re=<?php echo $return_url; ?>">
	<table  id="box-table-a" width="100%" border="0" cellpadding="5" class="table_bg">
      <thead><tr>
        <th colspan="2" class="heading_class"><?php echo $returning_member_heading; ?> </th>
        </tr>
        </thead>
     <tbody>
     <tr>
        <td align="right"><?php echo $username_text; ?></td>
        <td align="left"><input type="text" name="iusername" /></td>
      </tr>
      <tr>
        <td align="right"><?php echo $password_text; ?></td>
        <td align="left"><input  name="ipassword" type="password" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center">	<input  name="linkacc" type="hidden" value="1" />
	<input name="" type="submit" class="submitbutt" value="<?php echo $link_my_account_button; ?>"/></td>
        </tr>
      </tbody>
    </table>
    </form></td>
  </tr>
</table>

<?php }else{ ?>

<table border="0" cellpadding="5" class="main_table_class" align="center">
  <tr>
    <td width="50%" valign="top">
	<form method="post" action="index.php?option=com_fbjconnect&re=<?php echo $return_url; ?>">
    <table id="box-table-a" width="100%" border="0" cellpadding="5" class="table_bg">
	  <thead><tr>
        <th colspan="2" class="heading_class"><?php echo $new_visitor_heading; ?></th>
        </tr>  
        </thead>  
      <tbody>
      <tr>
        <td align="right"><?php echo $username_text; ?></td>
        <td align="left"><input type="text" name="newusername" value="<?php echo $randomusername; ?>" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center">
		<input name="" type="submit" class="submitbutt" value="<?php echo $create_new_user_button; ?>"/>
		<input type="hidden" name="newacc" value="1" /><?php echo $email_will_be_sent_to; ?></td>
        </tr>
     </tbody>
    </table>
	</form></td>
    <td width="10" align="center" valign="middle"><span class="or_class">OR</span></td>
    <td width="50%" valign="top">	<form method="post" action="index.php?option=com_fbjconnect&re=<?php echo $return_url; ?>">
	  <table id="box-table-a" width="100%" border="0" cellpadding="5" class="table_bg">
      <thead>
      <tr>
      <th colspan="2" class="heading_class"><?php echo $returning_member_heading; ?> </th>
       </tr>
      </thead>
      <tbody>
      <tr>
        <td align="right"><?php echo $username_text; ?></td>
        <td align="left"><input type="text" name="iusername" /></td>
      </tr>
      <tr>
        <td align="right"><?php echo $password_text; ?></td>
        <td align="left"><input  name="ipassword" type="password" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center">	<input  name="linkacc" type="hidden" value="1" />
	<input name="" type="submit" class="submitbutt" value="<?php echo $link_my_account_button; ?>"/></td>
        </tr>
        </tbody>
    </table>
    </form></td>
  </tr>
</table>
<?php }?>
	<div style="clear:both"></div>
	<div align="center">
	
	</div>
<?php
####################### END YOUR HTML EDITING HERE ##############################
}else{
			$errortext = "<a href='".JfbgconnectController::getreturnurl(true)."'>".JText::_('REVIOUSPAGE')."</a>";
			
			// line below causes infinite redirection cusing error ..
			//$mainframe->enqueueMessage($errortext, 'error');
			//$mainframe->redirect(JRoute::_('index.php?re='.JfbgconnectController::getreturnurl(), false));
}
?>
