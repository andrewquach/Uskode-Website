<?php
/**
* @version		2.7
* @copyright	Copyright (C) 2007-2011 Stephen Brandon
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// the version below MUST be kept up to date with the version defined in plugins/mod_metamod/JomGenius.class.php.
define( 'JOMGENIUS_MM_REQUIRED_VERSION', 5 );

class JElementMminfo extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Mminfo';

	function fetchElement($name, $value, &$node, $control_name) {
		$r = '';
		
		$r .= $this->metamodInfo();

		$r .= $this->metamodProInfo();
		
		$r .= $this->jomGeniusInfo();
		
		$r .= $this->donationInfo();
			
		return $r;
	}
	
	function metamodInfo() {
		$parser =& JFactory::getXMLParser('Simple');
		// define the path to the XML file
		$pathToXML_File = JPATH_SITE.DS.'modules'.DS.'mod_metamod'. DS.'mod_metamod.xml';
		// parse the XML
		$parser->loadFile($pathToXML_File);
		$document =& $parser->document;
		// get the version tag
		$version =& $document->version[0];
		$date =& $document->creationDate[0];
		
		$r = '<div>';
		$r .= JText::sprintf( 'MetaMod v%s, %s', $version->data(), $date->data());
		$r .= '</div>';
		return $r;
	}

	function metamodProInfo() {
		
		$db 	=& JFactory::getDBO();
		$query	= 'SELECT published from #__plugins where element = \'metamod\' and folder = \'system\'';
		$db->setQuery( $query );
		$result	= $db->loadResult();
		// $result will be "" or "0" or "1"
		
		switch( $result ) {
			case '':
			return '<div>' . JText::_('MetaMod Pro is not installed') . '</div>';
			
			case '0':
				$message = JText::_('MetaMod Pro plugin v%s, %s is installed but not enabled. Please enable it using the Plugin Manager.');
				break;
			case '1':
				$message = JText::_('MetaMod Pro plugin v%s, %s is installed and enabled.');
				break;
		}
		
		$parser =& JFactory::getXMLParser('Simple');
		// define the path to the XML file
		$pathToXML_File = JPATH_SITE.DS.'plugins'.DS.'system'. DS.'metamod.xml';
		// parse the XML
		$parser->loadFile($pathToXML_File);
		$document =& $parser->document;
		
		// get the version tag
		$version =& $document->version[0];
		$date =& $document->creationDate[0];
		
		$r = '<div>';
		$r .= JText::sprintf( $message, $version->data(), $date->data() );
		$r .= '</div>';
		return $r;
	}
	
	function jomGeniusInfo() {
		$r = '<div>';
		$db 	=& JFactory::getDBO();
		$query	= 'SELECT element from #__plugins where published = 1 and element = \'metatemplate\'';
		$db->setQuery( $query );
		$result	= $db->loadResult();

		if ( $result == 'metatemplate' ) {
			if (! defined( 'JOMGENIUS_MT_PROVIDED_VERSION' ) or JOMGENIUS_MT_PROVIDED_VERSION < JOMGENIUS_MM_REQUIRED_VERSION ) {
				// we want a certain version but MetaTemplate hasn't been upgraded and looks like it's loaded
				$r .= JText::_('JomGenius support is being provided by an out-of-date copy of MetaTemplate or MetaTemplate Pro. Please update your copies at www.metamodpro.com' );
			} else if ( defined( 'JOMGENIUS_MT_PROVIDED_VERSION' ) and JOMGENIUS_MT_PROVIDED_VERSION >= JOMGENIUS_MM_REQUIRED_VERSION ) {
				$r .= JText::sprintf( 'JomGenius v%s is provided by %s', JOMGENIUS_MT_PROVIDED_VERSION, 'MetaTemplate / MetaTemplate Pro' );
			}
		}
		else {
			include_once dirname(__FILE__) . DS . '..' . DS . 'mod_metamod' . DS . 'JomGenius.class.php';
			$r .= JText::sprintf('JomGenius v%s is provided by %s', JOMGENIUS_VERSION, 'MetaMod');
		}
		$r .= '</div>';
		
		return $r;
	}
	
	function donationInfo() {
		// donation
		$r = '
		<a href="http://www.metamodpro.com/donate.php" target="_blank"><img src="https://www.paypal.com/en_GB/i/btn/btn_donate_SM.gif" border="0" alt="' . JText::_("Donate with PayPal").'" title="' . JText::_('Donate with PayPal - support further development of MetaMod!') . '" /></a>
			' . JText::_("Make a donation &mdash; support further development of MetaMod!");
		return $r;
	}
}