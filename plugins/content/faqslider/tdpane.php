<?php
/**
 * @version		$Id: pane.php 10707 2008-08-21 09:52:47Z eddieajau $ - Extended by Matt Faulds Trafalgar Design www.trafalgardesign.com
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * TDPane abstract class
 *
 * @abstract
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
class TDPane extends JObject
{

	var $useCookies = false;

	/**
	* Constructor
	*
 	* @param	array	$params		Associative array of values
	*/
	function __construct( $params = array() )
	{
	}

	/**
	 * Returns a reference to a TDPanel object
	 *
	 * @param	string 	$behavior   The behavior to use
	 * @param	boolean	$useCookies Use cookies to remember the state of the panel
	 * @param	array 	$params		Associative array of values
	 * @return	object
	 */
	function &getInstance( $behavior = 'Tabs', $params = array())
	{
		$classname = 'TDPane'.$behavior;
		$instance = new $classname($params);

		return $instance;
	}

	/**
	 * Creates a pane and creates the javascript object for it
	 *
	 * @abstract
	 * @param	string	The pane identifier
	 */
	function startPane( $id )
	{
		return;
	}

	/**
	 * Ends the pane
	 *
	 * @abstract
	 */
	function endPane()
	{
		return;
	}

	/**
	 * Creates a panel with title text and starts that panel
	 *
	 * @abstract
	 * @param	string	$text The panel name and/or title
	 * @param	string	$id The panel identifer
	 */
	function startPanel( $text, $id )
	{
		return;
	}

	/**
	 * Ends a panel
	 *
	 * @abstract
	 */
	function endPanel()
	{
		return;
	}

	/**
	 * Load the javascript behavior and attach it to the document
	 *
	 * @abstract
	 */
	function _loadBehavior()
	{
		return;
	}
}

/**
 * TDPanelTabs class to draw parameter panes
 *
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
class TDPaneTabs extends TDPane
{
	/**
	 * Constructor
	 *
	 * @param	array 	$params		Associative array of values
	 */
	function __construct( $params = array() )
	{
		static $loaded = false;

		parent::__construct($params);

		if (!$loaded) {
			$this->_loadBehavior($params);
			$loaded = true;
		}
	}

	/**
	 * Creates a pane and creates the javascript object for it
	 *
	 * @param string The pane identifier
	 */
	function startPane( $id )
	{
		return '<dl class="tabs" id="'.$id.'">';
	}

	/**
	 * Ends the pane
	 */
	function endPane()
	{
		return "</dl>";
	}

	/**
	 * Creates a tab panel with title text and starts that panel
	 *
	 * @param	string	$text	The name of the tab
	 * @param	string	$id		The tab identifier
	 */
	function startPanel( $text, $id )
	{
		return '<dt id="'.$id.'"><span>'.$text.'</span></dt><dd>';
	}

	/**
	 * Ends a tab page
	 */
	function endPanel()
	{
		return "</dd>";
	}

	/**
	 * Load the javascript behavior and attach it to the document
	 *
	 * @param	array 	$params		Associative array of values
	 */
	function _loadBehavior($params = array())
	{
		// Include mootools framework
		JHTML::_('behavior.mootools');

		$document =& JFactory::getDocument();

		$options = '{';
		$opt['onActive']		= (isset($params['onActive'])) ? $params['onActive'] : null ;
		$opt['onBackground'] = (isset($params['onBackground'])) ? $params['onBackground'] : null ;
		$opt['display']		= (isset($params['startOffset'])) ? (int)$params['startOffset'] : null ;
		foreach ($opt as $k => $v)
		{
			if ($v) {
				$options .= $k.': '.$v.',';
			}
		}
		if (substr($options, -1) == ',') {
			$options = substr($options, 0, -1);
		}
		$options .= '}';

		$js = '		window.addEvent(\'domready\', function(){ $$(\'dl.tabs\').each(function(tabs){ new JTabs(tabs, '.$options.'); }); });';

		$document->addScriptDeclaration( $js );
		$document->addScript( JURI::root(). 'plugins/content/faqslider/tabs.js' );
	}
}

