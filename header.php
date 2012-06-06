<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title><?php print get_bloginfo('name'); ?></title>
	<link href="http://web.middlebury.edu/development/tools/1d2010/Stylesheets/style.css" rel="stylesheet" />
	<link href="http://web.middlebury.edu/development/tools/1d2010/Stylesheets/style-custom.css" rel="stylesheet" />
	<link href="<?php print get_stylesheet_uri(); ?>" rel="stylesheet" />
	<!--[if lt IE 7]><link rel="stylesheet" href="http://web.middlebury.edu/development/tools/1d2010/Stylesheets/ie6.css" /><![endif]-->
	<!--[if IE 7]><link rel="stylesheet" href="http://web.middlebury.edu/development/tools/1d2010/Stylesheets/ie7.css" /><![endif]-->
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
	<meta name="author" content="Middlebury College" />
	<meta name="keywords" content="middlebury college, liberal arts, education, higher education, middlebury, vermont" />
	<meta name="description" content="middlebury college" />
	<meta name="robots" content="all" />
	<?php wp_head(); ?>
</head>
<body class="middstart">
	<a href="#content" class="skiplink">Skip to content</a>
	<div class="container">
		<form id="search" action="http://www.middlebury.edu/search" method="get" target="_top">
			<label for="search_query">Search Midd</label>
			<input type="text" id="search_query" class="go_query" name="q2" />
			<input type="submit" id="search_submit" value="Go" />
			<input type="hidden" id="go_ajax_search_url" value="http://www.middlebury.edu/go/search" />
		</form>
		<div id="wordmark">
			<a href="http://www.middlebury.edu/"><img src="http://www.middlebury.edu/sites/all/themes/midd/images/design/logo.gif" width="206" height="39" alt="Middlebury" /></a>
		</div>
	</div>
	<div id="content" class="container">
		<h1 id="tagline"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('description'); ?></a></h1>
    <ul id="labnav" class="menu">
      <?php $pages = get_pages(array('sort_column' => 'menu_order', 'parent' => 0)); ?>
      <?php foreach($pages as $page) : ?>
        <li style="width:<?php print 960/count($pages); ?>px;" <?php if (!is_home() && strpos($page->guid, $_SERVER['REQUEST_URI']) !== FALSE) print ' class="open"'; ?>>
          <a href="<?php print bloginfo('url') . '/' . $page->post_name; ?>"><?php print $page->post_title; ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
