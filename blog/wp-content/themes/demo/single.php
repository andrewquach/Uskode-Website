<?php get_header(); ?>
<section id="container">
<div id="breadcrumbs"><a href="http://uskode.com/blog">Blog</a> : <strong><?php the_title(); ?></strong></div>
<div id="left">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class("left") ?> id="post-<?php the_ID(); ?>">

				<h2 class="titles"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" style="font-size:20px"><?php the_title(); ?></a></h2>
				
				<?php if ( function_exists( 'get_the_image' ) ) {
            get_the_image( array( 'custom_key' => array( 'post_thumbnail' ), 'default_size' => 'full', 'image_class' => 'alignleft', 'width' => '600') ); }?>
            
				<div class="entry">
					<?php the_content(''); ?>
				</div>
                <div class="meta">
                In: <?php the_category(', '); ?> <?php the_tags(); ?>
                </div>
			</div>

            <?php comments_template(); ?>

        <?php endwhile; ?>

	<?php endif; ?>
</div>
<div id="right">
<?php get_sidebar(); ?>
</div>
</div>
<div class="clr"></div>
</section>
<?php include('sidebar-bottom.php'); ?>
<?php get_footer(); ?>
