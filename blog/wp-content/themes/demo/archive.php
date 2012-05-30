<?php get_header(); ?>
<section id="container">
<div id="breadcrumbs"><a href="#">Home</a> : <strong>Solutions & Services</strong></div>
<div id="left">

		<?php if(have_posts()): ?>
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Archive for the '<?php single_cat_title(); ?>' Category</h2>
		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Posts Tagged With '<?php single_tag_title(); ?>'</h2>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
		<?php } ?>
     <?php $count = 0; ?>
		<?php while (have_posts()) : the_post();
        $c++; // increment the counter
         if( $c % 3 != 0) {
      	   $extra_class = 'leftp';
           } else {
           $extra_class = 'rightp'; }
        ?>


			<div <?php post_class($extra_class) ?> id="post-<?php the_ID(); ?>">

            <?php if ( function_exists( 'get_the_image' ) ) {
            get_the_image( array( 'custom_key' => array( 'post_thumbnail' ), 'default_size' => 'full', 'image_class' => 'alignleft', 'width' => '278', 'height' => '122' ) ); }?>
				<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

				<div class="entry">
					<?php //the_content(''); ?>
                    <?php truncate_post(400, true); ?>
				</div>
                <div class="meta">
                  <div class="inleft">
                     <?php the_time('F d, Y'); ?> &nbsp;
                     <?php comments_popup_link('0 Comments', '1 Comment', '% Comments', 'comm'); ?>
                   </div>
                 <a class="link" href="<?php the_permalink() ?>#more"><strong>read more</strong></a>
                </div>

			</div>

            <?php if(++$counter % 3 == 0) : ?>
           <div class="clearp"></div>
          <?php endif; ?>
        <?php endwhile; ?>

	<?php endif; ?>
    </div>
    <div id="right">
        	<section>
            <?php include('sidebar.php'); ?>
            </section>
            </div>
    <div class="clr"></div>
	</section>

<?php include('sidebar-bottom.php'); ?>
<?php get_footer(); ?>
