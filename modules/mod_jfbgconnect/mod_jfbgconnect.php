<?php
/**
* @package 		FacebookGraphConnect for joomla 1.5
* @copyright	Copyright (C) Computer - http://www.sikkimonline.info. All rights reserved.
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* @author		Saran Chamling (saaraan@gmail.com)
* @download URL	http://www.sikkimonline.info/joomla-facebook-graph-connect
*/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$language = JFactory::getLanguage();
$language->load('mod_fbjconnect');
// Include the syndicate functions only once
if(JRequest::getVar('option')!='com_fbjconnect')
{

require_once (JPATH_ROOT.DS.'components'.DS.'com_fbjconnect'.DS.'inc'.DS.'facebook.php');
require_once (JPATH_ROOT.DS.'components'.DS.'com_fbjconnect'.DS.'fbgccontroller.php');
require_once( dirname(__FILE__).DS.'helper.php' );
$list =  modjfbgconnector::pullItems($params);
require(JModuleHelper::getLayoutPath('mod_jfbgconnect'));
}

?>