/**
 * TDPanelSliders class to to draw parameter panes
 *
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
class TDPaneSliders extends TDPane
{
	/**
	 * Constructor
	 *
	 * @param int useCookies, if set to 1 cookie will hold last used tab between page refreshes
	 */
	function __construct( $params = array() )
	{
		static $loaded = false;

		parent::__construct($params);

		if(!$loaded) {
			$this->_loadBehavior($params);
			$loaded = true;
		}
	}

	/**
	 * Creates a pane and creates the javascript object for it
	 *
	 * @param string The pane identifier
	 */
	function startPane( $id )
	{
		return '<div id="'.$id.'" class="pane-sliders">';
	}

    /**
	 * Ends the pane
	 */
	function endPane() {
		return '</div>';
	}

	/**
	 * Creates a tab panel with title text and starts that panel
	 *
	 * @param	string	$text - The name of the tab
	 * @param	string	$id - The tab identifier
	 */
	function startPanel( $text, $id )
	{
		return '<div class="panel">'
			.'<h3 class="jpane-toggler title" id="'.$id.'"><span>'.$text.'</span></h3>'
			.'<div class="jpane-slider content">';
	}

	/**
	 * Ends a tab page
	 */
	function endPanel()
	{
		return '</div></div>';
	}

	/**
	 * Load the javascript behavior and attach it to the document
	 *
	 * @param	array 	$params		Associative array of values
	 */
	function _loadBehavior($params = array())
	{
		// Include mootools framework
		JHTML::_('behavior.mootools');

		$document =& JFactory::getDocument();
		
		if($params['native']) {

			$options = '{';
			$opt['onActive']	 = 'function(toggler, i) { toggler.addClass(\'jpane-toggler-down\'); toggler.removeClass(\'jpane-toggler\'); }';
			$opt['onBackground'] = 'function(toggler, i) { toggler.addClass(\'jpane-toggler\'); toggler.removeClass(\'jpane-toggler-down\'); }';
			$opt['duration']	 = (isset($params['duration'])) ? (int)$params['duration'] : 300;
			$opt['display']		 = (isset($params['startOffset']) && ($params['startTransition'])) ? (int)$params['startOffset'] : null ;
			$opt['show']		 = (isset($params['startOffset']) && (!$params['startTransition'])) ? (int)$params['startOffset'] : null ;
			$opt['opacity']		 = (isset($params['opacityTransition']) && ($params['opacityTransition'])) ? 'true' : 'false' ;
			$opt['alwaysHide']	 = (isset($params['allowAllClose']) && ($params['allowAllClose'])) ? 'true' : null ;
			foreach ($opt as $k => $v)
			{
				if ($v) {
					$options .= $k.': '.$v.',';
				}
			}
			if (substr($options, -1) == ',') {
				$options = substr($options, 0, -1);
			}
			$options .= '}';
	
			$js = '		window.addEvent(\'domready\', function(){ new Accordion($$(\'.panel h3.jpane-toggler\'), $$(\'.panel div.jpane-slider\'), '.$options.'); });';
		} else {
			$refocus = '';
			$expColl = '';
			
			if($params['refocus']) {
				$refocus = ",
							onComplete: function(request){ 
								var open = request.getStyle('margin-top').toInt();
								if(open >= 0) new Fx.Scroll(window).toElement(headings[i]);
							}";
			}
			if($params['expandCollapse']) {
				$expColl = "
					
					$('collapse-all').onclick = function(){
						headings.each( function(heading, i) {
							collapsibles[i].hide();
							if(heading, '.jpane-toggler-down') {
								heading.addClass('jpane-toggler').removeClass('jpane-toggler-down');
							}
						});
						return false;
					}
					
					$('expand-all').onclick = function(){
						headings.each( function(heading, i) {
							collapsibles[i].show();
							if(heading, '.jpane-toggler') {
								heading.addClass('jpane-toggler-down').removeClass('jpane-toggler');
							}
						});
						return false;
					}";
			}
			
			$js = "
			var Site = {vertical: function(){
					var list = $$('.panel div.jpane-slider');
					var headings = $$('.panel h3.jpane-toggler');
					var collapsibles = new Array();
					
					headings.each( function(heading, i) {
	
						var collapsible = new Fx.Slide(list[i], { 
							duration: 500, 
							transition: Fx.Transitions.linear$refocus
						});
						
						collapsibles[i] = collapsible;
						
						heading.onclick = function(){
							var clicked = heading.hasClass('jpane-toggler');
							if(clicked) {
								heading.addClass('jpane-toggler-down').removeClass('jpane-toggler');
							} else {
								heading.addClass('jpane-toggler').removeClass('jpane-toggler-down');
							}
							
							collapsible.toggle();
							return false;
						}
						
						collapsible.hide();
						
					});$expColl
					
				}
			}	
			window.addEvent('domready', Site.vertical);";
		}
		$document->addScriptDeclaration( $js );
	}
}