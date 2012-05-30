<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_DFCONTACT_EMAIL' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:23em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<tr>
			<td class="key"><?php echo JText::_( 'COM_DFCONTACT_DESTINATIONMAIL') . ":" ?></td>
			<td><input type="text" name="dfcontact[destinationMail]" value="<?php echo ( !empty( $dfcontact["destinationMail"] ) ? htmlspecialchars( $dfcontact["destinationMail"] ) : "" ); ?>"></td>
			<td><?php echo JText::_( 'COM_DFCONTACT_DESTINATIONMAIL_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'COM_DFCONTACT_HTMLMAILS') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[htmlMails]', 'class="inputbox"', ( !empty( $dfcontact["htmlMails"] ) ? htmlspecialchars( $dfcontact["htmlMails"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'COM_DFCONTACT_HTMLMAILS_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'COM_DFCONTACT_ADDSERVERDATA') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[addServerData]', 'class="inputbox"', ( !empty( $dfcontact["addServerData"] ) ? htmlspecialchars( $dfcontact["addServerData"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'COM_DFCONTACT_ADDSERVERDATA_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'COM_DFCONTACT_DISPLAYUSERINPUT') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[displayUserInput]', 'class="inputbox"', ( !empty( $dfcontact["displayUserInput"] ) ? htmlspecialchars( $dfcontact["displayUserInput"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'COM_DFCONTACT_DISPLAYUSERINPUT_INFO') ?></td>
		</tr>
		</tbody>
		</table>
</fieldset>

</td>
</tr>
<tr>
<td>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_DFCONTACT_SECURITY' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:23em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<tr>
			<td class="key"><?php echo JText::_( 'COM_DFCONTACT_CLICKABLELINKS') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[links]', 'class="inputbox"', ( !empty( $dfcontact["links"] ) ? htmlspecialchars( $dfcontact["links"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'COM_DFCONTACT_CLICKABLELINKS_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'COM_DFCONTACT_ONLINE_STATUS') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[onlineStatus]', 'class="inputbox"', ( !empty( $dfcontact["onlineStatus"] ) ? htmlspecialchars( $dfcontact["onlineStatus"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'COM_DFCONTACT_ONLINE_STATUS_INFO') ?></td>
		</tr>
		</tbody>
		</table>
</fieldset>

</td>
</tr>
<tr>
<td>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_DFCONTACT_CAPTCHA' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:23em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<tr>
			<td valign="top" class="key"><?php echo JText::_( 'COM_DFCONTACT_CAPTCHA') . ":" ?></td>			
			<td valign="top"><select name="dfcontact[captcha]" id="dfcontact[captcha]" onchange="toggleRecaptchaFields()">
			<option value="0">---</option>
			<?php
			$captchas = array('com_securityimages' => 'com_securityimages', 'recaptcha' => 'reCAPTCHA');
			while ( list($key, $value) = each($captchas) ) {
				echo '<option value="' . $key . '"' . ($dfcontact["captcha"] == $key ? ' selected="selected"' : '') . '>' . $value . '</option>';
			}
			?></select></td>		
			<td><?php echo JText::_( 'COM_DFCONTACT_CAPTCHA_INFO') ?></td>
		</tr>
		<tr id="recaptchaFieldTheme">
			<td valign="top" class="key"><?php echo JText::_( 'COM_DFCONTACT_CAPTCHA_RECAPTCHA_THEME') . ":" ?></td>
			<td valign="top"><select name="dfcontact[recaptcha_theme]" id="dfcontact[recaptcha_theme]" onchange="toggleRecaptchaThemeExample()">
			<?php
			$captchas = array('red' => 'red', 'white' => 'white', 'blackglass' => 'blackglass', 'clean' => 'clean');
			while ( list($key, $value) = each($captchas) ) {
				echo '<option value="' . $key . '"' . ($dfcontact["recaptcha_theme"] == $key ? ' selected="selected"' : '') . '>' . $value . '</option>';
			}
			?></select></td>		
			<td valign="top" rowspan="3"><img src="" alt="" id="dfcontactRecaptchaExample" style="height:100px" /></td>
		</tr>
		<tr id="recaptchaFieldPublicKey">
			<td class="key"><?php echo JText::_( 'COM_DFCONTACT_CAPTCHA_RECAPTCHA_PUBLIC_KEY') . ":" ?></td>
			<td><input type="text" name="dfcontact[recaptcha_public_key]" value="<?php echo ( !empty( $dfcontact["recaptcha_public_key"] ) ? htmlspecialchars( $dfcontact["recaptcha_public_key"] ) : "" ); ?>"></td>
		</tr>
		<tr id="recaptchaFieldPrivateKey">
			<td class="key"><?php echo JText::_( 'COM_DFCONTACT_CAPTCHA_RECAPTCHA_PRIVATE_KEY') . ":" ?></td>
			<td><input type="text" name="dfcontact[recaptcha_private_key]" value="<?php echo ( !empty( $dfcontact["recaptcha_private_key"] ) ? htmlspecialchars( $dfcontact["recaptcha_private_key"] ) : "" ); ?>"></td>
		</tr>
		</tbody>
		<script type="text/javascript">
			function toggleRecaptchaFields() {
				var active = document.getElementById('dfcontact[captcha]').options[document.getElementById('dfcontact[captcha]').selectedIndex].value;
				if (active == 'recaptcha') {
					document.getElementById('recaptchaFieldPublicKey').style.display = 'table-row';
					document.getElementById('recaptchaFieldPrivateKey').style.display = 'table-row';
					document.getElementById('recaptchaFieldTheme').style.display = 'table-row';
				} else {
					document.getElementById('recaptchaFieldPublicKey').style.display = 'none';
					document.getElementById('recaptchaFieldPrivateKey').style.display = 'none';
					document.getElementById('recaptchaFieldTheme').style.display = 'none';
				}
			}
			toggleRecaptchaFields();
			function toggleRecaptchaThemeExample() {
				var active = document.getElementById('dfcontact[recaptcha_theme]').options[document.getElementById('dfcontact[recaptcha_theme]').selectedIndex].value;
				if (active == 'blackglass') {
					active = 'black';
				}
				var url = 'http://code.google.com/intl/de-DE/apis/recaptcha/images/reCAPTCHA_Sample_' + active.substring(0, 1).toUpperCase() + active.substring(1).toLowerCase() + '.png';
				document.getElementById('dfcontactRecaptchaExample').src = url;
				
			}
			toggleRecaptchaThemeExample();
		</script>
		</table>
</fieldset>

</td>
</tr>
<tr>
<td>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_DFCONTACT_LAYOUT' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:23em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<tr>
			<td valign="top" class="key"><?php echo JText::_( 'COM_DFCONTACT_PAGETITLE') . ":" ?></td>
			<td valign="top">
				<?php
				foreach ($dfLangs as $lang) {
				?>
					<input class="dfcontactLangField dfcontactLangField-<?php echo $lang; ?>" type="text" name="dfcontact[pageTitle][<?php echo $lang; ?>]" value="<?php echo ( !empty( $dfcontact["pageTitle"][$lang] ) ? htmlspecialchars( $dfcontact["pageTitle"][$lang] ) : "" ); ?>">
				<?php
				}
				echo DFCONTACT_LANGUAGE_BUTTONS;
				?>
			</td>
			<td valign="top"><?php echo JText::_( 'COM_DFCONTACT_PAGETITLE_INFO') ?></td>
		</tr>
		<tr>
			<td valign="top" class="key"><?php echo JText::_( 'COM_DFCONTACT_INFOTEXT') . ":" ?></td>
			<td valign="top">
				<?php
				foreach ($dfLangs as $lang) {
				?>
					<textarea class="dfcontactLangField dfcontactLangField-<?php echo $lang; ?> inputbox" cols="30" rows="4" name="dfcontact[infoText][<?php echo $lang; ?>]"><?php echo ( !empty( $dfcontact["infoText"][$lang] ) ? htmlspecialchars( $dfcontact["infoText"][$lang] ) : "" ); ?></textarea>
				<?php
				}
				echo DFCONTACT_LANGUAGE_BUTTONS;
				?>
			</td>
			<td valign="top"><?php echo JText::_( 'COM_DFCONTACT_INFOTEXT_INFO') ?></td>
		</tr>
		<tr>
			<td valign="top" class="key"><?php echo JText::_( 'COM_DFCONTACT_FORMTEXT') . ":" ?></td>
			<td valign="top">
				<?php
				foreach ($dfLangs as $lang) {
				?>
					<textarea class="dfcontactLangField dfcontactLangField-<?php echo $lang; ?> inputbox" cols="30" rows="4" name="dfcontact[formText][<?php echo $lang; ?>]"><?php echo ( !empty( $dfcontact["formText"][$lang] ) ? htmlspecialchars( $dfcontact["formText"][$lang] ) : "" ); ?></textarea>
				<?php
				}
				echo DFCONTACT_LANGUAGE_BUTTONS;
				?>
			</td>
			<td valign="top"><?php echo JText::_( 'COM_DFCONTACT_FORMTEXT_INFO') ?></td>
		</tr>
		<tr>
			<td valign="top" class="key"><?php echo JText::_( 'COM_DFCONTACT_BUTTONTEXT') . ":" ?></td>
			<td valign="top">
				<?php
				foreach ($dfLangs as $lang) {
				?>
					<input class="dfcontactLangField dfcontactLangField-<?php echo $lang; ?>" type="text" name="dfcontact[buttonText][<?php echo $lang; ?>]" value="<?php echo ( !empty( $dfcontact["buttonText"][$lang] ) ? htmlspecialchars( $dfcontact["buttonText"][$lang] ) : "" ); ?>">
				<?php
				}
				echo DFCONTACT_LANGUAGE_BUTTONS;
				?>
			</td>
			<td valign="top"><?php echo JText::_( 'COM_DFCONTACT_BUTTONTEXT_INFO') ?></td>
		</tr>
		</tbody>
		</table>
</fieldset>

</td>
</tr>
</table>