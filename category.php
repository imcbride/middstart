		<?php get_header(); ?>
		<div class="container">
			<div class="panel archive">
				<div class="panel_title">
					<h2><?php single_cat_title(); ?></h2>
				</div>
				<?php $description = category_description(); ?>
				<?php if (!empty($description)): ?>
					<p><?php $description; ?></p>
				<?php endif; ?>
			</div>
			<?php $catquery = get_query_var('cat'); ?>
			<?php $category = get_the_category(); ?>
			<?php if (!empty($category)): ?>
				<?php foreach($category as $cat): ?>
					<?php if ($cat->cat_ID != $catquery) continue; ?>
					<?php query_posts('posts_per_page=-1&cat=' . $cat->term_id); ?>
					<?php include(TEMPLATEPATH . '/project_list.php'); ?>
					<?php break; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
		<?php get_footer(); ?>