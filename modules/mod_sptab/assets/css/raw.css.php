<?php
/*------------------------------------------------------------------------
# SP Tab - Tab Module for Joomla by JoomShaper.com
# ------------------------------------------------------------------------
# author    JoomShaper http://www.joomshaper.com
# copyright Copyright (C) 2010 JoomShaper.com. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
# This file may not be redistributed in whole or significant part
-------------------------------------------------------------------------*/
header("Content-Type: text/css");
$uniqid = $_GET['id'];
?>
#sptab<?php echo $uniqid ?> ul.tabs_container {list-style:none;margin: 0!important; padding: 0!important}
#sptab<?php echo $uniqid ?> .tabs_buttons{overflow:hidden}
#sptab<?php echo $uniqid ?> ul.tabs_container li.tab{float:left;cursor:pointer;padding:0 10px;margin:0}
#sptab<?php echo $uniqid ?> .items_mask {position:relative;overflow:hidden}