<?php
/*
        mod_simpleemailform.php
        
        Copyright 2010 D. Bierer <doug@unlikelysource.com>
		Version	1.1.12

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.
        
        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.
        
        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
        MA 02110-1301, USA.
		
		2011-09-01 DB: fixed error Warning: Missing argument 4 for sendResults(), called in C:\wamp\www\chapexsite\modules\mod_simpleemailform\mod_simpleemailform.php on line 476 and defined in C:\wamp\www\chapexsite\modules\mod_simpleemailform\mod_simpleemailform.php on line 214

*/

// no direct access
defined('_JEXEC') or die('Restricted access');
// 2010-04-28 added DB
jimport( 'joomla.filesystem.file' );

// 2011-05-07 DB: removed timezone setting 
//date_default_timezone_set("America/Los_Angeles");

// 2010-11-23 Changed from Email to SimpleEmailForm class
class SimpleEmailForm {
	public $to;
	public $from;
	public $cc;
	public $bcc;
	public $attachment;
	public $subject;
	public $body;
	public $dir;
	public $copyMe;
	public $copyMeAuto;
}

// Initialize vars
$output 	= "";
$maxFields	= 6;
$field		= array();
$txtError	= '';
$badEmail	= '';
$fileMsg	= '';

// Get XML params
$cssClass 		= $params->get('mod_SEF_cssClass');
$labelAlign		= $params->get('mod_SEF_labelAlign');
$fromsize 		= $params->get('mod_SEF_fromsize');
$txtRows 		= $params->get('mod_SEF_textareaRows');
$txtCols 		= $params->get('mod_SEF_textareaCols');
$txtLabel 		= $params->get('mod_SEF_textareaLabel');
$txtActive 		= $params->get('mod_SEF_textareaActive');
$subjectsize 	= $params->get('mod_SEF_subjectsize');
$subjectlabel 	= $params->get('mod_SEF_subjectlabel');
$fromlabel 		= $params->get('mod_SEF_fromlabel');
$copymeLabel 	= $params->get('mod_SEF_copymeLabel');
$copymeActive 	= $params->get('mod_SEF_copymeActive');
$copymeAuto 	= $params->get('mod_SEF_copymeActive');
$errorTxtColor	= $params->get('mod_SEF_errorTxtColor');
$successTxtColor= $params->get('mod_SEF_successTxtColor');

// 2011-02-10 DB: added error checking for all incoming params
$fromsize	= (int) $fromsize;
$txtRows	= (int) $txtRows;
$txtCols	= (int) $txtCols;

// 2010-08-24 DB: restructured field params into array 
for ($x = 1; $x < $maxFields; $x++) {
	$sizeLabel 				= 'mod_SEF_field' . $x . 'size';
	$labelLabel 			= 'mod_SEF_field' . $x . 'label';
	$activeLabel 			= 'mod_SEF_field' . $x . 'active';
	$maxxlabel				= 'mod_SEF_field' . $x . 'maxx'; //rcl linha totalmente adicionada (1 de 7)
	// 2010-12-12 DB: added check to see if any values + set defaults
	$s = $params->get($sizeLabel);
	$l = $params->get($labelLabel);
	$a = $params->get($activeLabel);
	$m = $params->get($maxxlabel); //rcl linha totalmente adicionada (2 de 7)
	$field[$x]['active'] 	= (isset($a)) ? $a : 'N';
	$field[$x]['size'] 		= (isset($s)) ? $s : 40;
	$field[$x]['label'] 	= (isset($l)) ? $l : $x . ':';
	$field[$x]['maxx']		= (isset($m)) ? $m : 255; //rcl linha totalmente adicionada (3 de 7)
	$field[$x]['error'] 	= '';
}
// 2010-08-24 DB: added allowed file upload extensions
$uploadActive 	= $params->get('mod_SEF_uploadActive');
$uploadAllowed 	= '';
if ($uploadActive == 'Y') {
	$a = strtolower($params->get('mod_SEF_uploadAllowed'));
	if (isset($a) && $a) {
		$uploadAllowed = explode(',', $a);
	}
}

