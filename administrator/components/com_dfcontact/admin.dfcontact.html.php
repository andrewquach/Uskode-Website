<?php
/**
* DFContact - A Joomla! contact form component
* @version 1.5
* @package DFContact
* @copyright (C) 2007 by Daniel Filzhut
* @license Released under the terms of the GNU General Public License
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package DFContact
*/
class HTML_dfcontact {

	function show( $option, $dfcontact) {
		
		//Load switcher behavior
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.switcher');
		
		// Build the component's submenu
		$contents = '';
		$tmplpath = dirname(__FILE__) . DS . 'tmpl';
		ob_start();
		require_once( $tmplpath . DS . 'navigation.php' );
		$contents = ob_get_contents();
		ob_end_clean();

		// Set document data
		$document =& JFactory::getDocument();
		$document->setBuffer($contents, 'modules', 'submenu');
		
		?>
		
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.getElementById('adminForm');
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			submitform( pressbutton );
		}
		</script>
		
		<form action="index.php?option=com_dfcontact" method="post" name="adminForm" id="adminForm">

		<?php
		
			// Get languages
			$dfLangs = array_keys(JLanguage::getKnownLanguages(JPATH_SITE));			
			
			// Get default frontend language
			$dfJConfig = &JFactory::getConfig(JPATH_CONFIGURATION);
			$dfLangDefaultSite = $dfJConfig->getValue('config.language');
			define('DFCONTACT_LANGUAGE_DEFAULT_SITE', $dfLangDefaultSite);
			unset($dfLangButtons);
			
			// Create buttons
			$dfLangButtons = '';
			foreach ($dfLangs as $lang) {
				$dfLangButtons .= "<button type=\"button\" onclick=\"dfcontactSwitchLanguage('$lang')\" class=\"dfcontactLangButton dfcontactLangButton-$lang\" style=\"margin-right:0;\">$lang</button>";
			}
			$dfLangButtons = "<p style=\"clear:both;position:relative;top:-11px;\">$dfLangButtons</p>";
			define('DFCONTACT_LANGUAGE_BUTTONS', $dfLangButtons);
			unset($dfLangButtons);
		
		?>

		<div id="config-document">
		
			<div id="page-general" class="tab">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'general.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-your_data" class="tab">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'your_data.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-form_fields" class="tab">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'form_fields.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-address_format" class="tab">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'address_format.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-about" class="tab">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'about.php'); ?>
					</td>
				</tr>
				</table>
			</div>
			
		</div>

		<div class="clr"></div>
		
		<input type="hidden" name="task" value="" />
		
		<script type="text/javascript">

			/* Switch to specified language */
			function dfcontactSwitchLanguage(lang) {
				dfcontactToggleLanguageFields('none');
				dfcontactToggleLanguageFields('block', lang);
			}

			/* Toggle a languages visibility */
			function dfcontactToggleLanguageFields(action, lang) {
				fieldsInput = $$(lang ? '.dfcontactLangField-' + lang : '.dfcontactLangField');
				fieldsButtonAll = $$('.dfcontactLangButton');
				fieldsButtonLang = $$('.dfcontactLangButton-' + lang);
				for (i = 0; i < fieldsInput.length; i++) {
					fieldsInput[i].style.display = action;
				}
				for (i = 0; i < fieldsButtonAll.length; i++) {
					fieldsButtonAll[i].style.fontWeight = 'normal';
				}
				for (i = 0; i < fieldsButtonLang.length; i++) {
					fieldsButtonLang[i].style.fontWeight = 'bold';
				}
			}

			/* Hide all language fields */
			dfcontactToggleLanguageFields('none');
			
			/* Show current language */
			dfcontactToggleLanguageFields('block', '<?php echo DFCONTACT_LANGUAGE_DEFAULT_SITE; ?>');
			
		</script>
		
		</form>
		
		<?php
	}

}

?>