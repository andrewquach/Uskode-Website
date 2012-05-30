<?php

/**
* @package 		FacebookGraphConnect for joomla 1.5
* @copyright	Copyright (C) Computer - http://www.sikkimonline.info. All rights reserved.
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* @author		Saran Chamling (saaraan@gmail.com)
* @download URL	http://www.sikkimonline.info/joomla-facebook-graph-connect
*/
// Nothing to see here, just admin cp htmls.
$me 					= "";
$sarannn["first_name"]	= "";
$componentversion 		= '1.7';
$changelog 				= "	1.0 -> Facebook Graph Connect Released for Joomla 1.5 <br />
							1.1 -> One Click Login and Registration option. Fixed Forgot pass/username link in login box. Auto Random username in registratioin page. Simple Statistics tab in admin cp.<br />
							1.2 -> Fixed mysql table prefix in \"install.sql\" to deal with custom table prefix. If \"facebook_joomla_connect\" was wrongly prefixed in joomla table list, rename it with own table prefix.<br />
							1.3 -> Fixed Undefined variable: session, access_token.<br />
							1.4 -> Auto link option for users with same email address in Facebook and Joomla, small fixes.<br />
							1.5 -> Added disable random string, small code modification<br />
							1.5U -> Space in some usernames fixed, Small Fix in backend, included Fancy FB login module*<br />
							1.6A -> Security Fix<br />
							1.7 -> Fixed Module links, added login/logout notification system, publish on user wall on registration <br />";
							
$document =& JFactory::getDocument();					
$document->addStyleSheet('components/com_fbjconnect/assets/fbbackend.css');
jimport( 'joomla.application.component.helper' );
jimport('joomla.html.pane');
JToolBarHelper::preferences( 'com_fbjconnect',400,570 );
JToolBarHelper::title( JText::_( 'Facebook Graph Connect '.$componentversion ),'facebook_c' );




$pane =& JPane::getInstance('tabs', array('startOffset'=>0)); 

############################ panel 1 ################################
echo $pane->startPane( 'pane' );
echo $pane->startPanel( 'Instructions', 'panel1' );
$instructions ='
<div style="float:right;margin-left:20px">
<fieldset><legend>Donate</legend>
<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="saaraan@gmail.com">
<input type="hidden" name="item_name" value="fbconnect_com">
<input type="hidden" name="currency_code" value="USD">
<table width="200" border="0">
  <tr>
    <td>USD</td>
    <td><input type="text" name="amount" value=""></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="image" src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" style="border:none" border="0" name="submit"></td>
  </tr>
</table>
</form></fieldset>
</div>
<h3>For Support, Bug Reporting and Instructions visit <a href="http://www.sikkimonline.info/forums/viewforum.php?f=83" target="_new">Support form</a></h3>
<ol>
  <li>Make sure your host supports supports <a href="http://www.php.net/manual/en/intro.curl.php" target="_blank">PHP curl</a>, Most hosts support this.</li>
  <li>Download and Extract UNZIP-FIRST-jfbgconnect-XXX-j1.5.zip, it contains 2 files, com_fbjconnect.zip and mod_jfbgconnect.zip</li>
  <li>Install both files in your Joomla Administration CP</li>
  <li><a href="http://developers.facebook.com/setup/" target="_blank"><b><u>Setup Application</u></b></a> in facebook for your website.</li>
  <li>When finished, Copy <b><u>App ID</u></b> and <b><u>App secret</u></b> from your facebook app settings. <a href="components/com_fbjconnect/facebookappid.gif" target="_new"><b>Click here</b></a> to see what it looks like.</li>
  <li>Find and Click <b>Parameters</b> on this page -->, and enter your <b><u>app ID</u></b> and <b><u>App Secret.</u></b> </li>
  <li>In <b>Module Manager</b>, <b><u>ENABLE</u></b> <b>FB Graph Connect Login Module</b> on your website sidebar, Don\'t forget to select <b><u>ALL PAGES</u></b> in <b>Module Assignment</b>!</li>
</ol>

Your users should be able to use fb graph connect by now, but optionally you might want to add: <b>xmlns:fb="http://www.facebook.com/2008/fbml"</b> Within the <b>&lt;html&gt;</b> tag in your template, if following facebook tags do not work properly.<br />
Once all done, you should be able to put following facebook tag anywhere in your article, where you have fb module enabled. <u>Remember Tiny Editor and few other editors are known to strip html or fbml tags, so it is better to disable them before writting these codes in your articles.</u>
<ul>
	<li>&lt;fb:like&gt;&lt;/fb:like&gt; - Facebook Like Button</li>
	<li>&lt;fb:comments&gt;&lt;/fb:comments&gt; - Add Facebook Comments to Any Page</li>
	<li>&lt;fb:activity&gt;&lt;/fb:activity&gt; - Facebook Activity Feed</li>
	<li>&lt;fb:live-stream&gt;&lt;/fb:live-stream&gt; - Facebook users can share activiy comments </li>
	<li>&lt;fb:recommendations&gt;&lt;/fb:recommendations&gt; - A Facebook Likes for your domain</li>
	<li>&lt;fb:facepile&gt;&lt;/fb:facepile&gt; - Facebook users friends photos</li>
