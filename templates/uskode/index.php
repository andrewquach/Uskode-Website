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
<link href="<?php echo $this->baseurl ?>/templates/uskode/css/style.css" rel="stylesheet" type="text/css">
<!--[if IE]>
    	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- SIMPLY INCLUDE THE JS FILE! -->
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskode/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskode/js/ui.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskode/js/ui_002.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/uskode/js/jqFancyTransitions.js"></script>
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

	<section id="contentcontainer"> <!-- HTML5 section tag for the content 'section' -->
    
    	<div id="banner">
        
        	<div id="placeslideshow">
                    <!--<div id="menutabs" >
                             <jdoc:include type="modules" name="position-3" style="xhtml" />
							 <li><a href="#panel1">E-Commerce</a></li>

					<li><a href="#panel2">CRM</a></li>

					<li><a href="#panel3">HRM</a></li>

					<li><a href="#panel4">Cloud Computing</a></li>

                    <li><a href="#panel5">Mobile</a></li>

                    </div>-->
         
                    <div id="panel1"><jdoc:include type="modules" name="position-4" style="xhtml" />
					</div>
		</div>
        
        </div>
        
    	<div id="home-latestproject">
        	<jdoc:include type="component" />
        </div>
        <!--
        <div id="home-threeboxes">
            <div class="home-box">
              <div class="ser-1">
                <jdoc:include type="modules" name="position-5" style="xhtml" />
              </div>
            </div>

            <div class="home-box">
              <div class="ser-2">
                    <jdoc:include type="modules" name="position-6" style="xhtml" />
              </div>
            </div>
            
   	    <div class="home-box">
              <div class="ser-3">
        	  <jdoc:include type="modules" name="position-7" style="xhtml" />
              </div>
            </div>
             <div class="clr"></div>
        </div>
        
      <div id="home-socialmedia">
      	<div class="home-box">
        	 <jdoc:include type="modules" name="position-8" style="xhtml" />
        </div> 
        <div class="home-box">
        	 <jdoc:include type="modules" name="position-9" style="xhtml" />
        </div>
        <div class="home-box">
            <div class="home-testi">
           	  <jdoc:include type="modules" name="position-10" style="xhtml" />
            </div>
        </div>
        <div class="clr"></div>
      </div>
        -->
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
		   <iframe src="http://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpreviousdesign&amp;layout=button_count&amp;show_faces=true&amp;width=200&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:21px;" allowTransparency="true"></iframe>
      </div>
      
    </footer>
    
<div class="copyright">&copy; 2011 Uskode Solution - All rights reserved.</div>

</body>
</html>