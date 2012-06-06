<?php
/*
Template Name: Authors List
*/
?>
		<?php get_header(); ?>
		<div class="container">
			<div class="panel archive">
				<div class="panel_title">
					<h2>Midd<b>START</b> People</h2>
				</div>
				<?php
					$posts = get_posts(array('numberposts' => -1));
					if (isset($wp_query->query_vars['middstart_authors_post'])) {
						$posts = array(get_post($wp_query->query_vars['middstart_authors_post']));
					}
					$authors = array();
					for ($i = 0; $i < count($posts); $i++) {
						foreach(split(',', get_meta('middstart-credits', $posts[$i]->ID)) as $author) {
							if ($author == '') continue;
																			$parts = split(' ', $author);
																			$lastname = $parts[count($parts) - 1];
																			$authors[$lastname.$author]['name'] = $author;
							$authors[$lastname.$author]['posts'][] = $posts[$i];
						}
					}
					ksort($authors);
				?>
				<table class="cubes authors">
					<tr>
					<?php
						$i = 0;
						foreach($authors as $author => $p) {
							if ($i % 4 == 0 && $i != 0) print '<tr>';
							print '<td class="cube c' . ($i % 16 + 1) . '"><div class="wrapper">' . $p['name'] .'<ul class="projects">';
							
							foreach($p['posts'] as $post) {
								print '<li><a href="' . $post->guid . '" title="Discuss ' . $post->post_title . '">' . $post->post_title . "</a></li>";
							}
							
							print '</ul></div></td>';
	
							if ($i % 4 == 3 && $i != count($authors) - 1) print '</tr>';
							$i++;
						}
					?>
					</tr>
				</table>
			</div>
		</div>
		<?php get_footer(); ?>