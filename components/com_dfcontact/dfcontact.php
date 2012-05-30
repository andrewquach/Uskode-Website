<?php
/**
* DFContact - A Joomla! contact form component
* @version 1.5
* @package DFContact
* @copyright (C) 2007 by Daniel Filzhut
* @license Released under the terms of the GNU General Public License
**/

// debugging
// if (empty( $_REQUEST["displayCaptcha"] )) {
// 	error_reporting(2047);
// 	ini_set("display_errors",1);
// }

// Get selected language
$dfJConfig = &JFactory::getConfig();
$langId = $dfJConfig->getValue('config.language');

// no direct access
defined('_JEXEC') or die('Restricted access');

// Load configuration data
include( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_dfcontact' . DS . 'config.dfcontact.php' );

# Display com_securityimages captcha
define('DFCONTACT_CAPTCHA_ENABLED' , !empty( $dfcontact['captcha'] ) && (
		( $dfcontact['captcha'] == 'com_securityimages' && file_exists( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_securityimages' ) )
		|| ($dfcontact['captcha'] == 'recaptcha' && file_exists( JPATH_SITE . DS . 'components' . DS . 'com_dfcontact' . DS . 'recaptcha' . DS . 'recaptchalib.php' ) )
	)
 );
if ( DFCONTACT_CAPTCHA_ENABLED && !empty( $_REQUEST["displayCaptcha"] ) ) {
	$check = null;
	$app = &JFactory::getApplication();
	$app->triggerEvent('onSecurityImagesDisplay', array($check)); 
	if (!$check) { 
		echo "Creation of captcha failed!"; 
	}
	exit;
}

# Display title
echo ( !empty( $dfcontact["pageTitle"] ) && !empty( $dfcontact["pageTitle"][$langId] ) ? "<div class=\"contentheading\">" . $dfcontact["pageTitle"][$langId] . "</div>\n\n" : "" );

# Start content
echo "<div class=\"contentpane\">\n\n";

# Check existance of destination mail
if ( empty( $dfcontact["destinationMail"] ) ) {
	echo '<p>' . JText::_( 'COM_DFCONTACT_FORM_NO_DESTINATION_MAIL') . "</p>\n\n";
}

# Start sending process if there is data
elseif ( !empty( $_REQUEST["submit"] ) ) {

	// create backlink
	$backlink = '<form action="" method="post">' . "\n";

	$dfcontact['field'] = ( is_array( $dfcontact['field'] ) ? $dfcontact['field'] : array() );
	reset( $dfcontact['field'] );
	while ( list( $key, $value ) = each( $dfcontact['field'] ) ) {
		if ( empty( $value['display'] ) ) {
			continue;
		}
		$backlink .= '<input type="hidden" name="' . htmlspecialchars( stripslashes( $key ) ) . '" value="' . ( !empty( $_REQUEST[$key] ) ? htmlspecialchars( stripslashes( $_REQUEST[$key] ) ) : '' ) . '" />' . "\n";
	}
	$backlink .= '<input type="hidden" name="submit" value="" />' . "\n";
	$backlink .= '<input type="submit" value="<< ' . JText::_( 'COM_DFCONTACT_FORM_BACK') . '" class="button" />' . "\n";
	$backlink .= '</form>' . "\n";

	# check for neccessary vars
	$missingFields = '';
	$dfcontact['field'] = ( !empty( $dfcontact['field'] ) && is_array( $dfcontact['field'] ) ? $dfcontact['field'] : array() );
	reset( $dfcontact['field'] );
	while ( list( $key, $value ) = each ( $dfcontact['field'] )) {
		if ( !empty( $value['display'] ) && !empty( $value['duty'] ) && empty( $_REQUEST[$key] ) ) {
			$missingFields .= '<li>' . JText::_( 'COM_DFCONTACT_' . strtoupper($key) ) . '</li>' . "\n";
		}
	}
	
	# captcha
	if (DFCONTACT_CAPTCHA_ENABLED) {
 		$captchaValid = null;
 		if ( $dfcontact['captcha'] == 'com_securityimages' ) {
	        $captchaTest = JRequest::getVar('captcha', false, '', 'CMD'); 
	        $app = &JFactory::getApplication();
	        $app->triggerEvent('onSecurityImagesCheck', array($captchaTest, &$captchaValid));
 		} else if ( $dfcontact['captcha'] == 'recaptcha' ) {
			require_once( JPATH_SITE . DS . 'components' . DS . 'com_dfcontact' . DS . 'recaptcha' . DS . 'recaptchalib.php' );
			$resp = recaptcha_check_answer ($dfcontact['recaptcha_private_key'],
                                $_SERVER["REMOTE_ADDR"],
                                $_REQUEST["recaptcha_challenge_field"],
                                $_REQUEST["recaptcha_response_field"] );
			$captchaValid = $resp->is_valid;
 		}
		if ( !$captchaValid ) {
			$missingFields .= '<li>' . JTEXT::_( 'COM_DFCONTACT_FORM_CAPTCHA_ERROR') . "</li>\n";			
		}
	}
	
	# display errors
	if ( $missingFields ) {
		echo '<p>' . JText::_( 'COM_DFCONTACT_FORM_MISSING_FIELDS') . '</p>' . "\n";
		echo "<ul>\n$missingFields</ul>\n";
		echo $backlink;
	} elseif ( !empty( $_REQUEST['email'] ) && !dfcontact_validMail( $_REQUEST['email'] ) ) {
		echo '<p>' . JText::_( 'COM_DFCONTACT_FORM_ENTER_VALID_MAIL') . '</p>' . "\n";
		echo $backlink;
	} else {

		# create html with user vars
		$sentVars = '<table border="0">';
		$dfcontact['field'] = ( is_array( $dfcontact['field'] ) ? $dfcontact['field'] : array() );
		reset( $dfcontact['field'] );
		while( list( $key, $value ) = each( $dfcontact['field'] )) {
			if ( empty( $value['display'] ) || ( empty( $_REQUEST[$key] ) && $key != 'checkbox' ) ) {
				continue;
			}
			$sentVars .= '<tr>';
			$sentVars .= '<th valign="top" align="left">' . JText::_( 'COM_DFCONTACT_' . strtoupper($key) ) . ':</th>' . "\n";
			$sentVars .= '<td valign="top">' . nl2br( htmlspecialchars( stripslashes( $key == 'checkbox' ? ( !empty( $_REQUEST[$key] ) && $_REQUEST[$key] ? JText::_( 'COM_DFCONTACT_YES') : JText::_( 'COM_DFCONTACT_NO') ) : ( !empty( $_REQUEST[$key]) ? $_REQUEST[$key] : '' ) ) ) ) . '</td>' . "\n";
			$sentVars .= '</tr>' . "\n";
		}
		$sentVars .= '</table>';

		# create html with server vars
		$serverVars = array(
			JText::_( 'COM_DFCONTACT_FORM_DATE') 		=> date( JText::_( 'COM_DFCONTACT_FORM_DATE_FORMAT') ),
			JText::_( 'COM_DFCONTACT_FORM_SENT_URL') 	=> (!empty($_SERVER['SERVER_NAME']) && !empty($_SERVER['REQUEST_URI']) ? '<a href="' . 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '" target="_blank">' . 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '</a>' : ''),
			JText::_( 'COM_DFCONTACT_FORM_USERAGENT')	=> (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''),
			JText::_( 'COM_DFCONTACT_FORM_HOST')		=> (!empty($_SERVER['REMOTE_NAME']) ? $_SERVER['REMOTE_NAME'] : @gethostbyaddr( (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '') )),
			JText::_( 'COM_DFCONTACT_FORM_IP') 		=> (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''),
			JText::_( 'COM_DFCONTACT_FORM_PORT')		=> (!empty($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : ''),
		);
		$sentVarsHidden = '<table border="0">';
		reset( $serverVars );
		while( list( $key, $value ) = each( $serverVars ) ) {
			$sentVarsHidden .= '<tr>';
			$sentVarsHidden .= '<th valign="top" align="left">' . ucfirst( nl2br( htmlspecialchars( stripslashes( $key ) ) ) ) . ':</th>' . "\n";
			$sentVarsHidden .= '<td valign="top">' . nl2br( stripslashes( $value ) ) . '</td>' . "\n";
			$sentVarsHidden .= '</tr>' . "\n";
		}
		$sentVarsHidden .= '</table>';

		# create html part
		$html = "<html>\n";
		$html .= "<style type=\"text/css\">\n";
		$html .= "<!--\n";
		$html .= "body,td,th,p{font-family:verdana;font-size:12px;}\n";
		$html .= "//-->\n";
		$html .= "</style>\n";
		$html .= "<body>\n";
		$html .= '<p>' . JText::_( 'COM_DFCONTACT_FORM_MAIL_TEXT') . '</p>' . "\n\n";
		$html .= "$sentVars\n";
		if ( !empty( $dfcontact['addServerData'] ) ) {
			$html .= '<p style="margin-top:2em;">' . JText::_( 'COM_DFCONTACT_FORM_MAIL_TEXT_SERVER_VARS') . '</p>' . "\n\n";
			$html .= "$sentVarsHidden\n";
		}
		$html .= "</body>\n";
		$html .= "</html>";
		
		# create text part
		$text = trim( strip_tags( $html ) );
		$text = str_replace("\n ", "\n", $text);
		$text = "\n" . $text;
		
		function escapeSubject($string, $prefix="=?UTF-8?Q?", $postfix="?=")    {
	        $crlf    = "\n\t";
	        $string  = preg_replace('!(\r\n|\r|\n)!', $crlf, $string) . $crlf ;
	        $f[]    = '/([\000-\010\013\014\016-\037\075\177-\377])/e' ;
	        $r[]    = "'=' . sprintf('%02X', ord('\\1'))" ;
	        $f[]    = '/([\011\040])' . $crlf . '/e' ;
	        $r[]    = "'=' . sprintf('%02X', ord('\\1')) . '" . $crlf . "'" ;
	        $string  = preg_replace($f, $r, $string);
	        return $prefix.trim(wordwrap($string, 70 - strlen($prefix) - strlen($postfix), ' ' . $postfix . $crlf . $prefix, true)).$postfix;
    	}	    
    
		# header vars
		$name = preg_replace('/[\r\n\t\f]/', '', ( !empty( $_REQUEST['name'] ) ? $_REQUEST['name'] : '' ) );
		$email = preg_replace('/[\r\n\t\f]/', '', ( !empty( $_REQUEST['email'] ) ? $_REQUEST['email'] : '' ) );
		$subject = ( function_exists( 'html_entity_decode') ? html_entity_decode( JText::_( 'COM_DFCONTACT_FORM_MAIL_SUBJECT'), ENT_COMPAT, "UTF-8" ) : JText::_( 'COM_DFCONTACT_FORM_MAIL_SUBJECT') ) . ' (' . preg_replace('/[\r\n\t\f]/', '', $_SERVER['SERVER_NAME']) . ')';
		$subject = escapeSubject($subject);
		$name = escapeSubject($name);
		
		# load internal mail class
		jimport('joomla.mail.mail');
		$mail = new JMail();
		$app = &JFactory::getApplication();
		switch ( $app->getCfg('mailer') ) {
			case 'smtp':
				$mail->useSMTP( $app->getCfg('smtpauth'), $app->getCfg('smtphost'), $app->getCfg('smtpuser'), $app->getCfg('smtppass') );
				break;
			case 'sendmail':
				$mail->useSendmail( $app->getCfg('sendmail') );
				break;
			default:
				break;
		}
		
		# Fill mail object with data
		$destinationEmails = preg_split('/[ ,;]/', $dfcontact['destinationMail']);
		foreach ( $destinationEmails as $destinationMail ) {
			if ( $destinationMail != '' ) {
				$mail->addRecipient( $destinationMail );
			}
		}
		$mail->setSender( array( $app->getCfg('mailfrom'), $app->getCfg('fromname') ) );
		if ( !empty( $email ) ) {
			$mail->addReplyTo( array($email, $name) );
		}
		$mail->setSubject( $subject );
		
		# Set mail body
		if ( !empty( $dfcontact['htmlMails'] ) ) {
			$mail->isHTML( true );
			$mail->Body = $html;
			$mail->AltBody = $text;
		} else {
			$mail->Body = $text;			
		}
		
		# Try to send email and display result
		$sendResult = $mail->Send();
		if ( is_bool($sendResult) && $sendResult == true ) {
			echo '<p class="dfContactSubmitSuccess">' . JText::_( 'COM_DFCONTACT_FORM_MAIL_SUCCESS') .  '</p>' . "\n";
			if ( !empty( $dfcontact['displayUserInput'] ) ) {
				echo '<p class="dfContactSubmitVars">' . JText::_( 'COM_DFCONTACT_FORM_MAIL_SUBMITTED_VARS') . '</p>' . "\n";
				echo $sentVars;
			}
		} else {
			echo '<p class="dfContactSubmitError">' . JText::_( 'COM_DFCONTACT_FORM_MAIL_ERROR') . '</p>' . "\n";
			echo $backlink;
		}
	}
} else {

	// info text
	if ( !empty( $dfcontact['infoText'] ) && !empty( $dfcontact['infoText'][$langId] ) ) {
		echo '<p class="dfContactInfoText" style="padding-top:15px;font-size:15px;font-weight:bold;">' . $dfcontact['infoText'][$langId] . '</p>' . "\n";
	}
	
	echo '<form action="" method="post" onsubmit="return dfcontact_checkForm();" id="dfContactForm" name="dfContactForm">' . "\n";
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"dfContactTable\">\n";
		
	// address
	ob_start();
	$addressFormat = trim( $dfcontact['addressFormat']['templates'][$dfcontact['addressFormat']['selected']] );
	$addressFormatSplit = preg_split('/\n/', $addressFormat);
	$lastCellEmpty = false;
	reset($addressFormatSplit);
	while (list($key, $line) = each($addressFormatSplit)) {
		
		// extract placeholders and get template string
		$placeholders = array();
		if (!preg_match_all('/\[([^\]]*)\]/', $line, $placeholders)) {
			if (!$lastCellEmpty) {
				$lastCellEmpty = true;
				echo "<tr><td>" . trim($line) . "</td></tr>\n";
			}
			continue;
		}
		$placeholders = $placeholders[1];
		$line = preg_replace("/\{[^\}]*\}/", '', $line);
		
		// keys
		$keyCell = '';
		$nonDisplayableKeys = array(
			'company',
			'title',
			'name',
			'position',
			'addition',
			'street',
			'streetno',
			'postbox',
			'zip',
			'city',
			'state',
			'country',
		);
		$keys = array();
		foreach ($placeholders as $ph) {
			$ph = strtolower($ph);
			if (!in_array($ph, $nonDisplayableKeys)) {
				array_push($keys, trim(JText::_('COM_DFCONTACT_'.strtoupper($ph))));
			}
		}
		$keyCell = join('/', $keys);		
		
		// values
		foreach ($placeholders as $ph) {
			
			$item = '';
			if (!empty($dfcontact['field'][strtolower($ph)]['value'])) {
				$item = trim($dfcontact['field'][strtolower($ph)]['value']);
			}
			
			// special fields
			if ($item) {
				switch ($ph) {
					case 'EMAIL':
						$item = ( !empty( $dfcontact['links']) ?  '<a href="mailto:' . $dfcontact['field']['email']['value'] . '">' . dfcontact_asciiEncodeString( $dfcontact['field']['email']['value'] ) . '</a>' : dfcontact_asciiEncodeString( $dfcontact['field']['email']['value'] ) );
						break;
					case 'ICQ':
						$item = ( !empty( $dfcontact['links']) ? '<a href="http://web.icq.com/whitepages/message_me?uin=' . $dfcontact['field']['icq']['value'] . '&action=message" target="_blank">' . dfcontact_asciiEncodeString( $dfcontact['field']['icq']['value'] ) . '</a>' : dfcontact_asciiEncodeString( $dfcontact['field']['icq']['value'] ) ) . ( !empty($dfcontact['onlineStatus']) ? ' <img src="http://status.icq.com/online.gif?web=' . $dfcontact['field']['icq']['value'] . '&img=5" align="absmiddle">' : '');
						break;
					case 'AIM':
						$item = ( !empty( $dfcontact['links'] ) ? '<a href="aim:goim?screenname=' . $dfcontact['field']['aim']['value'] . '&message">' . dfcontact_asciiEncodeString(  $dfcontact['field']['aim']['value'] ) . '</a>' : dfcontact_asciiEncodeString(  $dfcontact['field']['aim']['value'] ) );
						break;
					case 'YAHOO':
						$item = ( !empty( $dfcontact['links']) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $dfcontact['field']['icq']['value'] . '&.src=pg" target="_blank">' . dfcontact_asciiEncodeString( $dfcontact['field']['yahoo']['value'] ) . '</a>' : dfcontact_asciiEncodeString( $dfcontact['field']['yahoo']['value'] ) ) . ( !empty($dfcontact['onlineStatus']) ? ' <img border=0 src="http://opi.yahoo.com/online?u=' . $dfcontact['field']['yahoo']['value'] . '&m=g&t=0&l=us"></a>' : '');
						break;
					case 'MSN':
						$item = dfcontact_asciiEncodeString(  $dfcontact['field']['msn']['value'] );
						break;
					case 'SKYPE':
						$item = ( !empty( $dfcontact['links']) ? '<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script><a href="skype:' . $dfcontact['field']['skype']['value'] . '" onclick="return skypeCheck();">' . dfcontact_asciiEncodeString( $dfcontact['field']['skype']['value'] ) . '</a>' : dfcontact_asciiEncodeString( $dfcontact['field']['skype']['value'] ) ) . ( !empty($dfcontact['onlineStatus']) ? ' <img src="http://mystatus.skype.com/smallicon/' . $dfcontact['field']['skype']['value'] . '" style="border: none;" width="16" height="16" alt="My status" align="absmiddle" /></a>' : '');
						break;
					case 'LINKEDIN':
						$item = ( !empty( $dfcontact['links'] ) ? '<a href="http://www.linkedin.com/profile/view?id=' . $dfcontact['field']['linkedin']['value'] . '">' . dfcontact_asciiEncodeString(  $dfcontact['field']['linkedin']['value'] ) . '</a>' : dfcontact_asciiEncodeString(  $dfcontact['field']['linkedin']['value'] ) );
						break;
					case 'XING':
						$item = ( !empty( $dfcontact['links'] ) ? '<a href="http://www.xing.com/profile/' . $dfcontact['field']['xing']['value'] . '">' . dfcontact_asciiEncodeString(  $dfcontact['field']['xing']['value'] ) . '</a>' : dfcontact_asciiEncodeString(  $dfcontact['field']['xing']['value'] ) );
						break;
					case 'FACEBOOK':
						$item = ( !empty( $dfcontact['links'] ) ? '<a href="http://www.facebook.com/' . $dfcontact['field']['facebook']['value'] . '">' . dfcontact_asciiEncodeString(  $dfcontact['field']['facebook']['value'] ) . '</a>' : dfcontact_asciiEncodeString(  $dfcontact['field']['facebook']['value'] ) );
						break;
					case 'GOOGLEPLUS':
						$item = ( !empty( $dfcontact['links'] ) ? '<a href="http://plus.google.com/' . $dfcontact['field']['googleplus']['value'] . '">' . dfcontact_asciiEncodeString(  $dfcontact['field']['googleplus']['value'] ) . '</a>' : dfcontact_asciiEncodeString(  $dfcontact['field']['googleplus']['value'] ) );
						break;
					case 'TWITTER':
						$item = ( !empty( $dfcontact['links'] ) ? '<a href="http://twitter.com/#!/' . $dfcontact['field']['twitter']['value'] . '">' . dfcontact_asciiEncodeString(  $dfcontact['field']['twitter']['value'] ) . '</a>' : dfcontact_asciiEncodeString(  $dfcontact['field']['twitter']['value'] ) );
						break;
				}
			}
			
			$line = str_replace("[$ph]", $item, $line);
		}
		$valueCell = trim($line);
		$valueCellClean = str_replace(array('.',',',';'), "", $valueCell);
		
		// display line
		if ( !empty( $valueCellClean ) ) {
			$lastCellEmpty = false;
			echo "<tr>\n";
			echo '<th valign="top">' . $keyCell . "&nbsp;</th>\n";
			echo '<td valign="top">' . $valueCell . "</td>\n";
			echo "</tr>\n";
		}
		
	}
	
	// prevent empty last row
	$emptyLine = '<tr></tr>';
	$buffer = trim(ob_get_contents());
	if (substr($buffer, strlen($buffer) - strlen($emptyLine)) == $emptyLine) {
		$buffer = substr($buffer, 0, strlen($buffer) - strlen($emptyLine));
	}
	ob_end_clean();
	echo $buffer;
	echo '<tr></tr>' . "\n";

	// form text
	if ( !empty( $dfcontact['formText'] ) && !empty( $dfcontact['formText'][$langId] ) ) {
		echo '<tr><td colspan="2" class="dfContactFormText" style="padding-top:10px;">' . $dfcontact['formText'][$langId] . '</td></tr>' . "\n";
		echo '<tr><td>&nbsp;</td><td></td></tr>' . "\n";
	}

	// contact form
	$mandatoryFields = 0;
	$lastCellEmpty = false;
	reset($addressFormatSplit);
	while (list($key, $line) = each($addressFormatSplit)) {
		
		// extract placeholders and get template string
		$placeholders = array();
		if (!preg_match_all('/\[([^\]]*)\](\{.?\})?/', $line, $placeholders)) {
			if (!$lastCellEmpty) {
				$lastCellEmpty = true;
				echo "<tr>\n<td>&nbsp;</td><td>" . trim($line) . "</td></tr>\n";
			}
			continue;
		}
		$sizes = $placeholders[2];
		$placeholders = $placeholders[1];
		
		// keys
		$keyCell = '';
		$nonDisplayableKeys = array(
			'message',
			'checkbox',
			'mandatory',
		);
		$keys = array();
		foreach ($placeholders as $ph) {
			$ph = strtolower($ph);
			if (!in_array($ph, $nonDisplayableKeys)) {
				array_push($keys, '<label for="dfContactField-' . $ph . '">' . trim(JText::_('COM_DFCONTACT_'.strtoupper($ph)) . '</label>'));
			}
		}
		$keyCell = join('/', $keys);
		
		// calculate field sizes
		$fields = 0;
		$normalFields = 0;
		$specialsSizes = 0;
		$fullSize = 15;
		for ($i = 0; $i < sizeof($placeholders); $i++) {
			
			$ph = strtolower($placeholders[$i]);
			$specialSize = str_replace('{', '', str_replace('}', '', $sizes[$i]));
			
			if (!empty($dfcontact['field'][$ph]['display']) && !$specialSize) {
				$normalFields++;
				$fields++;
			} else if (!empty($dfcontact['field'][$ph]['display'])) {
				$specialsSizes += $specialSize;	
				$fields++;			
			}
		}
		if ($normalFields) {
			$normalSize = ($fullSize - $specialsSizes - (($fields - 1) * (strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') > 0 ? 0.3 : 0.4))) / $normalFields;
			$normalSize = number_format($normalSize, 2, ".", "");
		} else {
			$normalSize = 15;
		}
		
		// values
		$phs = array();
		$line = '';
		for ($i = 0; $i < sizeof($placeholders); $i++) {
			
			$ph = strtolower($placeholders[$i]);
			$specialSize = str_replace('{', '', str_replace('}', '', $sizes[$i]));
			
			if (!empty($dfcontact['field'][$ph]['display'])) {
				array_push($phs, $ph);
				if ( !empty( $dfcontact['field'][$ph]['duty'] ) ) {
					$mandatoryFields++;
				}
				switch($ph) {
					case 'message' :
						$line .= '<textarea name="message" id="dfContactField-message" class="inputbox" cols="40" rows="5">' . ( !empty( $_REQUEST['message'] ) ? htmlspecialchars( stripslashes( $_REQUEST['message'] ) ) : '' ) . '</textarea>' . ( !empty( $dfcontact['field'][$ph]['duty'] ) ? '*' : '');
						break;
					case 'checkbox' :
						$line .= '<input type="checkbox" name="checkbox" id="dfContactField-checkbox" class="inputbox"' . ( !empty( $_REQUEST['checkbox'] ) ? ' checked="checked"' : '' ) . ' /> <label for="dfContactField-' . $ph . '">' . ( !empty( $dfcontact['field']['checkbox']['text'] ) && !empty( $dfcontact['field']['checkbox']['text'][$langId] ) ? $dfcontact['field']['checkbox']['text'][$langId] : '') . '</label>' . ( !empty( $dfcontact['field'][$ph]['duty'] ) ? ' *' : '');
						break;
					default:
						$line .=  '<input type="text" name="' . $ph . '" id="dfContactField-' . $ph . '" class="inputbox" value="' . ( !empty( $_REQUEST[$ph] ) ? htmlspecialchars( stripslashes( $_REQUEST[$ph] ) ) : "" ) . '" style="width:' . ($specialSize ? $specialSize : $normalSize) . 'em;" />'  . ( !empty( $dfcontact['field'][$ph]['duty'] ) ? '*' : '');
						if ($ph == 'geocoordinates') {
							$line .= '<script src="http://api.mygeoposition.com/api/geopicker/api.js" type="text/javascript"></script>';
							$line .= '<script type="text/javascript">';
							$line .= 'function openGeopicker() {';
							$line .= 'var dfcontactPageLocation = "' . ( $dfcontact['field']['geocoordinates']['value'] ? $dfcontact['field']['geocoordinates']['value'] : $dfcontact['field']['zip']['value'] . ', ' . $dfcontact['field']['city']['value'] . ', ' . $dfcontact['field']['street']['value'] . ', ' . $dfcontact['field']['country']['value']) . '";';
							$line .= 'var dfcontactUserLocation = "";';
							$line .= 'dfcontactUserLocation += (document.getElementById("dfContactField-street") && document.getElementById("dfContactField-street").value	 	? document.getElementById("dfContactField-street").value + "," 		: "");';
							$line .= 'dfcontactUserLocation += (document.getElementById("dfContactField-streetno") && document.getElementById("dfContactField-streetno").value 	? document.getElementById("dfContactField-streetno").value + "," 	: "");';
							$line .= 'dfcontactUserLocation += (document.getElementById("dfContactField-zip") && document.getElementById("dfContactField-zip").value 			? document.getElementById("dfContactField-zip").value + "," 		: "");';
							$line .= 'dfcontactUserLocation += (document.getElementById("dfContactField-city") && document.getElementById("dfContactField-city").value 			? document.getElementById("dfContactField-city").value + "," 		: "");';
							$line .= 'dfcontactUserLocation += (document.getElementById("dfContactField-state") && document.getElementById("dfContactField-state").value 		? document.getElementById("dfContactField-state").value + "," 		: "");';
							$line .= 'dfcontactUserLocation += (document.getElementById("dfContactField-country") && document.getElementById("dfContactField-country").value 	? document.getElementById("dfContactField-country").value + "," 	: "");';
							$line .= 'var lookupLocation = (dfcontactUserLocation ? dfcontactUserLocation : dfcontactPageLocation);';
							$line .= 'myGeoPositionGeoPicker({startAddress: lookupLocation, returnFieldMap:{\'dfContactField-' . $ph . '\':\'<LAT>,<LNG>\'}, source:\'dfcontact\'});';
							$line .= '}';						
							$line .= 'document.write("<button type=\"button\" onclick=\"openGeopicker();\">' . JTEXT::_( 'COM_DFCONTACT_GEOPICKER') . '</button>");';
							$line .= '</script>';
						}
				}
			} elseif ( $ph == 'captcha' && DFCONTACT_CAPTCHA_ENABLED ) {
				array_push($phs, $ph);
				$mandatoryFields++;				
				$line .= JTEXT::_( 'COM_DFCONTACT_FORM_CAPTCHA_INFO') . '<br />';
		 		if ( $dfcontact['captcha'] == 'com_securityimages' ) {
					$captchaImageUrl = 'index.php?option=com_dfcontact&displayCaptcha=true';
					$line .= '<img src="' . $captchaImageUrl . '" id="dfContactField-captcha-image" /> <img src="components/com_securityimages/buttons/reload.gif" border="0" onclick="var date = new Date();document.getElementById(\'dfContactField-captcha-image\').src=\'' . $captchaImageUrl . '&timestamp=\' + date.valueOf();" style="cursor:pointer;" alt="" /><br /><input type="text" name="' . $ph . '" id="dfContactField-' . $ph . '" style="width:' . ($specialSize ? $specialSize : $normalSize) . 'em;" />*';
		 		} else if ( $dfcontact['captcha'] == 'recaptcha' ) {
					$line .= '<script type="text/javascript">var RecaptchaOptions = {theme : \'' . $dfcontact['recaptcha_theme'] . '\', lang : \'' . substr($langId, 0, 2). '\'};</script>';
					require_once( JPATH_SITE . DS . 'components' . DS . 'com_dfcontact' . DS . 'recaptcha' . DS . 'recaptchalib.php' );
					$line .= recaptcha_get_html($dfcontact['recaptcha_public_key']);
		 		}
			} elseif ( $ph == 'mandatory' ) {
				$line .= JText::_( 'COM_DFCONTACT_FORM_MANDATORY');
			}
	
		}
		$valueCell = trim($line);
		
		// display line
		if ($valueCell) {
			$lastCellEmpty = false;
			echo "<tr>\n";
			echo '<th valign="top">' . $keyCell . "&nbsp;</th>\n";
			echo '<td valign="top">' . $valueCell;
			foreach ($phs as $ph) {
				echo "<span id=\"dfContactFieldErrorSpan-$ph\" class=\"dfContactError\"></span>\n";			
			}
			echo "</td>\n";
			echo "</tr>\n";
		}	
		
	}
	
	// submit button & form end
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo '<td><input type="submit" value="' . ( !empty( $dfcontact['buttonText'] ) && !empty( $dfcontact['buttonText'][$langId] ) ? $dfcontact['buttonText'][$langId] : JText::_('COM_DFCONTACT_FORM_SUBMIT') ) . '" class="button" /></td>' . "\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo '<input type="hidden" name="submit" value="true" />' . "\n";
	echo "</form>\n";
?>

<span style="display:none;visibility:hidden;">Created using the Joomla DFContact component.</span>

<script type="text/javascript">
<!--

function DFContactValidation(id, duty) {
	this.id		= id;
	this.duty 	= duty;
}


function dfcontact_checkForm() {

  var fields = new Array();
<?php
reset($dfcontact['field']);
while(list($key, $value) = each($dfcontact['field'])) {
	echo "  fields.push(new DFContactValidation('" . $key . "', " . (!empty($value['duty']) ? 'true' : 'false') . "));\n";
}
if ( DFCONTACT_CAPTCHA_ENABLED ) {
	echo "  fields.push(new DFContactValidation('captcha', true));\n";
}
?>

  // check fields
  var errorSpan;
  var node;
  var nodeSelected;
  var ok = true;
  for (var i = 0; i < fields.length; i++) {
  	errorSpan = document.getElementById('dfContactFieldErrorSpan-' + fields[i].id);
  	node = document.getElementById('dfContactField-' + fields[i].id);
  	if (errorSpan && node) {
  		if (fields[i].duty
  			&& (
  				(node.nodeName == 'INPUT' && node.getAttribute('type') == 'text' && node.value == '')
  				|| (node.nodeName == 'INPUT' && node.getAttribute('type') == 'checkbox' && !node.checked)
  				|| (node.nodeName == 'TEXTAREA' && node.value == '')
  				)
  			) {
			ok = false;
	  		errorSpan.innerHTML = '<br />' + ( node.getAttribute('type') == 'checkbox' ? '<?php echo JText::_( 'COM_DFCONTACT_FORM_PLEASE_CHECK_BOX' ); ?>' : '<?php echo JText::_( 'COM_DFCONTACT_FORM_PLEASE_FILL_FIELD' ); ?>');
	  		if (!nodeSelected) {
	  			nodeSelected = node;
	  			node.focus();
	  		}
  			
  		} else {
	  		errorSpan.innerHTML = '';
  		}
  	}
  }
	
  return ok;
}

function validMail(s) {
  var a = false;
  var res = false;
  if ( typeof( RegExp ) == 'function') {
    var b = new RegExp( 'abc' );
    if ( b.test( 'abc' ) == true ) {
      a = true;
    }
  }
  if ( a == true ) {
    reg = new RegExp( '^([a-zA-Z0-9\\-\\.\\_]+)' +
                   '(\\@)([a-zA-Z0-9\\-\\.]{2,255})' +
                   '(\\.)([a-zA-Z]{2,6})$' );
    res = ( reg.test( s ) );
  } else {
    res = ( s.search( '@' ) >= 1 &&
    s.lastIndexOf( '.' ) > s.search( '@' ) &&
    s.lastIndexOf( '.' ) >= s.length - 5 );
  }
  return( res );
}
//-->
</script>
	
<?php
}

# End content
echo "\n</div>\n";






/**
 * Encodes a string into it's asci-entities.
 *
 * @static
 * @access	public
 * @param	string	$string
 * @return	string
 * @date	13:42 13.10.2005
 * @version	1.0
 * @status	Complete
 */
function dfcontact_asciiEncodeString( $string ) {

	$result = "";

	for ( $i = 0; $i < strlen( $string ); $i++ ) {
		$result .= "&#" . ord( $string[$i] ) . ";";
	}

	return $result;
}



/**
 * Returns whether an email-adress or
 * a list of comma-separated email-adresses
 * is valid and has a valid tld (optional).
 *
 * @static
 * @access	public
 * @param	string	$eMail		eMail
 * @param	boolean $checkTLD 	Check TLD-Validity?
 * @return	boolean
 * @date	16:20 01.07.2005
 * @version	1.0
 * @status	Complete
 */
function dfcontact_validMail($eMail, $checkTLD = false) {

	# list taken from: http://data.iana.org/TLD/tlds-alpha-by-domain.txt
	# Version 1.2, Last Updated 2005-04-29
	$tlds = array("AC", "AD", "AE", "AERO", "AF", "AG", "AI", "AL", "AM", "AN", "AO", "AQ", "AR", "ARPA", "AS", "AT", "AU", "AW", "AZ", "BA", "BB", "BD", "BE", "BF", "BG", "BH", "BI", "BIZ", "BJ", "BM", "BN", "BO", "BR", "BS", "BT", "BV", "BW", "BY", "BZ", "CA", "CC", "CD", "CF", "CG", "CH", "CI", "CK", "CL", "CM", "CN", "CO", "COM", "COOP", "CR", "CU", "CV", "CX", "CY", "CZ", "DE", "DJ", "DK", "DM", "DO", "DZ", "EC", "EDU", "EE", "EG", "ER", "ES", "ET", "EU", "FI", "FJ", "FK", "FM", "FO", "FR", "GA", "GB", "GD", "GE", "GF", "GG", "GH", "GI", "GL", "GM", "GN", "GOV", "GP", "GQ", "GR", "GS", "GT", "GU", "GW", "GY", "HK", "HM", "HN", "HR", "HT", "HU", "ID", "IE", "IL", "IM", "IN", "INFO", "INT", "IO", "IQ", "IR", "IS", "IT", "JE", "JM", "JO", "JP", "KE", "KG", "KH", "KI", "KM", "KN", "KR", "KW", "KY", "KZ", "LA", "LB", "LC", "LI", "LK", "LR", "LS", "LT", "LU", "LV", "LY", "MA", "MC", "MD", "MG", "MH", "MIL", "MK", "ML", "MM", "MN", "MO", "MP", "MQ", "MR", "MS", "MT", "MU", "MUSEUM", "MV", "MW", "MX", "MY", "MZ", "NA", "NAME", "NC", "NE", "NET", "NF", "NG", "NI", "NL", "NO", "NP", "NR", "NU", "NZ", "OM", "ORG", "PA", "PE", "PF", "PG", "PH", "PK", "PL", "PM", "PN", "PR", "PRO", "PS", "PT", "PW", "PY", "QA", "RE", "RO", "RU", "RW", "SA", "SB", "SC", "SD", "SE", "SG", "SH", "SI", "SJ", "SK", "SL", "SM", "SN", "SO", "SR", "ST", "SU", "SV", "SY", "SZ", "TC", "TD", "TF", "TG", "TH", "TJ", "TK", "TL", "TM", "TN", "TO", "TP", "TR", "TT", "TV", "TW", "TZ", "UA", "UG", "UK", "UM", "US", "UY", "UZ", "VA", "VC", "VE", "VG", "VI", "VN", "VU", "WF", "WS", "YE", "YT", "YU", "ZA", "ZM", "ZW");

	$eMails = preg_split( '/,/', str_replace( ", ", ",", $eMail ) );

	for ( $i = 0; $i < sizeof( $eMails ); $i++ ) {
		# check format
		if ( !preg_match( "/^([a-zA-Z0-9\\+\\-\\._])+@([a-zA-Z0-9������\\-]{2,255}\\.)+([a-zA-Z]){2,6}$/" , $eMails[$i] ) ) {
			return false;
		}
		# check tld
		if( $checkTLD && !in_array( strtoupper( substr( $eMails[$i], strrpos( $eMails[$i], "." ) + 1 ) ), $tlds ) ) {
			return false;
		}
	}

	return true;

}

?>