// Captcha
$useCaptcha 		= $params->get('mod_SEF_useCaptcha');
$captchaDir 		= $params->get('mod_SEF_captchaDir');
$captchaURL 		= $params->get('mod_SEF_captchaURL');
$captchaLen 		= $params->get('mod_SEF_captchaLen');
$captchaSize		= $params->get('mod_SEF_captchaSize');
$captchaWidth		= $params->get('mod_SEF_captchaWidth');
$captchaHeight		= $params->get('mod_SEF_captchaHeight');
$captchaTextColor	= $params->get('mod_SEF_captchaTxtColor');
$captchaLinesColor	= $params->get('mod_SEF_captchaLinesColor');
$captchaBgColor		= $params->get('mod_SEF_captchaBgColor');

// 2010-08-27 DB: Changed Language to read Joomla default + ini files
$lang = $params->get('mod_SEF_defaultLang');
$langFile = dirname(__FILE__) . '/translate.' . $lang . '.ini';
if (file_exists($langFile)) {
	$transLang = parse_ini_file($langFile);
} else {
	$langFile = dirname(__FILE__) . '/translate.en.ini';
	$transLang = parse_ini_file($langFile);
}

// Set email object params
$msg 			 = new SimpleEmailForm();
$msg->dir 		 = $params->get('mod_SEF_emailFile');
$msg->to		 = $params->get('mod_SEF_emailTo');
$msg->cc		 = $params->get('mod_SEF_emailCC');
$msg->bcc 		 = $params->get('mod_SEF_emailBCC');
$msg->subject 	 = $params->get('mod_SEF_subjectline'); 
$msg->copyMe	 =  0;	// NOTE: depends on what user selects
$msg->copyMeAuto = ($copyMeAuto == 'Y') ? 1 : 0;

function uploadAttachment($dir,$transLang,$uploadAllowed,$errorTxtColor,$successTxtColor) {
	$message = '';
	$result = FALSE;
	$allowed = FALSE;
	// Capture filename
	// 2010-08-24 DB: added basename + strip_tags to help prevent XSS attacks
	$fn = (isset($_FILES['mod_SEF_upload']['name'])) ? basename(strip_tags($_FILES['mod_SEF_upload']['name'])) : '';
	// 2010-08-24 DB: added regex to check for allowed filenames
	if ($fn) {
		// Get filename extension
		$name = explode('.', $fn);
		$ext = strtolower(array_pop($name));
		if ($uploadAllowed) {
			if (in_array($ext, $uploadAllowed)) {
				$allowed = TRUE;
			}
		} else {
			$allowed = TRUE;
		}
		if ($allowed) {
			// Check to see if upload parameter specified
			if ( $_FILES['mod_SEF_upload']['error'] == UPLOAD_ERR_OK ) {
				// Check to make sure file uploaded by upload process
				if ( is_uploaded_file ($_FILES['mod_SEF_upload']['tmp_name'] ) ) {
					// Set filename to current directory
					$copyfile = $dir . DIRECTORY_SEPARATOR . $fn;
					// Copy file
					if ( move_uploaded_file ($_FILES['mod_SEF_upload']['tmp_name'], $copyfile) ) {
						// Save name of file
						$message .= "<p style='color:$successTxtColor;'>" . $transLang['upload_success'] . " '" . $fn . "'</p>\n";
						$result = $fn;
					} else {
						// Trap upload file handle errors
						$message .= "<p style='color:$errorTxtColor;'>" . $transLang['upload_unable'] . " " . $fn . "</p>\n";
					}
				} else {
					// Failed security check
					$message .= "<p style='color:$errorTxtColor;'>" . $transLang['upload_failure'] . " " . $fn . "</p>\n";
				}
			} else {
				// Failed security check
				$message .= "<p style='color:$errorTxtColor;'>" . $transLang['upload_error'] . " " . $fn . "</p>\n";
			}
		} else {
			// Failed regex
			$message .= "<p style='color:$errorTxtColor;'>" . $transLang['disallowed_filename'] . " " . $fn . "</p>\n";
		}
	} else {
		$result = '';
	}
	return array($result,$message);
}

function formatRow ($name,$size,$label,$labelAlign,$errorTxtColor,$error = NULL,$maxx = 255) { // rcl acrescentei o $maxx (4 de 7)
	if (isset($_POST[$name])) {
		$value =  htmlspecialchars($_POST[$name]);
		// replace '@' to prevent Joomla code from interpolating email address into javascript
		if (strpos($value, '@')) {
			$value = str_replace('@', '&#64;', $value);
		}
	} else {
		$value = '';
	}
	$row = '';
	$row .= "<tr>";
	$row .= "<th align='" . $labelAlign . "'>" . $label . "</th>";
	$row .= "<td><input type=text name='$name' id='$name' size='$size' value='$value' maxlength='$maxx' />"; // rcl alterei maxlength=255 para maxlength='$maxx' (5 de 7)
	$row .= ($error) ? "<br /><b style='color: $errorTxtColor;'>$error</b>" : '';
	$row .= "</td>";
	$row .= "</tr>\n";
	return $row;
}

