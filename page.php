		<?php get_header(); ?>
		<div class="container">
			<div class="panel">
				<div class="panel_title">
					<h2><?php the_title(); ?></h2>
				</div>
				<?php the_post(); ?>
				<?php the_content(); ?>
				<?php comments_template(); ?>
			</div>
		</div>
		<?php get_footer(); ?>