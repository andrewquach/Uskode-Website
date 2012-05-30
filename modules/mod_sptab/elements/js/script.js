/*---------------------------------------------------------------
# SP Tab - Next generation tab module for joomla
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
-----------------------------------------------------------------*/

jQuery.noConflict();

jQuery(document).ready(function(){
	showhide();	

	jQuery('#paramsstyle, #paramsbody_height').change(function() {showhide()});
	jQuery('#paramsstyle, #paramsbody_height').blur(function() {showhide()});	
	
	function showhide(){
		if (jQuery("#paramsstyle").val()=="raw" || jQuery("#paramsstyle").val()=="custom") {
			jQuery("#paramscolor").parent().parent().css("display", "none");
		} else {
			jQuery("#paramscolor").parent().parent().css("display", "");		
		}	
		if (jQuery("#paramsstyle").val()=="raw" || jQuery("#paramsstyle").val()!="custom") {
			jQuery('.sp-input').parent().parent().css("display", "none");
		} else {
			jQuery('.sp-input').parent().parent().css("display", "");		
		}
		if (jQuery("#paramsbody_height").val()=="1") {
			jQuery("#paramsfixed_height").parent().parent().css("display", "none");
		} else {
			jQuery("#paramsfixed_height").parent().parent().css("display", "");		
		}
		updateHeight.delay();		
	}
	
	function updateHeight() {
		jQuery('.jpane-slider').first().css("height", "auto");	
	}
	updateHeight.delay(300);		
});