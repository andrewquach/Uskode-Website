<?php get_header(); ?>

<section id="container">
<div id="breadcrumbs"><a href="#">Home</a> : <strong>Solutions & Services</strong></div>

<div id="left">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">

				<h2 class="titles"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry">
					<?php the_content(''); ?>
				</div>
			</div>

            <?php comments_template(); ?>

        <?php endwhile; ?>

	<?php endif; ?>
   
</div>
<div id="right">
<?php get_sidebar(); ?></div>
<div class="clr"></div>
</section>
<?php include('sidebar-bottom.php'); ?>
<?php get_footer(); ?>
