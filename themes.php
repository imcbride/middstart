<?php
/*
Template Name: Category List
*/
?>
		<?php get_header(); ?>
		<div class="container">
			<div class="panel archive">
				<div class="panel_title">
					<h2>Midd<b>START</b> Themes</h2>
				</div>
				<table class="cubes themes">
					<tr>
					<?php
						$categories = get_categories(array('hide_empty' => TRUE));
						for ($i = 0; $i < count($categories); $i++) {
							if ($i % 4 == 0 && $i != 0) print '<tr>';
							
							print '<td class="cube c' . ($i % 16 + 1) . '"><a href="' . get_category_link( $categories[$i]->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $categories[$i]->name ) . '" ' . '>' . $categories[$i]->name.'</a></td>';
								
							if ($i % 4 == 3 && $i != count($categories) - 1) print '</tr>';
						}
					?>
					</tr>
				</table>
			</div>
		</div>
		<?php get_footer(); ?>