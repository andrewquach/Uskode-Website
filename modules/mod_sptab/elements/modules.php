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
 
class JElementModules extends JElement
{
	 
	var	$_name = 'Modules';

	function fetchElement($name, $value, &$node, $control_name)
	{
	
        // Construct an array of the HTML OPTION statements.
        $options = array ();
        // creating database instance	
        $db =& JFactory::getDBO();
        // generating query
		$db->setQuery("SELECT id, module, title FROM #__modules WHERE ( published !=-2 AND published !=0 ) AND client_id=0 AND module != 'mod_mainmenu' ORDER BY id ASC");
 		// getting results
   		$results = $db->loadObjectList();
		
		foreach ($results as $option)
		{
			$options[] = JHTML::_('select.option', $option->id, $option->title);
		}
		
		$output= JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $value );
		
		return $output;		
	}
} 