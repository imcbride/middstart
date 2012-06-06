		<?php get_header(); ?>
		<div class="container">
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : ?>
					<?php the_post(); ?>
					<?php include(TEMPLATEPATH . '/project.php'); ?>
					<?php if (is_home() && !$_GET['location']) break; ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
		<?php get_footer(); ?>