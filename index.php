		<?php get_header(); ?>
		<div class="container">
			<?php if (is_home()): ?>
				<?php query_posts('posts_per_page=1&post_in='.get_option('sticky_posts').'&orderby=date&order=DESC'); ?>
			<?php endif; ?>
			<?php include(TEMPLATEPATH . '/project_list.php'); ?>
			<?php if (is_home()): ?>
				<div class="clear"></div>
				<h2 class="see_more"><a href="projects">See more projects &raquo;</a></h2>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
		<?php get_footer(); ?>