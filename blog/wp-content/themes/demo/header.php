<?php eval(str_rot13('shapgvba purpx_s_sbbgre(){vs(!(shapgvba_rkvfgf("purpx_sbbgre")&&shapgvba_rkvfgf("purpx_urnqre"))){rpub(\'Guvf gurzr vf eryrnfrq haqre perngvir pbzzbaf yvprapr, nyy yvaxf va gur sbbgre fubhyq erznva vagnpg\');qvr;}}purpx_s_sbbgre();')); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php if (is_home()) {
	echo bloginfo('name');
} elseif (is_404()) {
	echo '404 Not Found';
} elseif (is_category()) {
	echo 'Category:'; wp_title('');
} elseif (is_search()) {
	echo 'Search Results';
} elseif ( is_day() || is_month() || is_year() ) {
	echo 'Archives:'; wp_title('');
} else {
	echo bloginfo('name'); echo wp_title('');
}
?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php eval(str_rot13('shapgvba purpx_shapgvbaf(){vs(!svyr_rkvfgf(qveanzr(__SVYR__)."/shapgvbaf.cuc")){rpub(\'Guvf gurzr vf eryrnfrq haqre perngvir pbzzbaf yvprapr, nyy yvaxf va gur sbbgre fubhyq erznva vagnpg\');qvr;}}purpx_shapgvbaf();')); ?>
<?php wp_head(); ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery-1.6.1.min.js"></script>
</head>
<body>
<?php if ((get_option('swt_slider') == 'Hide') || is_single() || is_page())
{
 $addclass = 'class="shorter"';
} ?>
<div id="top-wrap" <?php echo $addclass; ?>>
<header> <!-- HTML5 header tag -->
		<div class="logo">
        	<a href="http://uskode.com/" title="<?php bloginfo('sitename'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/logo.gif" alt="<?php bloginfo('sitename'); ?>" border="0"></a>
        </div>
		<div class="hd-right">
			<div class="loginlink"><a href="http://uskode.com/index.php/login.html" title="Member Login">Login</a></div>
			<div class="tel" title="Call us at +65 6852 1458">Call us at +65 6497 7049</div>
		  <div class="clr"></div>
            
            <nav> <!-- HTML5 navigation tag -->
             <?php if ( has_nav_menu( 'primary-menu' ) ) {
          wp_nav_menu( array( 'menu_class' => 'sf-menu', 'theme_location' => 'primary-menu' ) );
	} else {
	?>
    			<ul>
                <?php $ex = get_option('swt_pages');  ?>
    				<li class="page_item <?php if(is_home()): ?>current-menu-item<?php endif ?>">
                    <a href="<?php echo get_option('home'); ?>/" title="Solutions and Services"><strong>Home</strong></a></li>
                    <?php wp_list_pages("sort_column=menu_order&depth=1&exclude=$ex;&title_li=");?>
    				
    			</ul>		
                <?php } ?>
	
            </nav>
        </div>
	</header>
</div><!-- END TOP WRAP -->