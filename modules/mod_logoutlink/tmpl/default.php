<?php
/**
* @version   $Id$
* @package   Jumi
* @copyright (C) 2011 - 2011 MadeByChad.com, 2011 Chad Wagner
* @license   GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
*/

defined('_JEXEC') or die('Restricted access');



if($userId){
?>
<form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="login-form" style="display:inline;" class="logout-button">
		<input type="submit" name="Submit" class="button" value="<?php echo $logout_text ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $logout_link; ?>" />
		<?php echo JHtml::_('form.token'); ?>
</form>
<?php	

}else{
	$url = JRoute::_('index.php?option=com_users&view=login&return='.$login_link);
	echo '<span id="login-form"><a href="'.$url.'" class="login-button">'.$login_text.'</a></span>';
}

?>