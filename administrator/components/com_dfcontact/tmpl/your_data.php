<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_DFCONTACT_TAB_YOUR_DATA' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:23em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<?php
		$counter = 0;
		$dfcontact["field"] = ( !empty( $dfcontact["field"] ) && is_array( $dfcontact["field"] ) ? $dfcontact["field"] : array() );
		reset( $dfcontact["field"] );
		while( list( $key, $value ) = each( $dfcontact["field"] ) ) {
			if ( $key == "checkbox" || $key == "message" ) {
				continue;
			}
			if ( in_array( $key, array( 'phone', 'skype', 'message' ) ) ) {
				echo "<tr><td colspan='3'><hr style='border: 1px solid #CCCCCC;' /></td></tr>";
			}
		?>
			<tr>
				<td class="key"><?php echo JText::_( 'COM_DFCONTACT_' . strtoupper( $key ) ) . ":" ?></td>
				<td>
				<input type="text" name="dfcontact[field][<?php echo $key; ?>][value]" id="dfcontact[field][<?php echo $key; ?>][value]" value="<?php echo ( !empty( $value["value"] ) ? htmlspecialchars( $value["value"] ) : "" ); ?>" size="30">
				<?php
				if ($key == 'geocoordinates') {
				?>
				<script type="text/javascript">
				  /***
				  This implementation of the MyGeoPosition.com GeoPicker is exclusively developed for the Joomla Admin Area.
				  It makes sure, that the GeoPicker is NOT running in the administration area javascript context.
				  Please DO NOT copy&paste/reuse this implementation, as it might change without prior notice.
				  For information on a regular implementation, please check  http://api.mygeoposition.com.
				  ***/
				  function lookupGeoData() {
					var fields = ['dfcontact[field][street][value]', 'dfcontact[field][streetno][value]', 'dfcontact[field][zip][value]', 'dfcontact[field][city][value]', 'dfcontact[field][state][value]', 'dfcontact[field][country][value]'];
					var startAddress = '';
					for (var i = 0; i < fields.length; i++) {
						if (document.getElementById(fields[i])) {
							startAddress += document.getElementById(fields[i]).value + ',';
						}
						startAddress.replace(/&/g, "%26");
						startAddress.replace(/=/g, "%3D");
					}		            
					var geopickerUrl = 'http://api.mygeoposition.com/api/geopicker/id-dfcontactpipm/?startAddress=' + escape(startAddress) + '&returnUrl=' + escape(document.location.href);
					var mgpGeoWindow = window.open("", "MGPGeoPickerWindow", "width=488,height=518,location=no,menubar=no,resizable=no,status=no,toolbar=no");
					mgpGeoWindow.focus();
					mgpGeoWindow.document.write("<" + "html><" + "head><title>GeoPicker</title></" + "head><" + "body style=\"padding:0px;margin:0px;\">");
					mgpGeoWindow.document.write("<iframe src=\"" + geopickerUrl + "\" width=488 height=518 border=0 frameborder=0 style=\"padding:0px;margin:0px;\"></iframe>");
					mgpGeoWindow.document.write("<script type=\"text/javascript\">");
					mgpGeoWindow.document.write("function receiveMessage(event) {");
					mgpGeoWindow.document.write(" if(event.origin.match(/mygeoposition\.com$/g)) {");
					mgpGeoWindow.document.write("  window.opener.document.getElementById('dfcontact[field][geocoordinates][value]').value = event.data.replace(/[^0-9\.,-]/g, '');");
					mgpGeoWindow.document.write("  window.close();");
					mgpGeoWindow.document.write(" }");
					mgpGeoWindow.document.write("}");
					mgpGeoWindow.document.write("if (window.addEventListener) { window.addEventListener('message', receiveMessage, false);}");
					mgpGeoWindow.document.write("else {window.attachEvent('onmessage', receiveMessage);}");
					mgpGeoWindow.document.write("</" + "script>");
					mgpGeoWindow.document.write("</" + "body></" + "html>");					
				  }
				  if (window.postMessage && !navigator.userAgent.match(/Opera\/9/g)) {
					  document.write('<button type="button" onclick="lookupGeoData();"><?php echo JText::_( 'COM_DFCONTACT_GEOPICKER'); ?></button>');
				  }
				</script>				
				<?php
				}
				?>
				</td>
				<?php
				echo ($counter++ == 0 ? "<td align=\"left\" valign=\"top\" rowspan=\"" . sizeof($dfcontact["field"]) . "\">" . JText::_( 'COM_DFCONTACT_TAB_YOUR_DATA_INFO') . "</td>" : "");
				?>
				<td></td>
			</tr>
		<?php
		}
		?>
		</tbody>
		</table>
</fieldset>