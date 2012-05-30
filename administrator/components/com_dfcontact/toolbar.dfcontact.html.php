<?php
/**
* DFContact - A Joomla! contact form component
* @version 1.5
* @package DFContact
* @copyright (C) 2007 by Daniel Filzhut
* @license Released under the terms of the GNU General Public License
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_dfcontact {

	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'DFContact' ));
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel('cancel', 'Close');
	}
}

?>
