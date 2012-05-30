<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_DFCONTACT_TAB_ADDRESS_FORMAT' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:8em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<tr>
		<td colspan="3"><?php echo JText::_('COM_DFCONTACT_TAB_ADDRESS_FORMAT_INFO'); ?></td>
		</tr>
		<tr>
		<td class="key"><?php echo JText::_('COM_DFCONTACT_TEMPLATE'); ?>:</td>
		<td colspan="2"><select name="dfcontact[addressFormat][selected]" id="dfcontact[addressFormat][selected]" onchange="dfSelectOption()">
		<option value="self"><?php echo JText::_('COM_DFCONTACT_SELF_DEFINED'); ?></option>
		<?php
		while (list($key, $value) = each($dfcontact['addressFormat']['templates'])) {
			if ($key != 'self') {
				echo '<option value="' . $key . '"' . ($dfcontact['addressFormat']['selected'] == $key ? ' selected="selected"' : '') . '>' . $key . '</option>';				}
		}
		?></select></td>
		</tr>
		<tr>
		<td class="key"></td>
		<td colspan="2"><textarea class="inputbox" cols="60" rows="10" name="dfcontact[addressFormat][templates][self]" id="dfcontact[addressFormat][templates][self]" onchange="document.getElementById('dfcontact[addressFormat][selected]').selectedIndex=0;"></textarea></td>
		</tr>
		<?php
		$desc = false;
		reset($dfcontact['field']);
		while (list($key, $value) = each($dfcontact['field'])) {
			echo "<tr>\n";
			if (!$desc) {
				echo "<td rowspan=\"" . (sizeof($dfcontact['field']) + 3) . "\" class=\"key\">" . JText::_('COM_DFCONTACT_PLACEHOLDER') . "</td>\n";
				$desc = true;
			}
			echo "<td class=\"key\" style=\"text-align:right;\">[" . strtoupper($key) . "]</td>\n";
			echo "<td class=\"key\" style=\"text-align:left;\">" . JText::_('COM_DFCONTACT_'.strtoupper($key)) . "</td>\n";
			echo "</tr>\n";
			
		}
		?>
		<tr>
		<td class="key" style="text-align:right;">[CAPTCHA]</td>
		<td class="key" style="text-align:left;"><?php echo JText::_('COM_DFCONTACT_CAPTCHA'); ?></td>
		</tr>
		<tr>
		<td class="key" style="text-align:right;">[MANDATORY]</td>
		<td class="key" style="text-align:left;"><?php echo JText::_('COM_DFCONTACT_FORM_MANDATORY'); ?></td>
		</tr>
		<tr>
		<td class="key" style="text-align:right;">[OTHER]</td>
		<td class="key" style="text-align:left;"></td>
		</tr>
		</tbody>
		</table>
</fieldset>

<script type="text/javascript">

// Standard addressFormats
var dfAddressFormats = new Array();
<?php
reset($dfcontact['addressFormat']['templates']);
while (list($key, $value) = each($dfcontact['addressFormat']['templates'])) {
	echo "dfAddressFormats['$key'] = '" .  str_replace("\n", '\n', str_replace("\r", '\r', $value)) . "';\n";
}
?>

// Fill with data
function dfSelectOption() {
	var dfElement1 = document.getElementById('dfcontact[addressFormat][selected]');
	var dfElement2 = document.getElementById('dfcontact[addressFormat][templates][self]');
	if (dfElement1 && dfElement2) {
		dfElement2.value = dfAddressFormats[dfElement1.options[dfElement1.selectedIndex].value];
	}
}

// Start
dfSelectOption();

</script>