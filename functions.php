<?php

// Database table where nightly reports of donations are collected.
define('DONORS_TABLE', 'wp_1505_middstart_fetch');
define('DONATION_FORM', 'https://secure.www.alumniconnections.com/olc/pub/MDR/onlinegiving/showGivingForm.jsp?form_id=');

/**
 * Post thumbnails, or featured images, are used for the 200x200 images on the home page.
 */
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 200, 200 );

/**
 * Custom Header image support functions.
 */
define('HEADER_TEXTCOLOR', 'ffffff');
define('HEADER_IMAGE', '%s/images/header.jpg');
define('HEADER_IMAGE_WIDTH', 960);
define('HEADER_IMAGE_HEIGHT', 67);

add_custom_image_header('header_style', 'admin_header_style');

function admin_header_style() {
  ?><style type="text/css">
    #heading {
      width: <?php print HEADER_IMAGE_WIDTH; ?>px;
      height: <?php print HEADER_IMAGE_HEIGHT; ?>px;
      background:no-repeat;
    }
  </style><?php
}

function header_style() {
  ?><style type="text/css">
    #tagline {
      background: url(<?php header_image(); ?>);
    }
  </style><?php
}

/**
 * Custom query variable support functions.
 */
add_filter('query_vars', 'add_query_vars');
add_filter('rewrite_rules_array', 'add_rewrite_rules');

function add_query_vars($vars) {
  // Username of an author to fetch posts by that author.
	$vars[] = "middstart_authors_post";

  // The form id of a particular donation form.
  $vars[] = "middstart_donors_form";

  // The fiscal year of a donation.
  $vars[] = "middstart_donors_year";
	return $vars;
}

function add_rewrite_rules($rules) {
	$newrules = array('middstart_authors_post/([^/]+)/?$' => 'index.php?pagename=people&middstart_authors_post=$matches[1]');
	$rules = $newrules + $rules;
	return $rules;
}

/**
 * Donations query support functions.
 */
function middstart_fetch_get_total($form) {
  global $wpdb;
  return $wpdb->get_var($wpdb->prepare("SELECT SUM(amount) FROM " . DONORS_TABLE . " WHERE form = %d", $form));
}

function middstart_fetch_get_donors($form, $year = NULL) {
  global $wpdb;
  if (is_null($year)) {
	  return $wpdb->get_results("SELECT DISTINCT CONCAT(first, ' ', last) AS name FROM " . DONORS_TABLE . " WHERE form IN (". $form . ") AND amount > 0 ORDER BY last, first");
	} else {
	  return $wpdb->get_results("SELECT DISTINCT CONCAT(first, ' ', last) AS name FROM " . DONORS_TABLE . " WHERE form IN (". $form . ") AND amount > 0 AND date > '". date('Y-m-d', strtotime('July 1, '. $year)) ."' AND date < '". date('Y-m-d', strtotime('July 1, '. ($year+1))) ."' ORDER BY last, first");
	}
}

/* From http://www.php.net/manual/en/function.ucwords.php#60064 */
function my_ucwords($str) {
   // exceptions to standard case conversion
   $all_uppercase = '';
   $all_lowercase = 'De La|De Las|Der|Van De|Van Der|Vit De|Von|Or|And';

   $prefixes = 'Mc';
   $suffixes = "'S";

   // captialize all first letters
   $str = preg_replace('/\\b(\\w)/e', 'strtoupper("$1")', strtolower(trim($str)));

   if ($all_uppercase) {
       // capitalize acronymns and initialisms e.g. PHP
       $str = preg_replace("/\\b($all_uppercase)\\b/e", 'strtoupper("$1")', $str);
   }
   if ($all_lowercase) {
       // decapitalize short words e.g. and
       if ($is_name) {
           // all occurences will be changed to lowercase
           $str = preg_replace("/\\b($all_lowercase)\\b/e", 'strtolower("$1")', $str);
       } else {
           // first and last word will not be changed to lower case (i.e. titles)
           $str = preg_replace("/(?<=\\W)($all_lowercase)(?=\\W)/e", 'strtolower("$1")', $str);
       }
   }
   if ($prefixes) {
       // capitalize letter after certain name prefixes e.g 'Mc'
       $str = preg_replace("/\\b($prefixes)(\\w)/e", '"$1".strtoupper("$2")', $str);
   }
   if ($suffixes) {
       // decapitalize certain word suffixes e.g. 's
       $str = preg_replace("/(\\w)($suffixes)\\b/e", '"$1".strtolower("$2")', $str);
   }
   return $str;
}

