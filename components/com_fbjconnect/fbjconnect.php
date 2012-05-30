<?php
/**
* @package 		FacebookGraphConnect for joomla 1.5
* @copyright	Copyright (C) Computer - http://www.sikkimonline.info. All rights reserved.
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* @author		Saran Chamling (saaraan@gmail.com)
* @download URL	http://www.sikkimonline.info/joomla-facebook-graph-connect
*/

defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once( JPATH_COMPONENT.DS.'fbgccontroller.php' );
require_once( JPATH_COMPONENT.DS.'inc'.DS.'facebook.php' );
// Create the controller
$classname    = 'JfbgconnectController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getWord( 'task' ) );
JfbgconnectController::LogInUsers();
JfbgconnectController::LinkExistingUser();
JfbgconnectController::varAccNewCrt();
// Redirect if set by the controller
$controller->redirect();
?>