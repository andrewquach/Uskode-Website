<?php get_header(); ?>
<section id="container">
<div id="breadcrumbs"><a href="http://uskode.com/">Home</a> : <strong>Blog</strong></div>
  <div id="left">
	
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post();
      	   $extra_class = 'left';
        ?>


			<div <?php post_class($extra_class) ?> id="post-<?php the_ID(); ?>">
<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" style="font-size:20px"><?php the_title(); ?></a></h2>

            <?php if ( function_exists( 'get_the_image' ) ) {
            get_the_image( array( 'custom_key' => array( 'post_thumbnail' ), 'default_size' => 'full', 'image_class' => 'alignleft', 'width' => '600') ); }?>
				
				<div class="entry">
					<?php the_content(''); ?>
				</div>
                <div class="meta">
                  <div class="inleft">
                     <?php the_time('F d, Y'); ?> &nbsp;
                     <?php comments_popup_link('0 Comments', '1 Comment', '% Comments', 'comm'); ?>
                     <hr size=1> 
                   </div>
                </div>
			</div>
        <?php endwhile; ?>

	
	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>

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
