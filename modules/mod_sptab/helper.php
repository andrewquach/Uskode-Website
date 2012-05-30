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

abstract class modspTabHelper {

    static function getTabs(&$params) {
		$mods		= $params->get('mods');
		$images		= $params->get('images');
		$options 	= array('style' => 'none');
		
		$items = array();

		for ($i=0;$i<count($mods);$i++) {
			$items[$i]->order 	= modspTabHelper::getModule($mods[$i])->ordering;
			$items[$i]->title 	= modspTabHelper::getModule($mods[$i])->title;
			$items[$i]->content = JModuleHelper::renderModule(  modspTabHelper::getModule($mods[$i]), $options);
		}
		
		return $items;
		
	}

	//load module by id
	function getModule( $id ){

		global $mainframe;
		$_db		=& JFactory::getDBO();		
		$_where = ' AND ( m.id='.$id.' ) ';

		$_query = 'SELECT *'.
			' FROM #__modules AS m'.
			' WHERE m.client_id = '.(int) $mainframe->getClientId().
			$_where.
			' ORDER BY ordering'.
			' LIMIT 1';

		$_db->setQuery( $_query );
		$module = $_db->loadObject();
						
		if (!$module) return null;
		
		$file				= $module->module;
		$custom				= substr($file, 0, 4) == 'mod_' ?  0 : 1;
		$module->user		= $custom;
		$module->name		= $custom ? $module->title : substr($file, 4);
		$module->style		= null;
		$module->position	= strtolower($module->position);
		$clean[$module->id]	= $module;		
		
		return $module;	
	}	
}