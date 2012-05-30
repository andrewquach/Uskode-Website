<?php
/**
* @version		2.7
* @copyright	Copyright (C) 2007-2011 Stephen Brandon
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JElementModulelist extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Modulelist';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$doc = & JFactory::getDocument();
		$doc->addStyleDeclaration( JElementModulelist::makeStyle() );
		$r  = '<div style="float:right; background-color:white; border:1px solid black; padding:3px;margin-bottom:3px;"><a id="mm_toggler" onclick="mm_toggle(this)" rel="false">' . JText::_('show disabled') . '</a></div>';
		$r .= '<div style="clear:both"></div>';
		$r .= '<div id="all-mod-lists">';
		$r .= JElementModulelist::makeList( true, 'title');
		$r .= JElementModulelist::makeList( false, 'title');
		$r .= '</div>';

$r .= '
	<script type="text/javascript" src="../modules/mod_metamod/elements/grid.js"></script>
	
		<script type="text/javascript">
			window.addEvent("domready", function(){ 
				new Grid($("modulelist-enabledonly"));
				new Grid($("modulelist"));
			});
			function mm_toggle( sender ) {
				sender = $(sender);
				if (sender.getProperty("rel") == "false") {
					sender.setProperty("rel","true");
					sender.setText("' . JText::_('hide disabled') . '");
					$("modulelist-enabledonly").setStyle("display","none");
					$("modulelist").setStyle("display","");
				} else {
					sender.setProperty("rel","false");
					sender.setText("' . JText::_('show disabled') . '");
					$("modulelist-enabledonly").setStyle("display","");
					$("modulelist").setStyle("display","none");
				}
			}
		</script>

';
		return $r;
	}
	
	function makeStyle() {
		return '
			table {}
			table#modulelist-enabledonly td, table#modulelist td
				{ padding: 1px 6px 1px 0;}
			tr.r1 {background-color: #e8e8e8; }
			table.modulelist th.sort { padding-right: 0.4em; padding-left:1px; font-size:110%;}
			table.modulelist td.modid { text-align:right; }
			table.modulelist th.modid { text-align:right; }
			table.modulelist th.hover { background-color:#d0ffd0; }
			div#all-mod-lists { height:390px; overflow:auto; background-color:white;}
			';
	}
	
	function makeList( $enabledonly = true, $order ) {
		$orderby = "title";
		switch( $order ) {
			case "title":
				$orderby = "title";
				break;
			case "titledesc":
				$orderby = "title desc";
				break;
			case "enabled":
				$orderby = "published, title";
				break;
			case "enableddesc":
				$orderby = "published desc, title";
				break;
			case "id":
				$orderby = "id";
				break;
			case "iddesc":
				$orderby = "id desc";
				break;
			case "type":
				$orderby = "module, title";
				break;
			case "typedesc":
				$orderby = "module desc, title";
				break;
		}
		$db = &JFactory::getDBO();
		
		$query = "SELECT id, title, module, position, published"
			. "\n FROM #__modules WHERE ";
		
		if ( $enabledonly ) {
			$query .= "\n published = 1 AND ";
		}
		$query .= "\n client_id != 1"
			. "\n ORDER BY $orderby"
		;
		
		$db->setQuery( $query );
		$options = $db->loadObjectList();
		
		$arrow = '../modules/mod_metamod/elements/updown.png';
		$tick  = 'images/tick.png';
		$cross = 'images/publish_x.png';

		$r = '<table id="modulelist' . ($enabledonly ? "-enabledonly" : '') . '" style="' . ($enabledonly ? '' : 'display:none;') .'" 
				class="modulelist" cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
		<tr>
		<th class="modid sort" axis="int" nowrap="nowrap">' . JText::_('id') . '&nbsp;<img src="'. $arrow .'" alt=""  /></th>
		<th class="sort" axis="string" nowrap="nowrap">' . JText::_('name') . '&nbsp;<img src="'. $arrow .'" alt=""  /></th>
		<th class="sort" axis="string" nowrap="nowrap">' . JText::_('type') . '&nbsp;<img src="'. $arrow .'" alt=""  /></th>
		<th class="sort" axis="string" nowrap="nowrap">' . JText::_('position') . '&nbsp;<img src="'. $arrow .'" alt=""  /></th>
		<th class="sort" axis="rel" nowrap="nowrap" style="padding-right:0">' . JText::_('MM_ENABLED') . '&nbsp;<img src="'. $arrow .'" alt=""  /></th>
		</tr>
		</thead>
		<tbody>';
		$counter = 0;
		foreach ($options as $o) {
			$counter = abs( --$counter );
			$r .= "<tr class=" . ($counter ? '"r1"' : '"r2"') .">\n";
			$r .= '<td class="modid"><b>'.$o->id.'</b></td>
				<td>'.$o->title.'</td>
				<td>'.$o->module.'</td>
				<td>'.$o->position.'</td>
				<td align="center" rel="' . ($o->published ? 0 : 1) . '"><img src="'.($o->published ? $tick : $cross).'" height="12" alt="#" /></td>';
			$r .= "</tr>\n";
		}
		$r .= "</tbody></table>";
		return $r;
	}
}