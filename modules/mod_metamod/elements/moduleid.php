<?php
/**
* @version		2.7
* @copyright	Copyright (C) 2007-2011 Stephen Brandon
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JElementModuleid extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Moduleid';

	function fetchElement($name, $value, &$node, $control_name) {
		$cid = JRequest::getVar('cid');
		if (is_array($cid)) {
			if (count($cid)) $cid = (int)$cid[0];
			else $cid = 0;
		} else $cid = (int) $cid;
		if ($cid == 0) $cid = JRequest::getInt('id',0);
		
		$r = '<input type="hidden" name="'.htmlentities($control_name).'['.htmlentities($name).']" value=' . $cid . '>';
		return $r;
	}
}