// 2010-11-23 Changed from Email to SimpleEmailForm class
function sendResults(SimpleEmailForm $msg, $field, $txtLabel, $fromlabel)
{

	// Build Body
	// 2011-03-26 DB: added "From" field inside message body
	$msg->subject = (isset($_POST['mod_SEF_subject'])) ? htmlspecialchars($_POST['mod_SEF_subject']) : $msg->subject;
	$msg->subject .= (isset($_POST['mod_SEF_field1']))    ? " " . htmlspecialchars($_POST['mod_SEF_field1']) : "";
	$msg->body =  $fromlabel . ': ' . htmlspecialchars($msg->from);
	$msg->body .= (isset($_POST['mod_SEF_field1']))    ? "\n" . $field[1]['label'] . ': ' . htmlspecialchars($_POST['mod_SEF_field1']) : ""; // rcl error correction from $field[2] to $field[1]
	$msg->body .= (isset($_POST['mod_SEF_field2']))    ? "\n" . $field[2]['label'] . ': ' . htmlspecialchars($_POST['mod_SEF_field2']) : "";
	$msg->body .= (isset($_POST['mod_SEF_field3']))    ? "\n" . $field[3]['label'] . ': ' . htmlspecialchars($_POST['mod_SEF_field3']) : "";
	$msg->body .= (isset($_POST['mod_SEF_field4']))    ? "\n" . $field[4]['label'] . ': ' . htmlspecialchars($_POST['mod_SEF_field4']) : "";
	$msg->body .= (isset($_POST['mod_SEF_field5']))    ? "\n" . $field[5]['label'] . ': ' . htmlspecialchars($_POST['mod_SEF_field5']) : "";
	$msg->body .= (isset($_POST['mod_SEF_textarea']))  ? "\n" . $txtLabel          . ":\n" . htmlspecialchars($_POST['mod_SEF_textarea']) : "";
	// Strip slashes
	$msg->body = stripslashes($msg->body);	
	// Filter for \n in subject - 2010-05-03 DB
	$msg->subject = str_replace("\n",'',$msg->subject);
	// Send mail
	$message =& JFactory::getMailer();
	$message->addRecipient($msg->to);
	$message->setSender($msg->from);
	$message->setSubject($msg->subject);
	$message->setBody($msg->body);
	if ($msg->cc) { $message->addCC($msg->cc); }
	if ($msg->bcc) { $message->addBCC($msg->bcc); }
	if ($msg->attachment) { 
		// Formulate FN for attachment
		$msg->attachment = $msg->dir . DIRECTORY_SEPARATOR . $msg->attachment;
		$message->addAttachment($msg->attachment); 
	}
	try {
		$sent = $message->send();
		$msg->copyMe = (isset($_POST['mod_SEF_copyMe'])) ? (int) $_POST['mod_SEF_copyMe'] : 0;
		// 2011-08-12 DB: added option for copyMeAuto
		if ($msg->copyMe || $msg->copyMeAuto) { 
			$message->ClearAllRecipients();
			$message->addRecipient($msg->from);
			$sent = $message->send();
		}
		$result = TRUE;
	} catch (Exception $e) {
		$result = FALSE;
	}
	return $result;
	
}

