<?php
/*---------------------------------------------------------------
# SP Tab - Next generation tab module for joomla
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
-----------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JElementPicker extends JElement
{
	var	$_name = 'Picker';
	
	function fetchElement( $name, $value, &$node, $control_name ) {
		$doc = JFactory::getDocument();
		
		$picker = $control_name.$name.'_picker';
		$id = $control_name.$name;
		
		$doc->addScriptDeclaration ('jQuery(document).ready(function(){
		jQuery("#' . $picker . '").ColorPicker({
		color: "' . $value . '",
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery("#' . $picker . ' div").css("backgroundColor", "#" + hex);
			jQuery("#' . $id . '").val("#" + hex);
		}
	}); 
	});'
	);
	
	return '<input type="text" name="'.$control_name.'['.$name.']" id="' . $id . '" class="sp-input" value="' . $value . '" size="10" /><span id="' . $id . '_picker" class="picker-box"><div style="background-color: ' . $value . '"></div></span>';
	
	}
} 
?>