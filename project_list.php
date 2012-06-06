<?php if (have_posts()) : ?>
  <?php $i = $print_success = $print_ongoing = 0; ?>
  <?php while (have_posts()) : ?>
    <?php the_post(); ?>
      <?php $color = get_meta('middstart-color'); ?>
      <?php if (!$color) $i++; ?>
      <div class="middstart_teaser color<?php print $color ? substr($color, 0, 2) : str_pad($i, 2, '0', STR_PAD_LEFT); ?>">
      	<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium'); ?>
      	<?php if (!empty($image[0])): ?>
	        <a href="<?php the_permalink(); ?>"><img src="<?php print $image[0]; ?>" alt="MiddSTART teaser" width="200" height="200" /></a>
	      <?php endif; ?>
      <div class="middstart_teaser_headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
      <?php $funded = middstart_fetch_get_total(get_meta('middstart-formid')); ?>
      <?php $total = (float)get_meta('middstart-total'); ?>
      <?php $percent = $funded / $total * 100; ?>
      <h4>Total needed: <b><?php print $percent >= 100 && strpos(get_the_title(), 'Scholarship') === FALSE ? 'FUNDED!' : '$'. number_format($total, 0, '.', ','); ?></b></h4>
      <h4>Deadline: <b><?php print date('M d, Y', strtotime(get_meta('middstart-deadline'))); ?></b></h4>
      <?php $days = ceil((strtotime(get_meta('middstart-deadline')) - strtotime('now')) / 86400); ?>
      <?php if ($percent < 100 && $days > 0): ?>
      	<div class="remaining"><b>$<?php print ($percent > 100) ? 0 : number_format($total - $funded, 0, '.', ','); ?></b> and <b><?php print $days; ?> days</b> to go</div>
      <?php endif; ?>
      <div class="middstart_teaser_label"><a href="<?php the_permalink(); ?>">Details &raquo;</a></div>
      <div class="middstart_arrow"></div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>