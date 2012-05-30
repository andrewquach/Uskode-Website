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

<link href="<?php echo $this->baseurl ?>/templates/uskodenew/css/style.css" rel="stylesheet" type="text/css">
<!--[if IE]>
    	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- SIMPLY INCLUDE THE JS FILE! -->
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskodenew/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskodenew/js/ui.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskodenew/js/ui_002.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskodenew/js/jqFancyTransitions.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function($){

		
			$('#slideshow2').jqFancyTransitions({ 
				width: '960', 
				height: '377',
				position: 'alternate',
				direction: 'random',
				strips: 25,
				stripDelay: 3,
				titleOpacity: 0.7
			});
			$('#placeslideshow').tabs({ fx: { opacity: 'toggle' } }); 
	
	});
	</script>
    
    <script type="text/javascript">

  		var _gaq = _gaq || [];
  		_gaq.push(['_setAccount', 'UA-27964384-1']);
  		_gaq.push(['_trackPageview']);

  		(function() {
    		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  		})();

	</script>
    
    
</head>

<body>
<header> <!-- HTML5 header tag -->
		<div class="logo">
			<jdoc:include type="modules" name="logo" style="xhtml" />
        	
        </div>
		<div class="hd-right">
			<div class="loginlink"><jdoc:include type="modules" name="position-0" style="xhtml" />
			</div>
			<div class="tel" ><jdoc:include type="modules" name="position-1" style="xhtml" /></div>
			<div class="fblogin"><jdoc:include type="modules" name="fblog" style="xhtml"/></div>
		  <div class="clr"></div>
            
            <nav> <!-- HTML5 navigation tag -->
                <jdoc:include type="modules" name="position-2" style="xhtml" />
            </nav>
        </div>
	</header>

	<section id="<?php if(!$this->countModules('right')) {echo "contentcontainer";} else {echo "container";}; ?>"> <!-- HTML5 section tag for the content 'section' -->
    
    	<div id="banner">
        
        	<div id="placeslideshow">
         
            	<div id="panel1"><jdoc:include type="modules" name="position-4" style="xhtml" />
			</div>
		</div>
        
        </div>
    	<div id="left" class="<?php if(!$this->countModules('right')) {echo "clsfull";}; ?>">        
        	<jdoc:include type="component" />
        </div>
		
		
		<?php if($this->countModules('right')) : ?>
		<div id="right">
				<jdoc:include type="modules" name="right" style="xhtml" />
		</div>
		<?php endif; ?>
		
              <div class="clr"></div>

        
      <div class="ser-logos">
      	 <jdoc:include type="modules" name="position-11" style="xhtml" />
      </div>


    </section>
    
    <footer>
      <div class="services-box">
	   <h1>Services</h1>
        <jdoc:include type="modules" name="position-12" style="xhtml" />
      </div>
      
      <div class="sitemap-box">
	  <h1>Sitemap</h1>
         <jdoc:include type="modules" name="position-13" style="xhtml" />
      </div>
      
      <div class="contactus-box">
	  <h1>Contact Us</h1>
          <jdoc:include type="modules" name="position-14" style="xhtml" />
      </div>
      
      <div class="stay-box">
	   <h1>Stay Connected</h1>
         <jdoc:include type="modules" name="position-15" style="xhtml" />
         <jdoc:include type="modules" name="facebook" style="xhtml" />
      </div>
      
    </footer>
    
<div class="copyright">&copy; 2011 Uskode Solution - All rights reserved.</div>
</div>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(66519512); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/66519512ns.gif" /></p></noscript>
</body>
</html>