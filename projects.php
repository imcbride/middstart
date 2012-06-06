<?php
/*
Template Name: Projects List
*/
?>
		<?php get_header(); ?>
		<div class="container">
			<div class="categories">
				<?php $categories = get_categories(array('hide_empty' => TRUE, 'exclude' => get_cat_ID('Success Stories'))); ?>
				<ul>
					<?php foreach($categories as $category): ?>
						<li><?php print '<a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a>'; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
   		<?php query_posts('posts_per_page=-1&cat=-' . get_cat_ID('Success Stories')); ?>
			<?php include(TEMPLATEPATH . '/project_list.php'); ?>
		</div>
		<div class="clear"></div>
		<?php get_footer(); ?>