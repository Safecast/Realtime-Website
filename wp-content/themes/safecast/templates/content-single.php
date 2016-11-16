<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
<?php if (!get_post_type(get_the_ID()) == 'sensors'): ?>
      <h1 class="entry-title page-header">
		  <?php the_title(); ?>
	  </h1>
<?php get_template_part('templates/entry-meta'); ?>
<?php else : ?>
	<?php include safecast_sensor_page_path(); ?>
<?php endif; ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