function isEmailAddress($email)
{

	// Split the email into a local and domain
	$atIndex	= strrpos($email, "@");
	$domain		= substr($email, $atIndex+1);
	$local		= substr($email, 0, $atIndex);

	// Check Length of domain
	$domainLen	= strlen($domain);
	if ($domainLen < 1 || $domainLen > 255) {
		return false;
	}

	// Check the local address
	// We're a bit more conservative about what constitutes a "legal" address, that is, A-Za-z0-9!#$%&\'*+/=?^_`{|}~-
	$allowed	= 'A-Za-z0-9!#&*+=?_-';
	$regex		= "/^[$allowed][\.$allowed]{0,63}$/";
	if ( ! preg_match($regex, $local) ) {
		return false;
	}

	// No problem if the domain looks like an IP address, ish
	$regex		= '/^[0-9\.]+$/';
	if ( preg_match($regex, $domain)) {
		return true;
	}

	// Check Lengths
	$localLen	= strlen($local);
	if ($localLen < 1 || $localLen > 64) {
		return false;
	}

	// Check the domain
	$domain_array	= explode(".", rtrim( $domain, '.' ));
	$regex		= '/^[A-Za-z0-9-]{0,63}$/';
	foreach ($domain_array as $domain ) {

		// Must be something
		if ( ! $domain ) {
			return false;
		}

		// Check for invalid characters
		if ( ! preg_match($regex, $domain) ) {
			return false;
		}

		// Check for a dash at the beginning of the domain
		if ( strpos($domain, '-' ) === 0 ) {
			return false;
		}

		// Check for a dash at the end of the domain
		$length = strlen($domain) -1;
		if ( strpos($domain, '-', $length ) === $length ) {
			return false;
		}

	}

	return true;
}

function imageCaptcha(	$captchaBgColor, 
						$captchaDir, 
						$captchaHeight, 
						$captchaLen, 
						$captchaLinesColor, 
						$captchaSize, 
						$captchaTextColor, 
						$captchaURL, 
						$captchaWidth,
						&$url_fn)
{
	require_once 'Image.php';
	$imgOptions = array(
		'font_size'  		=> $captchaSize,
		'font_path'     	=> dirname(__FILE__),
		'font_file'     	=> 'FreeSansBold.ttf',
		'text_color'    	=> $captchaTextColor,
		'lines_color'    	=> $captchaLinesColor,
		'background_color'	=> $captchaBgColor
	);        
	$options = array(
		'width' 		=> $captchaWidth,
		'height'   		=> $captchaHeight,
		'output'  		=> 'png',
		'imageOptions'	=> $imgOptions
	);        
	// Generate a new Text_CAPTCHA object, Image driver
	$c = Text_CAPTCHA::factory('Image');
	$retval = $c->init($options);
	if (PEAR::isError($retval)) {
		throw new Exception($transLang['captcha_error_init'] . " " . $retval->getMessage());
	}

	// Get CAPTCHA image (as PNG)
	$png = $c->getCAPTCHAAsPNG();
	if (PEAR::isError($png)) {
		throw new Exception($transLang['captcha_error_gen'] . " " . $png->getMessage());
	}
	$randval = time() . rand(1,999);
	$fn = 'captcha_' . md5($randval) . '.png';
	$put_fn = $captchaDir . "/" . $fn;
	$url_fn = $captchaURL . "/" . $fn;
	JFile::write($put_fn, $png);
	
	return $c->getPhrase();
}		

function textCaptcha($captchaBgColor, $captchaLen, $captchaSize, $captchaTextColor, $captchaWidth, &$textCaptcha)
{
		$alpha = 'abcdefghijklmnopqrstuvwxyz';
		$textCaptcha = "<span style='color: $captchaTextColor; background-color: $captchaBgColor;'>";
		$phrase = '';
		$count = 0;
		for ($x = 0; $x < $captchaLen; $x++) {
			$a = substr($alpha, rand(0,25), 1);
			$phrase .= $a;
			switch ($count) {
				case ($count % 3) :
					$textCaptcha .= "<b>$a</b>";
					break;
				case ($count % 2) :
					$textCaptcha .= "<font size=+1>$a</font>";
					break;
				default :
					$textCaptcha .= "<font size=+2>$a</font>";
					break;
			}
			$count++;
		}
		$textCaptcha .= '</span>';
		return $phrase;
}

// Main logic

