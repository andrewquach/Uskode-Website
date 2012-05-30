<?php
/**
* @version		2.7
* @copyright	Copyright (C) 2007-2011 Stephen Brandon
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JElementGeoipcheck extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Geoipcheck';

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $mosConfig_absolute_path;
		$files = $this->geoIPFolders();
		$foundcountry = $foundlitecity = $foundcity = false;
		$messages = array();
		
		foreach ($files as $file) {
			$proposed_file = JPATH_SITE.DS.$file.'GeoIP.dat';
			$country = is_file($proposed_file) && is_readable($proposed_file);
			$proposed_file = JPATH_SITE.DS.$file.'GeoLiteCity.dat';
			$litecity = is_file($proposed_file) && is_readable($proposed_file);
			$proposed_file = JPATH_SITE.DS.$file.'GeoIPCity.dat';
			$city = is_file($proposed_file) && is_readable($proposed_file);
			
			if ($country && !$foundcountry) {
				$age = intval((time() - filemtime(JPATH_SITE.DS.$file.'GeoIP.dat'))/(24*60*60));
				if ($age > 30) $age = "<span style='color:red;'>" . JText::sprintf("File is %d days old. Please update your database from %s.", $age,
					"<a href='http://www.maxmind.com/app/ip-location' target='_blank'>MaxMind</a>") . "</span>";
				else $age = "";
				$messages[] = $file . JText::_("GeoIP.dat found. GeoIP Country features enabled.") . " $age";
				$foundcountry = true;
			}
			if ($litecity && !$foundlitecity) {
				$age = intval((time() - filemtime(JPATH_SITE.DS.$file.'GeoLiteCity.dat'))/(24*60*60));
				if ($age > 30) $age = "<span style='color:red;'>" . JText::sprintf("File is %d days old. Please update your database from %s.", $age,
					"<a href='http://www.maxmind.com/app/ip-location' target='_blank'>MaxMind</a>") . "</span>";
				else $age = "";
				$messages[] = $file . JText::_("GeoLiteCity.dat found. GeoIP City/region features enabled.") . " $age";
				$foundlitecity = true;
			}
			if ($city && !$foundcity) {
				$age = intval((time() - filemtime(JPATH_SITE.DS.$file.'GeoIPCity.dat'))/(24*60*60));
				if ($age > 30) $age = "<span style='color:red;'>" . JText::sprintf("File is %d days old. Please update your database from %s.", $age,
					"<a href='http://www.maxmind.com/app/ip-location' target='_blank'>MaxMind</a>") . "</span>";
				else $age = "";
				$messages[] = $file . JText::_("GeoIPCity.dat found. GeoIP City/region features enabled.") . " $age";
				$foundcity = true;
			}
			
		}
		if ($foundcountry || $foundlitecity || $foundcity) {
			return "<b>".implode("<br/>",$messages).'</b>
			<br />' . JText::sprintf("Keep your GeoIP databases up to date from %s",
				'<a href="http://www.maxmind.com/app/ip-location" target="_blank">MaxMind</a>');
		}
		return JText::_('GEOIP_DOWNLOAD_HELPTEXT');
	}
	
	function geoIPFolders() {
		return array(
			"administrator".DS."components".DS."com_chameleon".DS."geoip".DS,
			"administrator".DS."components".DS."com_metatemplate".DS."geoip".DS,
			"geoip".DS,
			"GeoIP".DS,
			"geoIP".DS,
			"GEOIP".DS,
			"GEO IP".DS,
			"",
			"geo_ip".DS,
			"geo_IP".DS,
			"Geo_IP".DS
			);
		
	}
	
}