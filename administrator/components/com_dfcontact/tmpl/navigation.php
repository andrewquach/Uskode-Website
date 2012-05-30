<?php
if ( defined('JVERSION') && substr(JVERSION, 0, 3) == '1.6') {
?>
<div id="submenu-box">
	<div class="t">
		<div class="t">
			<div class="t"></div>
		</div>
	</div>
	<div class="m">	
<?php
}
?>

		<div class="submenu-box">
			<div class="submenu-pad">
				<ul id="submenu">
					<li>
						<a id="general" class="active">
							<?php echo JText::_( 'COM_DFCONTACT_TAB_GENERAL' ); ?></a>
					</li>
					<li>
						<a id="your_data">
							<?php echo JText::_( 'COM_DFCONTACT_TAB_YOUR_DATA' ); ?></a>
					</li>
					<li>
						<a id="form_fields">
							<?php echo JText::_( 'COM_DFCONTACT_TAB_FORM_FIELDS' ); ?></a>
					</li>
					<li>
						<a id="address_format">
							<?php echo JText::_( 'COM_DFCONTACT_TAB_ADDRESS_FORMAT' ); ?></a>
					</li>
					<li>
						<a id="about">
							<?php echo JText::_( 'COM_DFCONTACT_TAB_ABOUT' ); ?></a>
					</li>
				</ul>
				<div class="clr"></div>
			</div>
		</div>
		<div class="clr"></div>
		
<?php
if ( defined('JVERSION') && substr(JVERSION, 0, 3) == '1.6') {
?>
	</div>
	<div class="b">
		<div class="b">
			<div class="b"></div>
		</div>
	</div>
</div>
<?php
}
?>