if (isset($useCaptcha) && $useCaptcha == "I") {
	try {
		// 2011-05-07 DB: use Joomla date functions instead of PHP
		//$timeCheck = time() - 300;
		$date =& JFactory::getDate();
		$timeCheck = $date->toUnix() - 300;
		foreach(new DirectoryIterator($captchaDir) as $file) {
			if (!$file->isDot()) {
				$fn = $file->getFilename();
				if (strlen($fn) > 8 && substr($fn,0,8) == "captcha_") {
					$fn = $captchaDir . DIRECTORY_SEPARATOR . $fn;
					// remove CAPTCHAs older than 5 minutes
					if ($file->getMTime() < $timeCheck) unlink($fn);
				}
			}
		}
	} catch (Exception $e) {
		$output .= "<p><b style='color:$errorTxtColor;'> " . $transLang['unable_clean_captcha'] . "</b></p>\n";
		// Make Captcha directory and URL recommendations
		$dirs = explode('/', dirname(__FILE__));
		if (count($dirs) > 2) {
			array_pop($dirs);
			array_pop($dirs);
		}
		$suggestedCaptchaDir = implode('/', $dirs) . '/images/captcha';
		$suggestedCaptchaURL = 'http://' . $_SERVER['HTTP_HOST'] . '/images/captcha';
		$output .= "<p>" . $transLang['make_captcha_dir'] . ": " . $suggestedCaptchaDir . "</p>\n";
		$output .= "<p>" . $transLang['make_captcha_url'] . ": " . $suggestedCaptchaURL . "</p>\n";
	}
}

if (isset($_POST['mod_SEF_submit'])) {

	$currentSession = JSession::getInstance('none',array());
	$msg->from = strip_tags($_POST['mod_SEF_from_field']);
	if ($uploadActive == "Y") {
		$result = uploadAttachment($msg->dir,$transLang,$uploadAllowed,$errorTxtColor,$successTxtColor);
		$fileMsg = $result[1];
		$msg->attachment = $result[0];
	}
	
	// Check "from" email address
	if (isEmailAddress($msg->from)) {
		// Check required fields
		$requiredCheck = TRUE;
		for ($x = 1; $x < $maxFields; $x++) {
			$fieldLabel = 'mod_SEF_field' . $x;
			if ($field[$x]['active'] == 'R') {
				if (!isset($_POST[$fieldLabel]) || $_POST[$fieldLabel] == NULL) {
					$requiredCheck = FALSE;
					$field[$x]['error'] = $transLang['required_field'] . " " . $field[$x]['label'];
				}
			}
		}
		if ($txtActive == 'R') {
				if (!isset($_POST['mod_SEF_textarea']) || $_POST['mod_SEF_textarea'] == NULL) {
					$requiredCheck = FALSE;
					$txtError = $transLang['required_field'] . " " . $txtLabel;
				}
		}
		if ($requiredCheck) {
			// Validate captcha if active
			if ($useCaptcha != "N") {
				$currentSession = JSession::getInstance('simpleForm', array());
				$captchaPhrase = $currentSession->get("mod_simpleemailform_phrase");
				if (isset($_POST['captcha']) && $_POST['captcha'] == $captchaPhrase) {
					if (sendResults($msg, $field, $txtLabel, $fromlabel)) {
						$output .= "<p><b><span style='color:$successTxtColor;'>" . $transLang['form_success'] . "</span></b></p>\n";
					} else {
						$output .= "<p><b><span style='color:$errorTxtColor;'>" . $transLang['form_unable'] . "</span></b></p>\n";
					}
				} else {
					$output .= "<p><b><span style='color:$errorTxtColor;'>" . $transLang['form_reenter'] . "</span></p>\n";
				}
			} else {
				if (sendResults($msg, $field, $txtLabel, $fromlabel)) {
					$output .= "<p><b><span style='color:$successTxtColor;'>" . $transLang['form_success'] . "</span></b></p>\n";
				} else {
					$output .= "<p><b><span style='color:$errorTxtColor;'>" . $transLang['form_unable'] . "</span></b></p>\n";
				}
			}
		}
	} else {
		$badEmail .= "<p><b><span style='color:$errorTxtColor;'>" . $transLang['email_invalid'] . "</span></b></p>\n";
	}
} elseif (isset($_POST['mod_SEF_reset'])) {
	foreach ($_POST as $key => $value) {
		$_POST[$key] = "";
	}
}

