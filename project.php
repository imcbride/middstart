<div class="panel">
  <div class="panel_title">
    <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <?php $teaser = get_meta('middstart-teaser'); ?>
    <?php if (function_exists('sharethis_button')) : ?>
      <div class="share_this">
        <?php  sharethis_button(); ?>
        <a href="<?php the_permalink(); ?>feed/" class="feed" title="Post comments"><img src="<?php print includes_url(); ?>images/rss/Feed-32x32.png" width="32" height="32" /></a>
      </div>
    <?php endif; ?>
  </div>
  <div class="description">
    <table class="layout">
      <tr>
        <td class="overview">
          <?php the_excerpt(); ?>
          <?php $blog = get_meta('middstart-blog'); ?>
          <?php if ($blog) : ?>
            <p><a href="<?php print $blog; ?>">View project website &raquo;</a></p>
          <?php endif; ?>
          <?php if (!is_single()) : ?>
            <p><a href="<?php the_permalink(); ?>">Talk about it &raquo;</a></p>
          <?php else: ?>
            <p><?php comments_popup_link('Talk about it &#187;', 'Talk about it &#187;', 'Talk about it &#187;'); ?></p>
          <?php endif; ?>
          <p><a href="<?php the_permalink(); ?>feed/" title="Post comments">Follow this project &raquo;</a></p>
          <?php $forms = get_meta('middstart-formid'); ?>
          <?php $formid = preg_split('/,/', $forms); ?>
          <?php $participation = get_meta('middstart-participation'); ?>
          <?php $donors = count(middstart_fetch_get_donors($formid[0])); ?>
          <?php $funded = middstart_fetch_get_total($formid[0]); ?>
          <?php $total = (float)get_meta('middstart-total'); ?>
          <?php $p_percent = !empty($participation) ? $donors / $participation * 100 : 0; ?>
          <?php if ($p_percent > 1.5): ?>
            <h3 class="progress">Number of Donors: <?php print $donors . '&nbsp;of&nbsp;' . $participation; ?></h3>
            <div class="progress">
              <div class="progress_complete" style="width: <?php print ($p_percent > 100) ? 100 : $p_percent; ?>%;">
                 <span style="height:26px;"></span>
              </div>
            </div>
          <?php endif; ?>
          <?php $d_percent = !empty($total) ? $funded / $total * 100 : 0; ?>
          <?php if ($d_percent > 1.5): ?>
            <h3 class="progress">Dollars Raised: <?php print round($d_percent, 0) .'%'; ?></h3>
            <div class="progress">
              <div class="progress_complete" style="width: <?php print ($d_percent > 100) ? 100 : $d_percent; ?>%;">
                 <span style="height:26px;"></span>
              </div>
            </div>
          <?php endif; ?>
          <ul class="stats" style="width:250px">
          <?php if ($donors > 0 && $total > 0): ?>
            <li>
              <div class="contributions"><a href="<?php print get_bloginfo('url'); ?>/donors/?middstart_donors_form=<?php the_ID(); ?>"><b><?php print $donors; ?> <?php print $donors > 1 ? 'people</b></a>' : 'person</b></a>'; ?> gave <b>$<?php print number_format($funded, 0, '.', ','); ?></b></div>
            </li>
          <?php endif; ?>
          <?php $days = ceil((strtotime(get_meta('middstart-deadline')) - strtotime('now')) / 86400); ?>
          <?php if ($days > 0 && ($total > 0 || $participation > 0)): ?>
            <li>
              <?php if ($total > 0): ?>
                <div class="remaining"><b>$<?php print ($d_percent > 100) ? 0 : number_format($total - $funded, 0, '.', ','); ?></b> and <b><?php print $days; ?> days</b> to go</div>
              <?php else: ?>
                <div class="remaining"><b><?php print ($p_percent > 100) ? 0 : number_format($participation - $donors, 0, '.', ','); ?> donors</b> and <b><?php print $days; ?> days</b> to go</div>
              <?php endif; ?>
            </li>
          <?php endif; ?>
            <li>
              <a href="<?php print DONATION_FORM . $formid[0]; ?>" class="fund">Fund this project!</a>
            </li>
          </ul>
          <h3>Funding Details</h3>
          <div class="details">
            Deadline: <b><?php print date('M d, Y', strtotime(get_meta('middstart-deadline'))); ?></b><br />
            <?php if ($total > 0) : ?>
              Total needed: <b>$<?php print number_format($total, 0, '.', ','); ?></b>
            <?php else: ?>
              <?php if ($participation > 0): ?>
                Total needed: <b><?php print number_format($participation, 0, '.', ','); ?> donors</b>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </td>
        <td>
          <h3>People</h3>
          <div class="people">
            <?php $people = get_meta('middstart-people'); ?>
            <?php if (trim($people) != '') : ?>
              <?php print $people; ?>
            <?php else: ?>
              <?php $i = new CoAuthorsIterator(); ?>
              <?php $i->iterate(); ?>
              <?php while($i->iterate()): ?>
                <?php the_author(); ?>
                <?php print $i->is_last() ? '' : '<br />'; ?>
              <?php endwhile; ?>
            <?php endif; ?>
          </div>
          <?php $related = get_meta('middstart-links'); ?>
          <?php if ($related) : ?>
            <h3>Related Links</h3>
            <div class="related"><?php print $related; ?></div>
          <?php endif; ?>
        </td>
        <td width="300">
          <h3>Media</h3>
          <?php $GLOBALS["customVideoHeight"] = 200; ?>
          <?php $GLOBALS["customVideoWidth"] = 300; ?>
          <?php $media = get_meta('middstart-media'); ?>
          <?php
            $printed = FALSE;
            
            if (!$printed && function_exists('middmedia_plugin') && $media) {
              $video = middmedia_plugin($media);
              if ($video != $media) { print $video; $printed = TRUE; }
            }
            
            if (!$printed && function_exists('youtube_plugin') && $media) {
              $video = youtube_plugin($media);
              if ($video != $media) { print $video; $printed = TRUE; }
            }
            
            if (!$printed && $media) {
              print $media;
            }
          ?>
          <?php $downloads = get_meta('middstart-downloads'); ?>
          <?php if ($downloads) : ?>
            <h3>Downloads</h3>
            <div class="downloads"><?php print $downloads; ?></div>
          <?php endif; ?>
        </td>
      </tr>
    </table>
  </div>
  <?php if (is_single()) : ?>
    <div id="the_content"><?php the_content(); ?></div>
    <hr />
    <div class="container">
      <?php comments_template(); ?>
    </div>
    <div id="connections">
      <table class="layout">
        <tbody><tr>
          <td id="connections_header">
            <h2>Connections</h2>
          </td>
          <td>
            <div id="project_themes">
              This project is included in these themes:
              <?php the_category(); ?>
            </div>
          </td>
          <td>
            <div id="project_tags">
              <strong>Midd</strong>Tags
              <div class="tags"><?php the_tags(''); ?></div>
            </div>
          </td>
        </tr>
      </tbody></table>
    </div>
  <?php endif; ?>
</div>
<div class="clear break"></div>