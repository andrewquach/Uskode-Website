<?php
/**
ini_set('display_errors', 1);
error_reporting(E_ALL);
* @package 		FacebookGraphConnect for joomla 1.5
* @copyright	Copyright (C) Computer - http://www.sikkimonline.info. All rights reserved.
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* @author		Saran Chamling (saaraan@gmail.com)
* @download URL	http://www.sikkimonline.info/joomla-facebook-graph-connect
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JfbgconnectController extends JController
{
	function display() {
        // Make sure we have a default view
        if( !JRequest::getVar( 'view' )) {
		    JRequest::setVar('view', 'jfbgconnect' );
        }
		parent::display();
	}
	
	function getreturnurl($option=false)
	{
		if(strlen(JRequest::getVar('re','','GET'))<1) // no return url set, return base url
		{
			if($option)//option true for decoded url
			{
				$returnurl= JURI::base();
			}else{
				$returnurl= base64_encode(JURI::base());
			}
		}
		else
		{
			if($option)//option true for decoded url
			{
				$returnurl= base64_decode(JRequest::getVar('re','','GET'));
			}else{
				$returnurl= JRequest::getVar('re','','GET');
			}
		}
	return $returnurl;
	}
	
	function try_connect($pubit=0, $stUrl='',$PublishMessage='') 
	{ 
		$myparams = &JComponentHelper::getParams('com_fbjconnect');
		$getappid = $myparams->get('appid');
		$getappsec = $myparams->get('appsecret');
		$access_token="";
		$uid = "";
		$postresult = false;
		$facebook = new Facebook(array(
		'appId'  => $getappid,
		'secret' => $getappsec,
		'cookie' => true,
		));
		$session = $facebook->getSession();
		$me = null;
		$uid = "";
		if ($session) 
		{
		try {
				$access_token = $facebook->getAccessToken();
				$me = $facebook->api('/me');
				$uid = $facebook->getUser();
				if($pubit==1)
				{
				$fbpic = JURI::base().'modules/mod_jfbgconnect/fgimage.jpg';
				$postresult = $facebook->api('/me/feed/','post', array('access_token' => $access_token, 'picture'=> $fbpic, 'link' => $stUrl, 'message' => $PublishMessage));
				}
			} 
			catch (FacebookApiException $e) 
			{
			error_log($e);
			}
		}
		
	return array($uid,$me,$session,$access_token,$postresult);
	}

	function return_fbloggedin_userid($userid)
	{
					$db =& JFactory::getDBO();
						$db->setQuery("SELECT facebook_userid FROM #__facebook_joomla_connect WHERE joomla_userid=".$userid);
						$results 		= $db->loadObject();
						if($results)
						{
							$retrivedbid 	= $results->facebook_userid;
							return $retrivedbid;
						}
	}
	function count_this_user($userid) //make sure this fb user exist in both table
	{
		$db =& JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__users LEFT JOIN #__facebook_joomla_connect ON #__users.id=#__facebook_joomla_connect.joomla_userid WHERE #__facebook_joomla_connect.facebook_userid=$userid AND #__facebook_joomla_connect.linked=1";
		$db->setQuery($query);
		$count_user = $db->loadResult();
		return $count_user;
	}
	function count_this_fb_user($fbuid) // count this user in facebook_joomla_connect table
	{

		$db =& JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__facebook_joomla_connect WHERE facebook_userid=".$fbuid;
		$db->setQuery($query);
		$count_fbuserid = $db->loadResult();
		return $count_fbuserid;
	}
	function count_this_username($username) // count this user in users table
	{
		$db =& JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__users WHERE username=".$db->Quote($username);
		$db->setQuery($query);
		$count_username = $db->loadResult();
		return $count_username;
	}
	function count_this_useremail($useremail) //count email in user table
	{
		$db =& JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__users WHERE email=".$db->Quote($useremail);
		$db->setQuery($query);
		$count_email = $db->loadResult();
		return $count_email;
	}
	
#########################login returning users #########################
	function LogInUsers()
	{
		
		global $mainframe;
		$db =& JFactory::getDBO();
		jimport('joomla.user.helper');
		JPluginHelper::importPlugin('user');
		$user =& JFactory::getUser();
		list($uid,$me) = JfbgconnectController::try_connect();
		if(JfbgconnectController::count_this_user($uid)>=1) 
		{
				$db->setQuery("SELECT  #__users.username as username,
				#__facebook_joomla_connect.joomla_userid as jomuserid
				FROM #__facebook_joomla_connect LEFT JOIN #__users 
				ON #__facebook_joomla_connect.joomla_userid=#__users.id
				WHERE #__facebook_joomla_connect.facebook_userid=$uid 
				AND #__facebook_joomla_connect.linked=1");
				$userDetails = $db->loadObjectList();
				$row = $userDetails[0];
				
				$response->username = $row->username;
				$result = $mainframe->triggerEvent('onLoginUser', array((array)$response, $options));
				if ($result) 
				{
				   $mainframe->redirect(JfbgconnectController::getreturnurl(true));
				}	
		}
		elseif($me && JfbgconnectController::count_this_user($uid)>=1) 
		{
			$mainframe->redirect(JfbgconnectController::getreturnurl(true));
		}
		elseif(!$me)
		{
			$mainframe->redirect(JURI::base());
		}elseif(!$user->get('guest')){
			$mainframe->logout();
		}

	}
//#########################Link existing account ###########################
function LinkExistingUser()
{
 	global $mainframe;
	jimport('joomla.user.helper');
	$db =& JFactory::getDBO();
	$optionlinkacc = JRequest::getVar('linkacc','0','post');
	list($uid,$me) = JfbgconnectController::try_connect();
	 if(JfbgconnectController::count_this_user($uid)>0)
	 {
	 	$db->setQuery("SELECT  #__users.username as username
				FROM #__facebook_joomla_connect LEFT JOIN #__users 
				ON #__facebook_joomla_connect.joomla_userid=#__users.id
				WHERE #__facebook_joomla_connect.facebook_userid=$uid");
				$userDetails = $db->loadObjectList();
				$row = $userDetails[0];
		
		$errortext = JText::sprintf(JText::_('USERALREADYLINKED'),$uid,$row->username);
		$mainframe->enqueueMessage($errortext, 'error');
		$mainframe->redirect(JRoute::_('index.php?re='.JfbgconnectController::getreturnurl(), false));
	 }
	 elseif($optionlinkacc==1 && $me)
	 {
		$linkusername = JRequest::getVar('iusername','','post');			
		$linkpassword = JRequest::getVar('ipassword','','post');
		$intdatetime = time();
		$fullname = $me['first_name']." ". $me['last_name'];
		$query = 'SELECT id, password FROM #__users WHERE username='.$db->Quote($linkusername);
		$db->setQuery($query);
		$results = $db->loadObject();

		if($results)
		{
			$parts	= explode( ':', $results->password );
			$crypt	= $parts[0];
			$salt	= @$parts[1];
			$testcrypt = JUserHelper::getCryptedPassword($linkpassword, $salt);
			if ($crypt == $testcrypt) {
				$joomla_userid = $results->id;
				$db->setQuery("INSERT INTO #__facebook_joomla_connect(joomla_userid,facebook_userid,joined_date,linked) VALUES ($joomla_userid,$uid,$intdatetime,1)");
				if (!$db->query())
				{
						$mainframe->enqueueMessage($db->getErrorMsg(), 'error');
						$mainframe->redirect(JRoute::_('index.php?re='.JfbgconnectController::getreturnurl(), false));
				}
				else
				{
					$db->setQuery("UPDATE #__users SET name=".$db->Quote($fullname).",joined_date=$intdatetime WHERE username=".$db->Quote($linkusername));
					if (!$db->query())
						{
								$mainframe->enqueueMessage($db->getErrorMsg(), 'error');
								$mainframe->redirect(JRoute::_('index.php?re='.JfbgconnectController::getreturnurl(), false));
						}
				}
				
					
				//preform login 
				JPluginHelper::importPlugin('user');
				$response->username = $linkusername;
				$result = $mainframe->triggerEvent('onLoginUser', array((array)$response, $options));
				if ($result) 
				{
					//publish it on the wall
					JfbgconnectController::try_connect(1,JURI::base(),JText::_('FBPUBLISHMESSAGE'));
					
					$successmsg = JText::sprintf(JText::_('LINKSUCESS'),$mainframe->getCfg('sitename'));
					$mainframe->enqueueMessage($successmsg, 'message');
					$mainframe->redirect(JfbgconnectController::getreturnurl(true));
					
				}	
			} 
			else 
			{
				$mainframe->enqueueMessage(JText::_('PASSWORDWRONG'), 'error');
				$mainframe->redirect(JRoute::_('index.php?re='.JfbgconnectController::getreturnurl(), false));
			}

		}
		else
		{
			$errortext = JText::sprintf(JText::_('USERNAMENOTFOUND'),$linkusername);
			$mainframe->enqueueMessage($errortext, 'error');
			$mainframe->redirect(JRoute::_('index.php?re='.JfbgconnectController::getreturnurl(), false));
		}
 	}

}
//######################### Create account ###########################
function varAccNewCrt()
	{
		 global $mainframe;
		 $db =& JFactory::getDBO();
		 $myparams 			= &JComponentHelper::getParams('com_fbjconnect');
		 $mailsender 		= $myparams->get('emailsender',1);
		 $connecttype 		= $myparams->get('connect-type',0);
		 $linkwithemail 	= $myparams->get('link-with-email',1);
		 $rndstrnglength 	= $myparams->get('length-of-randstring',0);
		 list($uid,$me) 	= JfbgconnectController::try_connect();
		 $optioncreateacc 	= JRequest::getVar('newacc','0','post');	
		 		
		 if(($connecttype==1 && $me) || ($optioncreateacc==1 && $me))
		 {	

			if((strlen(JRequest::getVar('newusername','','post'))>1) || ($connecttype==1))
			{
				
				if($connecttype==1)
					{
						// generate a random username if auto registration is set
						$musername = strtolower(JfbgconnectController::just_clean($me['first_name'])).JfbgconnectController::random_str($rndstrnglength);
					}else{
						$musername = JRequest::getVar('newusername','','post');
					}
					
					jimport( 'joomla.user.helper' );
						
					 // Get required system objects
					$user       	= clone(JFactory::getUser());
					$pathway    	=& $mainframe->getPathway();
					$config     	=& JFactory::getConfig();
					$authorize  	=& JFactory::getACL();
					$document   	=& JFactory::getDocument();
					
					// Use the Joomla helper
					$usersConfig = &JComponentHelper::getParams( 'com_users' );
					$newUsertype = $usersConfig->get( 'new_usertype' );
					if (!$newUsertype) {
						$newUsertype = 'Registered';
					}			
									
					$fullname 		= $me['first_name']." ". $me['last_name'];
					$email 			= $me['email'];
					$newpassword 	= JUserHelper::genRandomPassword(5);
					$intdatetime 	= time();			

					// let's check email address before we proceed,  we will take action acording to params
					if(JfbgconnectController::count_this_useremail($me['email'])>0 && $linkwithemail==1)	
					{
						$query = 'SELECT username,id FROM #__users WHERE email='.$db->Quote($me['email']);
						$db->setQuery($query);
						$results 			= $db->loadObject();
						$retrivedbid 		= $results->id;
						$retrivedusername	= $results->username;
						
						$fbinsertifemail = "INSERT INTO #__facebook_joomla_connect(joomla_userid,facebook_userid,joined_date,linked) VALUES ($retrivedbid,$uid,$intdatetime,1)";
						$db->setQuery($fbinsertifemail);
						if(!$db->query())
						{
							$mainframe->enqueueMessage("Email link Error", 'error');
							if($connecttype==1)
							{
								$mainframe->redirect(str_replace('option=com_fbjconnect','',JRoute::_(JURI::base().'index.php?errtxt=1', false)));
							}
							else
							{
								$mainframe->redirect(JRoute::_('index.php?errtxt=1&re='.JfbgconnectController::getreturnurl(), false));
							}	
						}
						else
						{
						JPluginHelper::importPlugin('user');
						$response->username = $retrivedusername;
						$result = $mainframe->triggerEvent('onLoginUser', array((array)$response, $options));
						
						// Publish on user's wall
						$publishonwall = JText::sprintf(JText::_('FBPUBLISHMESSAGE'),$mainframe->getCfg('sitename'));
						JfbgconnectController::try_connect(1,JURI::base(),$publishonwall); 
						
						$regsuccessmsg = JText::sprintf(JText::_('FOUNDYOUREMAIL'),$retrivedusername, $me['email']);
						$mainframe->enqueueMessage($regsuccessmsg, 'message');
						$mainframe->redirect(JfbgconnectController::getreturnurl(true));
						exit();
						}
											
					}
					
					$userData= array();
					$userData['name'] 		= $fullname;
					$userData['username'] 	= $musername;
					$userData['email'] 		= $email;
					$userData['password'] 	= $newpassword;
					$userData['password2'] 	= $newpassword;
					
					 // Bind the post array to the user object
					 if (!$user->bind($userData, 'usertype' )) {
						$mainframe->enqueueMessage($user->getError(), 'error');
						
						if($connecttype==1)
						{
							$mainframe->redirect(str_replace('option=com_fbjconnect','',JRoute::_(JURI::base().'index.php?errtxt=1', false)));
						}
						else
						{
							$mainframe->redirect(JRoute::_('index.php?errtxt=1&re='.JfbgconnectController::getreturnurl(), false));
						}
					 }
			   
					 // Set some initial user values
					 $user->set('id', 0);
					 $user->set('usertype', $newUsertype);
					 $user->set('gid', '18');
			   
					 $date =& JFactory::getDate();
					 $user->set('registerDate', $date->toMySQL());
			   
					 // If there's an error with registration
					 if ( !$user->save() )
					 {
						$mainframe->enqueueMessage($user->getError().'<br />If you are experiancing problem accessing site, <a href="#" onclick="javascript:fb_logout(); return false;">Clear Facebook session</a>!', 'error'); //auto rise errors
						if($connecttype==1)
						{
							$mainframe->redirect(str_replace('option=com_fbjconnect','',JRoute::_(JURI::base().'index.php?errtxt=1', false)));
						}
						else
						{
							$mainframe->redirect(JRoute::_('index.php?errtxt=1&re='.JfbgconnectController::getreturnurl(), false));
						}
					 }
				
						JPluginHelper::importPlugin('user');
						$response->username = $musername;
						$result = $mainframe->triggerEvent('onLoginUser', array((array)$response, $options));
						// everything done redirect user and log him in...preform the login action
						if ($result) 
							{
							$user =& JFactory::getUser();
							$jomuserid = $user->get('id');
							
							if(JfbgconnectController::count_this_fb_user($uid)>=1) //user exist in db update table 
								{
									$fbinsertquary = "UPDATE #__facebook_joomla_connect SET joomla_userid=$jomuserid,joined_date=$intdatetime WHERE facebook_userid=$uid";
								}else{
									$fbinsertquary = "INSERT INTO #__facebook_joomla_connect(joomla_userid,facebook_userid,joined_date,linked) VALUES ($jomuserid,$uid,$intdatetime,1)";
									
								}
								
								$db->setQuery($fbinsertquary);
							if (!$db->query())
								{
								//try insert into fb table
								$mainframe->enqueueMessage($db->getErrorMsg().'<br />If you are experiancing problem accessing site, <a href="#" onclick="javascript:fb_logout(); return false;">Clear Facebook session</a>!', 'error');
								if($connecttype==1)
									{
										$mainframe->redirect(str_replace('option=com_fbjconnect','',JRoute::_(JURI::base().'index.php?errtxt=1', false)));
									}
								else
									{
										$mainframe->redirect(JRoute::_('index.php?errtxt=1&re='.JfbgconnectController::getreturnurl(), false));
									}
								}
							
							//let's send email
							$mailfrom = $mainframe->getCfg('mailfrom');
							$fromname = $mainframe->getCfg('fromname');
							$sitename = $mainframe->getCfg('sitename');
							$subject = JText::sprintf(JText::_('EMAILSUBJECT'),$sitename);
							$bodytext = str_replace("***","<br />",JText::_('EMAILBODY')); // replace *** with <br> html tag.
							$bodytext = JText::sprintf($bodytext,$fullname,$sitename,JURI::base(),$musername,$newpassword,JURI::base().'<br /> '.$sitename.' Team');
							$bodytext = html_entity_decode($bodytext, ENT_QUOTES);
							
							//get all super administrator
							$query = 'SELECT name, email, sendEmail' .
									' FROM #__users' .
									' WHERE LOWER( usertype ) = "super administrator"';
							$db->setQuery( $query );
							$rows = $db->loadObjectList();
					
							// Send email to user
							if (!$mailfrom  || !$fromname) {
								$fromname = $rows[0]->name;
								$mailfrom = $rows[0]->email;
							}
		
							jimport( 'joomla.utilities.utility' );
							
							if($mailsender==0)
							{
								$SendRegMail = JUtility::sendMail($mailfrom, $fromname, $email, $subject, $bodytext,true);
							}else{
								$SendRegMail = JfbgconnectController::fbj_mailer($fromname, $mailfrom, $email, $subject, $bodytext);
							}
							
							if (!$SendRegMail)
							{
									$mainframe->enqueueMessage(JText::sprintf(JText::_('EMAILERRORSEND'),'<a href="index.php?option=com_user&view=reset">Click here</a>'), 'error');
									if($connecttype==1)
										{
											$mainframe->redirect(str_replace('option=com_fbjconnect','',JRoute::_(JURI::base().'index.php?errtxt=1', false)));
										}
										else
										{
											$mainframe->redirect(JRoute::_('index.php?errtxt=1&re='.JfbgconnectController::getreturnurl(), false));
										}
							}

							// Send notification to all administrators
							$subject2 = sprintf ( JText::_( 'NEWACCOUNTOF' ), $fullname);
							$message2 = sprintf ( JText::_( 'SEND_MSG_ADMIN' ), $fullname, $sitename, $musername,$uid);

							// get superadministrators id
							foreach ( $rows as $row )
							{
								if ($row->sendEmail)
								{
									if($mailsender==0)
									{
										JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2,true);
									}else{
										JfbgconnectController::fbj_mailer($fromname, $mailfrom, $row->email, $subject2, $message2);
									}

								}
							}
							
							// Publish on user's wall
							JfbgconnectController::try_connect(1,JURI::base(),JText::_('FBPUBLISHMESSAGE')); 
							
							//end sending mail, redirect user
							$regsuccessmsg = JText::sprintf(JText::_('REGISTRATIONSUCCESS'),$mainframe->getCfg('sitename'));
							$mainframe->enqueueMessage($regsuccessmsg, 'message');
							$mainframe->redirect(JfbgconnectController::getreturnurl(true));
							}	
			}
		 }

}

//let's create a clean username
function just_clean($string)
{
$specialCharacters = array(
'#' => '',
'$' => '',
'%' => '',
'&' => '',
'@' => '',
'.' => '',
'€' => '',
'+' => '',
'=' => '',
'§' => '',
'\\' => '',
'/' => '',
'\'' => '',
);
while (list($character, $replacement) = each($specialCharacters)) {
$string = str_replace($character, '-' . $replacement . '-', $string);
}
$string = strtr($string,"ÀÁÂÃÄÅ�áâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ","AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn");
$string = preg_replace('/[^a-zA-Z0-9\-]/', '', $string);
$string = preg_replace('/^[\-]+/', '', $string);
$string = preg_replace('/[\-]+$/', '', $string);
$string = preg_replace('/[\-]{2,}/', '', $string);
return $string;
}


// Generate a random character string
function random_str($length = 0, $chars = 'abcdefghijklmnopqrstuvwxyz1234567890')
{
		if($length > 0)
		{
			$chars_length = (strlen($chars) - 1);
			$string = $chars{rand(0, $chars_length)};
			for ($i = 1; $i < $length; $i = strlen($string))
			{
				$r = $chars{rand(0, $chars_length)};
				if ($r != $string{$i - 1}) $string .=  $r;
			}
		
			return $string;
		}
}

// let's use our own proper header mail..
function fbj_mailer($from_name, $frome_mail, $to_email, $mail_subject, $mail_message) 
	{
				$headers  = 'From: '.$from_name.' <'.$frome_mail.'>'."\r\n";
				$headers .= 'Reply-To: '.$frome_mail.''."\r\n";
				$headers .= 'Return-Path: '.$frome_mail.''."\r\n";				
				$headers .= 'X-Mailer: '.$from_name.''."\n";
				$headers .= 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$sentmail = mail($to_email, $mail_subject, $mail_message, $headers);	
				return $sentmail;
	} 
}