// Present the Email Form
echo ($cssClass) ? '<div class="' . $cssClass . '">' : "";
echo "<form method='post' name='SimpleEmailForm' id='SimpleEmailForm' enctype='multipart/form-data'>\n";
echo "<table>\n";
echo formatRow('mod_SEF_from_field',$fromsize,$fromlabel,$labelAlign,$errorTxtColor,$badEmail);
if (!isset($_POST['mod_SEF_subject'])) { $_POST['mod_SEF_subject'] = $params->get('mod_SEF_subjectline'); }
//echo formatRow('mod_SEF_subject',$subjectsize,$subjectlabel,$labelAlign,$errorTxtColor);
// 2010-08-24 DB: restructured field params into array 
for ($x = 1; $x < $maxFields; $x++) {
	$fieldLabel = 'mod_SEF_field' . $x;
	if ($field[$x]['active'] == 'Y' || $field[$x]['active'] == 'R' ) {
		echo formatRow($fieldLabel,
					   $field[$x]['size'],
					   $field[$x]['label'],
					   $labelAlign,
					   $errorTxtColor,
					   $field[$x]['error'],
					   $field[$x]['maxx']); // rcl linha totalmente adicionada (7 de 7)
	}
}
if ($txtActive != 'N') {
	$value = (isset($_POST['mod_SEF_textarea'])) ? htmlspecialchars($_POST['mod_SEF_textarea']) : "";
	echo "<tr>";
	echo "<th align='" . $labelAlign . "'>" . $txtLabel . "</th>";
	echo "<td><textarea name='mod_SEF_textarea' id='textarea' rows='$txtRows' cols='$txtCols'>" . stripslashes($value) . "</textarea>";
	echo ($txtError) ? "<br /><b style='color: $errorTxtColor;'>$txtError</b>" : '';
	echo "</td>";
	echo "</tr>\n";
}
if ($uploadActive == "Y") {
	echo "<tr>";
	echo "<th align='" . $labelAlign . "'>" . $transLang['attachment'] . "</th>";
	echo "<td>";
	echo "<input type=file name='mod_SEF_upload' enctype='multipart/form-data' />";
	echo $fileMsg;
	echo "</td>";
	echo "</tr>\n";
}
if ($useCaptcha != "N") {
	try {
		if ($useCaptcha == 'I') {
			$phrase = imageCaptcha(	$captchaBgColor, 
									$captchaDir, 
									$captchaHeight, 
									$captchaLen, 
									$captchaLinesColor, 
									$captchaSize, 
									$captchaTextColor, 
									$captchaURL, 
									$captchaWidth,
									$url_fn);
		} else {
			$phrase = textCaptcha(	$captchaBgColor, 
									$captchaLen, 
									$captchaSize, 
									$captchaTextColor, 
									$captchaWidth,
									$textCaptcha);
		}

		// Get CAPTCHA secret passphrase + store FN
		$currentSession = JSession::getInstance('simpleForm', array());
		$currentSession->set("mod_simpleemailform_phrase",$phrase);

		echo "<tr>";
		echo "<th align='" . $labelAlign . "'>" . $transLang['captcha_please_enter'] . "</th>";
		echo "<td>";
		if ($useCaptcha == 'I') {
			echo "<img src='$url_fn' width='$captchaWidth' height='$captchaHeight' />";
			// 2010-08-28 DB: this will change when I figure out how to set the # chars in the CAPTCHA
			echo "<br /><input name='captcha' id='captcha' type=text size='8' maxlength='8' />";
		} else {
			echo $textCaptcha;
			echo "<br /><input name='captcha' id='captcha' type=text size='$captchaLen' maxlength='$captchaLen' />";
		}
		echo "&nbsp;" . $transLang['captcha_please_help'] . "</td>";
		echo "</tr>\n";
	} catch (Exception $e) {
		echo "<tr>";
		echo "<th>" . $transLang['error'] . "</th>";
		echo "<td>" . $e->getMessage() . "</td>";
		echo "</tr>\n";
	}
}

if ($copymeActive == 'Y') {
	echo "<tr>";
	echo "<th>&nbsp;</th>";
	echo "<td><input type='checkbox' name='mod_SEF_copyMe' id='copyMe' value='1' />$copymeLabel</td>";
	echo "</tr>\n";
}
echo "<tr>";
echo "<th>&nbsp;</th>";
echo "<td>";
echo "<input type='submit' name='mod_SEF_submit' id='submit' value='" . $transLang['button_submit'] . "' title='" . $transLang['click_submit'] . "' />";
echo "&nbsp;&nbsp;";
echo "<input type='submit' name='mod_SEF_reset' id='reset' value='" . $transLang['button_reset'] . "' title='' />";
echo "</td>";
echo "</tr>\n";
echo "<tr>";
echo "<th>&nbsp;</th>";
echo "<td>$output</td>";
echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";
echo ($cssClass) ? '</div>' : "";
//phpinfo(INFO_VARIABLES);
?>
