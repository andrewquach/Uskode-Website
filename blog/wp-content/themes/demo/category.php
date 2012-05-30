<?php get_header(); ?>
<section id="container"> <!-- HTML5 section tag for the content 'section' -->
		
        <div id="breadcrumbs"><a href="#">Home</a> : <strong>Solutions & Services</strong></div>
        <div id="left">
	<?php if (have_posts()) : ?>
     <?php $count = 0; ?>
		<?php while (have_posts()) : the_post();
        $c++; // increment the counter
         if( $c % 3 != 0) {
      	   $extra_class = 'left';
           } else {
           $extra_class = 'right'; }
        ?>


			<div <?php post_class($extra_class) ?> id="post-<?php the_ID(); ?>">
			<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

            <?php if ( function_exists( 'get_the_image' ) ) {
            get_the_image( array( 'custom_key' => array( 'post_thumbnail' ), 'default_size' => 'full', 'image_class' => 'alignleft', 'width' => '278', 'height' => '122' ) ); }?>
				
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
                  <div class="clr"></div>
                </div>

			</div>

            <?php if(++$counter % 3 == 0) : ?>
           <div class="clr"></div>
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