/* Everything from here on is from the Get Author's Comments plugin */
if (!defined('HTML'))
{
    define('HTML', 'HTML', false);
}

/**
 * Retrieves comments posted by a user.
 * 
 * Usage :
 * <ul>
 *   <li>Within loop, retrieves mehdi's comments. Without loop, retrieves 
 *      all comments that mehdi wrote.
 *     <ul>
 *       <li><code>ppm_get_author_comments('mehdi', 'foo@example.com')</code></li>
 *       <li><code>ppm_get_author_comments('mehdi', array('foo@example.com', 'bar@example.com'))</code></li>
 *       <li><code>ppm_get_author_comments('mehdi', 'foo@example.com', null, 'output=ARRAY_N')</code></li>
 *     </ul>
 *   </li>
 *   <li>Retrieves comments wrote by a user in the post of ID number 9:
 *     <ul>
 *       <li><code>ppm_get_author_comments('mehdi', 'foo@example.com', 9)</code></li>
 *       <li><code>ppm_get_author_comments('mehdi', 'foo@example.com', 9, 'orderby=content&number=1')</code></li>
 *     </ul>
 *   </li>
 * </ul>
 * 
 * @uses wp_list_comments
 * @global wpdb $wpdb
 * @global wpdb $post
 * 
 * @param string $author_name The author's name
 * @param string|array $author_email The author's e-mail(s)
 * @param int    $postID An optional post ID
 * @param array  $args Search and formatting options ({@link wp_list_comments()})
 * 
 * @return string|array If output parameter is HTML, returns a (x)HTML formated list.
 */
