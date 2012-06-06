<?php
/*
Template Name: Donors List
*/
?>
		<?php get_header(); ?>
		<div class="container">
			<div class="panel archive">
				<div class="panel_title">
					<h2>Donors
						<?php $year = $curyear = date('n') < 7 ? date('Y') - 1 : date('Y'); ?>
						<?php while ($year > 2009): ?>
							&nbsp;|&nbsp;
							<?php if ((isset($wp_query->query_vars['middstart_donors_year']) && $wp_query->query_vars['middstart_donors_year'] == $year) || (!isset($wp_query->query_vars['middstart_donors_year']) && $year == $curyear)): ?>
								<?php print $year .'-'. ($year+1); ?>
							<?php else: ?>
								<?php $form = isset($wp_query->query_vars['middstart_donors_form']) ? '&middstart_donors_form='. $wp_query->query_vars['middstart_donors_form'] : ''; ?>
								<a href="<?php print get_bloginfo('url'); ?>/donors/?middstart_donors_year=<?php print $year . $form; ?>"><?php print $year .'-'. ($year+1); ?></a>
							<?php endif; ?>
							<?php $year--; ?>
						<?php endwhile; ?>
					</h2>
				</div>
				<?php
					$posts = get_posts(array('numberposts' => -1));
					$forms = array();
					if (isset($wp_query->query_vars['middstart_donors_form'])) {
						$posts = array(get_post($wp_query->query_vars['middstart_donors_form']));
					}
					for ($i = 0; $i < count($posts); $i++) {
						$form = get_meta('middstart-formid', $posts[$i]->ID);
						if (!empty($form)) $forms = array_merge($forms, preg_split('/,/', $form));
					}
					$year = $curyear;
					if (isset($wp_query->query_vars['middstart_donors_year'])) {
						$year = $wp_query->query_vars['middstart_donors_year'];
					}
					$donors = middstart_fetch_get_donors(implode(',', $forms), $year);
				?>
				<table class="cubes donors">
					<tr>
					<?php
						$i = 0;
						foreach($donors as $d => $p) {
							if ($i % 4 == 0 && $i != 0) print '<tr>';
							print '<td class="cube c' . ($i % 16 + 1) . '"><div class="wrapper">' . my_ucwords(strtolower($p->name)) .'<ul class="projects">';
							
							print '</ul></div></td>';
	
							if ($i % 4 == 3 && $i != count($donors) - 1) print '</tr>';
							$i++;
						}
					?>
					</tr>
				</table>
			</div>
		</div>
		<?php get_footer(); ?>