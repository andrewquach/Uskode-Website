<?php
/**
* @version		2.7
* @copyright	Copyright (C) 2007-2011 Stephen Brandon
* @license		GNU/GPL
*/
 
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the helper functions only once
require_once (dirname(__FILE__).DS.'helper.php');

// the legacy method is this, and it still works (though without "changeCache" support):
// $moduleIds = modMetaModHelper::moduleIds( $params );

// Note: if a MetaMod is included twice in the page, e.g. in a {loadposition right} as well
// as in the normal position, it appears that the $attribs['name'] (which is the position)
// is not set for *either* occurrence.
list( $moduleIds, $changeCache ) = modMetaModHelper::moduleIdsAndChanges( $params, @$attribs['name'] );

// $attribs is inherited from the calling code in Joomla.
echo modMetaModHelper::displayModules( $moduleIds, $params, $attribs, $changeCache );
