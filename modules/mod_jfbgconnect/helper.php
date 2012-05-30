<?php
/**
* @package 		FacebookGraphConnect for joomla 1.5
* @copyright	Copyright (C) Computer - http://www.sikkimonline.info. All rights reserved.
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* @author		Saran Chamling (saaraan@gmail.com)
* @download URL	http://www.sikkimonline.info/joomla-facebook-graph-connect
*/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
class modjfbgconnector
{
    
	function pullItems( $params )
    {
		global $mainframe;
		$db =& JFactory::getDBO();
		$checkuser = JFactory::getUser();
				
		$myparams = &JComponentHelper::getParams( 'com_fbjconnect' );
		$getappid =$myparams->get('appid');
		$getappsec =$myparams->get('appsecret');
		$me = "";
		list($uid,$me,$session,$access_token) = JfbgconnectController::try_connect($getappid,$getappsec);
		$items	= array();
		$items["appid"] = $getappid;
		$items["session"] = $session;
		require_once (JPATH_ROOT.DS.'components'.DS.'com_fbjconnect'.DS.'fbgccontroller.php');			
		if (($me && $checkuser->guest) || ($me && JfbgconnectController::return_fbloggedin_userid($checkuser->id) == $uid))//make sure user is not logged in OR logged in jom user matches with logged in facebook user
		{
			$items["first_name"] = $me['first_name'];
			$items["uid"] = $uid;
			$items["me"] = $me;
			$items["acctoken"] = $access_token;
			$iferrortxt = JRequest::getVar('errtxt','0','get');

			if(JfbgconnectController::count_this_user($uid)==0 && $iferrortxt==0) //Logged in with facebook but doesnt exist in fbtable, redirect to registration.
				{
					$mainframe->redirect(JRoute::_('index.php?option=com_fbjconnect&fb=1&re='.base64_encode(JURI::current()), false),'');
				}
			elseif(JfbgconnectController::count_this_user($uid)>0 && $checkuser->guest) //fb user exist, but not logged in, auto login him in
				{
					$mainframe->redirect(JRoute::_('index.php?option=com_fbjconnect&fb=2&re='.base64_encode(JURI::current()), false),'');
				}
		}

		return $items;
    }

}




?>