function ppm_get_author_comments($author_name, $author_email, $postID = null, $args = array())
{

    // {{{ Checks parameters

    $author_name = trim($author_name);
    if (empty($author_name))
    {
        return;
    }

    if (is_array($author_email))
    {
        $emailsCount = count($author_email);
        if (0 === $emailsCount)
        {
            return;
        }
    }

    // }}}

    global $wpdb, $post;

    $defaults = array(
        'all'       => false,
        'status'    => '',
        'orderby'   => 'date',
        'order'     => 'DESC',
        'number'    => '',
        'offset'    => '',
        'output'    => OBJECT
    );

    $args = wp_parse_args($args, $defaults);
    $args['all'] = (boolean) $args['all'];
    $buf  = '';

    // {{{ Prepares SQL query

    $sql  = 'SELECT * FROM ' . $wpdb->comments . ' WHERE comment_author = %s';

    if (false === $args['all'])
    {
        if (null === $postID)
        {
            if (in_the_loop())
            {
                $sql .= ' AND comment_post_ID = ' . $post->ID;
            }
        }
        else
        {
            $sql .= ' AND comment_post_ID = ' . absint($postID);
        }
    }

    switch (strtolower($args['status']))
    {
        case 'hold':
            $sql .= ' AND comment_approved = \'0\'';
            break;

        case 'approve':
            $sql .= ' AND comment_approved = \'1\'';
            break;

        case 'spam':
            $sql .= ' AND comment_approved = \'spam\'';
            break;

        default:
            $sql .= ' AND (comment_approved = \'0\' OR comment_approved = \'1\')';
            break;
    }

    $sql .= ' AND comment_author_email';
    if (isset($emailsCount))
    {
        if (1 < $emailsCount)
        {
            array_walk($author_email, array($wpdb, 'escape_by_ref'));
            $list = str_repeat("'%s',", $emailsCount);
            $sql .= ' IN ('
                 . substr_replace(vsprintf($list, $author_email), '', -1, 1)
                 . ')';
            unset($list);
        }
        else
        {
            $sql .= ' = ' . $wpdb->prepare('%s', $author_email[0]);
        }
    }
    else
    {
        $sql .= ' = ' . $wpdb->prepare('%s', $author_email);
    }

    $args['orderby'] = addslashes_gpc(urldecode($args['orderby']));
    $orderby_array   = explode(' ', $args['orderby']);

    if (empty($orderby_array))
    {
        $orderby_array[] = $args['orderby'];
    }

    $orderby_array   = array_map('strtolower', $orderby_array);
    $args['orderby'] = '';

    while (list(, $orderby) = each($orderby_array))
    {
        switch ($orderby)
        {
            case 'id': // compatibility with 1.0.0
            case 'comment_id':
                $orderby = 'comment_ID';
                break;

            case 'post': // compatibility with 1.0.0
            case 'post_id':
                $orderby = 'comment_post_ID';
                break;

            case 'status':
                $orderby = 'comment_approved';
                break;

            case 'rand':
                $orderby = 'RAND()';
                break;

            case 'type':
                $orderby = 'comment_type';
                break;

            case 'content':
                $orderby = 'comment_content';
                break;

            case 'date':
            default:
                $orderby = 'comment_date_gmt';
                break;
        }

        $args['orderby'] .= $orderby . ',';
    }
    unset($allowed_keys, $orderby, $orderby_array);

    $args['orderby'] = substr_replace($args['orderby'], '', -1, 1);

    $sql .= ' ORDER BY '
         . $args['orderby']
         . ' '
         . (('ASC' == strtoupper($args['order'])) ? 'ASC' : 'DESC');

    if ('' != $args['number'])
    {
        if ('' != $args['offset'])
        {
            $sql .= ' LIMIT ' . absint($args['offset']) . ',' . absint($args['number']);
        }
        else
        {
            $sql .= ' LIMIT ' . absint($args['number']);
        }
    }

    // }}}
    // {{{ Go!

    $comments = $wpdb->get_results($wpdb->prepare($sql, $author_name));

    // }}}
    // {{{ Determines output format

    switch (strtoupper($args['output']))
    {
        case ARRAY_A:
            $_comments = array();

            foreach ($comments as $k => $comment)
            {
                $_comments[$k] = get_object_vars($comment);
            }

            $buf = $_comments;
            break;

        case ARRAY_N:
            $_comments = array();

            foreach ($comments as $k => $comment)
            {
                $_comments[$k] = array_values(get_object_vars($comment));
            }

            $buf = $_comments;
            break;

        case 'HTML':
            unset($args['status'], $args['orderby'], $args['order'],
                  $args['number'], $args['offset'], $args['output']
            );
            ob_start();
            wp_list_comments($args, $comments);
            $buf = ob_get_clean();
            break;

        default:
        case OBJECT:
            $buf = $comments;
            break;
    }

    // }}}

    return apply_filters('ppm_get_author_comments', $buf);
}

/**
 * Displays comments posted by a user.
 *
 * @uses apply_filters() Calls 'ppm_get_author_comments' on author's comments before displaying
 * 
 * @param string $author_name The author's name
 * @param string|array $author_email The author's e-mail
 * @param int    $postID An optional post ID
 * @param array  $args Formatting options ({@link ppm_get_author_comments()} and {@link wp_list_comments()})
 * 
 * @return void
 */
function ppm_author_comments($author_name, $author_email, $postID = null, $args = array())
{
    $args     = wp_parse_args($args, array('output' => 'HTML'));
    $comments = apply_filters(
        'ppm_author_comments',
        ppm_get_author_comments($author_name, $author_email, $postID, $args)
    );

    echo $comments;
}

?>