</ul>

<br /> Dont forget to click <b>test facebook</b> tab to see everything\'s working';

echo $instructions;
echo $pane->endPanel();


############################ panel 2 ################################
echo $pane->startPanel( 'Test Facebook', 'panel2' );

require_once( JPATH_ROOT.DS.'components'.DS.'com_fbjconnect'.DS.'inc'.DS.'facebook.php' );
$myparams 		= &JComponentHelper::getParams('com_fbjconnect');
$appidentered 	= (!$myparams->get('appid',0))? '<div>App ID Entered : <strong style="color:red">No</strong></div>':'';
$appsecretenter = (!$myparams->get('appsecret',0))? '<div>App Secret Entered <strong style="color:red">No</strong></div>' :'';
$installedcurl = (iscurlinstalled())? '<b>cURL</b> is : <span style="color:green"><b>Enabled</b></span> in this Server!' : '<b>cURL</b> <span style="color:red"><b>NOT Found</b></span>. cCURL is needed to run facebook!';

echo "<fieldset>".$appidentered.$appsecretenter.$installedcurl."</fieldset>";
if(!$myparams->get('appid') || $myparams->get('appsecret')=="")
	{
		echo '<p>Enter your facebook App ID and App secret on parameters fields, login to facebook <br />
		and refresh this page to see facebook graph working! <br />
		<img src="components/com_fbjconnect/facebookappid.gif" width="501" height="442"></p>';
	}

if($myparams->get('appid',0)>0 && $myparams->get('appsecret')!="")
	{
		$facebook = new Facebook(array('appId'  => $myparams->get('appid',0),'secret' => $myparams->get('appsecret',0),'cookie' => true,));
		$session = $facebook->getSession(); $loginurl = $facebook->getLoginUrl(); $logouturl = $facebook->getLogoutUrl();
		$me = null;

		  try 	{
					$sarannn = $facebook->api('/saranCham');
					$uid = $facebook->getUser(); $me = $facebook->api('/me');
					
				} 
			catch (FacebookApiException $e) {
				if($e)
				{
					if(trim($e) == 'OAuthException: An active access token must be used to query information about the current user.')
					{
						echo '<br />Login to facebook to see it working!<br /><br />';
					}else{
						echo '<br /><fieldset style="color:red">';
						echo $e;
						echo '<div>Try connecting to facebook with connect button below, <b>if not working --></b> App secret entered may be wrong, Please check once again, make sure everything is right!</div>';
						echo '</fieldset>';
					}
				}
		  } 
		
	}
	
$logouturl= (isset($logouturl))?$logouturl:"";
$loginurl= (isset($loginurl))?$loginurl:"";

if($me)
	{
		echo '<a href="'.$logouturl.'"><img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif"></a>
		<h3>Yay!!! facebook works in your php enviroment, Congratulations!</h3>
		<h3>Session</h3><pre>';
		print_r($session);
		echo '</pre>
		<h3>You</h3>
		<img src="https://graph.facebook.com/'.$uid.'/picture">
		'.$me['name'].'
		<h3>Your User Object</h3>
		<pre>';
		print_r($me);
		echo '</pre>';
	}
	else
	{
		echo '<strong><em>You are not Connected. Or not logged in to facebook</em></strong><br />';
		if($myparams->get('appid')){
		echo '<a href="'.$loginurl.'"><img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif"></a><br />';
	}
echo '<br /><a href="http://www.facebook.com/saranCham" target="_blank"><img src="https://graph.facebook.com/saranCham/picture"></a><br />['.$sarannn["first_name"].']<br />This is my public data, you should see my name in square bracket once you enter your app id and secret. Add me if you want!';

}
echo $pane->endPanel();


############################ panel 3 ################################
echo $pane->startPanel( 'Statistics', 'panel3' );
$lastregusers = $myparams->get('list-no-of-reg-users',10);
$db =& JFactory::getDBO();
$query = "SELECT COUNT(*) FROM #__users LEFT JOIN #__facebook_joomla_connect ON #__users.id=#__facebook_joomla_connect.joomla_userid WHERE #__facebook_joomla_connect.linked=1";
$db->setQuery($query);
echo "Total Connected users " .$db->loadResult();
echo "<hr >";
$query = "SELECT #__users.id, #__users.username, #__users.name, #__users.email, #__facebook_joomla_connect.facebook_userid, #__facebook_joomla_connect.joined_date 	
    FROM #__users LEFT JOIN #__facebook_joomla_connect ON #__users.id=#__facebook_joomla_connect.joomla_userid WHERE #__facebook_joomla_connect.linked=1 ORDER BY #__users.id DESC LIMIT $lastregusers";
