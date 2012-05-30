<?php
/**
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
<link href="<?php echo $this->baseurl ?>/templates/uskodepage/css/style.css" rel="stylesheet" type="text/css">
<!--[if IE]>
    	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- SIMPLY INCLUDE THE JS FILE! -->
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskodepage/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskodepage/js/ui.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskodepage/js/ui_002.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskodepage/js/jqFancyTransitions.js"></script>   
</head>

<body>
<section id="container">
	<header></header>
	<div id="breadcrumb"><jdoc:include type="modules" name="breadcrumb" style="xhtml" />
	</div>	
	<div id="left"> <jdoc:include type="component" /></div>
	<div id="right"> <jdoc:include type="modules" name="right" style="xhtml" /> </div>
</section>
<footer>
<div class="footer"><jdoc:include type="module" name="footer" style="xhtml"/><div>
<div class="copyright">&copy; 2011 Uskode Solution - All rights reserved.</div>
</body>
</html>