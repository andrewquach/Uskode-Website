<?php
/*------------------------------------------------------------------------
# mod_coverflowfx.php - Cover Flow FX
# ------------------------------------------------------------------------
# author    FlashXML.net
# copyright Copyright (C) 2011 flashxml.net. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.flashxml.net
# Technical Support:  Forum - http://www.flashxml.net/cover-flow.html#comments
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');

$login_text = $params->get('login_text');
$login_link = base64_encode($params->get('login_url'));

$logout_text = $params->get('logout_text');
$logout_link = base64_encode($params->get('logout_url'));

$user = JFactory::getUser();
$userId	= (int) $user->get('id');

require(JModuleHelper::getLayoutPath('mod_logoutlink'));