$db->setQuery($query);
$rows = $db->loadRowList();
echo '<br />Last 10 Registered users with Facebook Connect</br ><table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="16%"><strong>ID</strong></td>
	 <td width="16%"><strong>Username</strong></td>
    <td width="16%"><strong>Full Name</strong></td>
    <td width="16%"><strong>Email</strong></td>
    <td width="16%"><strong>Facebook ID</strong></td>
	 <td width="16%"></td>
  </tr>';
$bg = 0;
if($rows)
{
	foreach($rows as $row)
	{
	$bgcolor = ($bg==1)? '#FFFFAE':'#DDFFFF';
	echo '<tr bgcolor="'.$bgcolor.'"><td width="16%"><a href="index.php?option=com_users&view=user&task=edit&cid[]='.$row[0].'">'.$row[0].'</a></td><td width="16%">'.$row[1].'</td><td width="16%">'.$row[2].'</td><td width="16%">'.$row[3].'</td><td width="16%"><a href="http://www.facebook.com/profile.php?id='.$row[4].'" target="_new">'.$row[4].'</a></td><td width="16%">Registered '.time_since($row[5]).' ago.</td></tr>';
	$bg=($bg==1)? 0:1;
	}
}
echo '</table>';
echo $pane->endPanel();


############################ panel 3 ################################
echo $pane->startPanel( 'Feedbacks', 'panel4' );
echo '<h3>Thanks for your Feedbacks here</h3>
<script type="text/javascript"><!--
google_ad_client = "pub-0052126645916042";
/* 468x60, created 6/3/08 */
google_ad_slot = "4332477367";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=209116209104091&amp;xfbml=1"></script>
<fb:comments href="http://www.sikkimonline.info/joomla-facebook-graph-connect" num_posts="20" width="500"></fb:comments>
';
echo $pane->endPanel();

############################ panel 4 ################################
echo $pane->startPanel( 'Updates', 'panel5' );
$returned_content = get_data('http://www.sikkimonline.info/joomla_updates/index.php');
echo $returned_content;
function get_data($url)
{
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
//echo '<iframe src="http://www.sikkimonline.info/joomla_updates" width="700" height="500" scrolling="no" style="border:1px"></iframe>';
echo $pane->endPanel();

############################ panel 5 ################################
echo $pane->startPanel( 'About', 'panel6' );
$donationbox = '<p><div style="float:right;margin:10px">
<script type="text/javascript"><!--
google_ad_client = "pub-0052126645916042";
/* 200x200, created 8/27/10 */
google_ad_slot = "3401766077";
google_ad_width = 200;
google_ad_height = 200;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<h3>Joomla Facebook Graph Connect '.$componentversion.'</h3>
<div>Version is '.$componentversion.', Check <a href="http://www.sikkimonline.info/fbgconnect-download/category/1-sikkimonline-downloads" target="_new">Latest version.</a></div><br />
Did this component help you? I have spent endless hours developing this component, <br />If you feel this has helped you, 
Please don\'t forget to Donate and keep fire burning ;).<br />
<fieldset><legend>Donate</legend>
<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="saaraan@gmail.com">
<input type="hidden" name="item_name" value="fbconnect_com">
<input type="hidden" name="currency_code" value="USD">
<table width="200" border="0">
  <tr>
    <td>USD</td>
    <td><input type="text" name="amount" value=""></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="image" src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" style="border:none" border="0" name="submit"></td>
  </tr>
</table>
</form></fieldset>
<br /><hr />
<h3>Changelog</h3>'.$changelog;
echo $donationbox;
echo $pane->endPanel();
echo $pane->endPane();


############################ functions ################################
function time_since($original) {
$chunks = array(array(60 * 60 * 24 * 365 , 'year'),array(60 * 60 * 24 * 30 , 'month'),array(60 * 60 * 24 * 7, 'week'),
array(60 * 60 * 24 , 'day'),array(60 * 60 , 'hour'),array(60 , 'minute'),);
$today = time();
$since = $today - $original;
for ($i = 0, $j = count($chunks); $i < $j; $i++) {
$seconds = $chunks[$i][0];
$name = $chunks[$i][1];
if (($count = floor($since / $seconds)) != 0) {
	break;
	}
}
	$print = ($count == 1) ? '1 '.$name : "$count {$name}s";
	if ($i + 1 < $j) 
		{
		$seconds2 = $chunks[$i + 1][0];
		$name2 = $chunks[$i + 1][1];
		if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
		}
	}
	return $print;
}

function iscurlinstalled() {
	if  (in_array  ('curl', get_loaded_extensions())) {
		return true;
	}
	else{
		return false;
	}
}