<?php
/**
* @version		2.7
* @copyright	Copyright (C) 2007-2011 Stephen Brandon
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JElementMetamodhelp extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Metamodhelp';

	function fetchElement($name, $value, &$node, $control_name)
	{
				return JText::_('Create some PHP code to determine which module should be used. Once the script has decided which module or modules to display, return the module id or module position name, a comma-separated list of module ids (and/or module position names) as a string, or an array of module ids and/or module position names. For more help and recipes, see the MetaMod home page. e.g.') .
'<pre>
if ( MM_NOT_LOGGED_IN ) return 65;
if ( MM_LOGGED_IN )
     return "advert1";// ' . JText::_('all modules in "advert1" position') .'
if ( MM_DAY_OF_WEEK == 1 )
     return 67; // ' . JText::_('Monday is &quot;1&quot;') .'
if ( MM_DAY_OF_MONTH == 1 )
     return 68; // ' . JText::_('only show on 1st day of every month') .'
if ( MM_MONTH == 5 )
     return 69; // ' . JText::_('only show during month of May') .'
if ( MM_YEAR == 2010 )
     return "70,user2"; // ' . JText::_('only show during year 2010') .'
if ( $core_genius->inTimeSpan( "22:25 - 02:25" ) )
     return 71; // ' . JText::_('21:30 to 03:25 daily') .'
if ( $core_genius->inTimeSpan( "09:30:10 - 17:15:00" ) )
     return 72; // ' . JText::_('09:30:10 to 17:15:00 daily') .'
if ( MM_DATE >= 20090101 && MM_DATE <= 20090723)
     return 73; // ' . JText::_('1 Jan 2009 to 23 July 2009') .'

if ( $fromCountryId == "US" ) return 55;
if ( $fromCountryId == "GB" ) return "55,56,57";
if ( $fromCountryId == "NL" ) return array(58,59,73);
if ( $fromCountryName == "New Zealand" ) return "user1";</pre>
		<p>' . JText::_('You have access to the following PHP variables:') .'
			<ul><li><b>$fromCountryId</b> - ' . JText::_('the upper-case 2-letter ISO country code (e.g. GB, US, FR, DE)') .'</li>
			<li><b>$fromCountryName</b> - ' . JText::_('the official ISO country name') .'</li>
			<li><b>$geoip</b> - ' . JText::_('if you have enabled GeoLiteCity or GeoIPCity, a record containing the following items:') .'</li>
			<ul>
			<li><b>$geoip-&gt;country_name</b> - ' . JText::_('full country name, as above') .'</li>
			<li><b>$geoip-&gt;country_code</b> - ' . JText::_('2-letter ISO country code, as above') .'</li>
			<li><b>$geoip-&gt;country_code3</b> - ' . JText::_('3-letter ISO country code (e.g. GBR, USA, FRA, DEU)') .'</li>
			<li><b>$geoip-&gt;region</b> - ' . JText::_('2-letter code. For US/Canada, ISO-3166-2 code for the state/province name, e.g. &quot;GA&quot; (Georgia, USA). Outside of the US and Canada, FIPS 10-4 code, e.g. &quot;M9&quot; (Staffordshire, UK)') .'</li>
			<li><b>$geoip-&gt;city</b> - ' . JText::_('full city name') .'</li>
			<li><b>$geoip-&gt;postal_code</b> - ' . JText::_('For US, Zipcodes; for Canada, postal codes. Available for about 56% of US GeoIP Records. More info.') . '</li>
			<li><b>$geoip-&gt;latitude</b></li>
			<li><b>$geoip-&gt;longitude</b></li>
			<li><b>$geoip-&gt;area_code</b> - ' . JText::_('3-digit telephone prefix (US Only)') .'</li>
			<li><b>$geoip-&gt;metro_code</b> - ' . JText::_('3-digit Metro code (US only)') .'</li>
			<li><b>$geoip-&gt;continent_code</b> - ' . JText::_('continent_code') .'</li>
			</ul>
			<li><b>$Itemid</b> - ' . JText::_('the Itemid of the main component on the page') .'</li>
			<li><b>$option</b> - ' . JText::_('the option of the main component on the page (e.g. com_content)') .'</li>
			<li><b>$view</b> - ' . JText::_('the view of the main component on the page (e.g. article)') .'</li>
			<li><b>$id</b> - ' . JText::_('the id of the item in the main component on the page (e.g. 24:content-layouts)') .'</li>
			<li><b>$db</b> - ' . JText::_('in case you want to query the database for anything (for experts!)') .'</li>
			<li><b>$language</b> - ' . JText::_('a lower-case language code. By default this returns the default language of the web visitor&rsquo;s browser, but can alternatively return the language code of the Joomla front-end, or intelligently find the best match between a user&rsquo;s browser languages and a list of languages that you provide. Typical language strings returned include: en, en-gb, en-us, fr, de and many others.') .'</li>
			<li><b>$language_code</b> - ' . JText::_('the 2-letter language code without region (lower case) e.g. en') .'</li>
			<li><b>$language_region</b> - ' . JText::_('if it exists, the 2-letter region code (lower case). e.g. if $language is en-us, $languagecode is en and $languageregion is us. Having them in separate variables like this makes it easier to put into MetaMod rules.') .'</li>
			<li><b>$user</b> - ' . JText::_('information about the user, if they are logged in....') .'</li>
			<ul>
			<li><b>$user-&gt;id</b> - ' . JText::_('If 0, the user is not logged in') . '</li>
			<li><b>$user-&gt;name</b></li>
			<li><b>$user-&gt;username</b></li>
			<li><b>$user-&gt;email</b></li>
			<li><b>$user-&gt;usertype</b> - ' . JText::_('e.g. &quot;&quot; or &quot;Public Frontend&quot; means not logged in (test for both), otherwise &quot;Registered&quot;, &quot;Author&quot;, &quot;Editor&quot;, &quot;Publisher&quot;, &quot;Manager&quot;, &quot;Administrator&quot; or &quot;Super Administrator&quot;') .'</li>
			<li><b>$user-&gt;registerDate</b> - ' . JText::_('e.g. &quot;2007-05-17 01:25:52&quot;') .'</li>
			<li><b>$user-&gt;lastvisitDate</b> - ' . JText::_('e.g. &quot;2007-11-02 18:51:29&quot;') .'</li>
			</ul>
			
			</ul>
			</p>
			<p>
			' . JText::_('Note: $fromCountryName and $fromCountryId will only be available if you have one of the Enable GeoIP options selected above, and if you have one of the GeoLite Country, GeoIP Country, GeoLiteCity or GeoIPCity databases installed (see Maxmind, direct GeoLite Country download, or direct GeoLite City download)') .'
			</p>';
	}
}