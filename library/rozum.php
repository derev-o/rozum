<?php
/* Welcome to Rozum :)
This is the core Rozum file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/rozum/

  - head cleanup (remove rsd, uri links, junk css, ect)
  - enqueueing scripts & styles
  - theme support functions
  - custom menu output & fallbacks
  - related post function
  - page-navi function
  - removing <p> from around images
  - customizing the post excerpt

*/

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

function rozum_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	add_filter( 'style_loader_src', 'rozum_remove_wp_ver_css_js', 9999 );
	// remove Wp version from scripts
	add_filter( 'script_loader_src', 'rozum_remove_wp_ver_css_js', 9999 );

} /* end rozum head cleanup */

// A better title
// http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
function rw_title( $title, $sep, $seplocation ) {
  global $page, $paged;

  // Don't affect in feeds.
  if ( is_feed() ) return $title;

  // Add the blog's name
  if ( 'right' == $seplocation ) {
    $title .= get_bloginfo( 'name' );
  } else {
    $title = get_bloginfo( 'name' ) . $title;
  }

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );

  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title .= " {$sep} {$site_description}";
  }

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 ) {
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
  }

  return $title;

} // end better title

// remove WP version from RSS
function rozum_rss_version() { return ''; }

// remove WP version from scripts
function rozum_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

// remove injected CSS for recent comments widget
function rozum_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

// remove injected CSS from recent comments widget
function rozum_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}

// remove injected CSS from gallery
function rozum_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}


/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function rozum_scripts_and_styles() {

  global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

  if (!is_admin()) {

		// modernizr (without media query polyfill)
		wp_register_script( 'rozum-modernizr', get_stylesheet_directory_uri() . '/library/js/libs/modernizr.custom.min.js', array(), '2.5.3', false );		
		// classie.js
		wp_register_script( 'rozum-classie', get_stylesheet_directory_uri() . '/library/js/libs/classie.js', array(), '', true );
		
		//***** PERSPECTIVE MENU *****//		
		//*** JS ***//
		
		// perspective menu.js
	  	wp_register_script( 'rozum-perspective-js', get_stylesheet_directory_uri() . '/library/js/libs/perspective/menu.js', array(), '', true );
		
		//*** CSS ***//
		
		// register component stylesheet
	  	wp_register_style( 'rozum-perspective-component', get_stylesheet_directory_uri() . '/library/css/perspective/component.css', array(), '', 'all' );

		//***** BOOK ELEMENTS *****//
		//*** JS ***//
		
		// bookshelf.js		
		wp_register_script( 'rozum-bookshelf', get_stylesheet_directory_uri() . '/library/js/libs/bookshelf.js', array(), '', true );		
		
		// bookblock.min.js		
		wp_register_script( 'rozum-bookblock', get_stylesheet_directory_uri() . '/library/js/libs/bookblock.min.js', array(), '', true );
		
	  	// single-book.js		
	  	wp_register_script( 'rozum-single-book-js', get_stylesheet_directory_uri() . '/library/js/libs/single-book.js', array(), '', true );
		
		//*** CSS ***//
		
		// register bookblock stylesheet
		wp_register_style( 'rozum-bookblock-stylesheet', get_stylesheet_directory_uri() . '/library/css/bookblock.css', array(), '', 'all' );
		
		// register component stylesheet
		wp_register_style( 'rozum-component-stylesheet', get_stylesheet_directory_uri() . '/library/css/component.css', array(), '', 'all' );

		// single book stylesheet
	  	wp_register_style( 'rozum-single-book', get_stylesheet_directory_uri() . '/library/css/single-book.css', array(), '', 'all' );


		//***** BOOK ELEMENTS *****//
	  	//*** JS ***//

	  	// bookshelf.js
	 	 wp_register_script( 'rozum-sm-js', get_stylesheet_directory_uri() . '/library/js/libs/smart-menu/jquery.smartmenus.js', array(), '', true );

		//*** CSS ***//

	  	// register smart-menu stylesheet
		wp_register_style( 'rozum-sm-core', get_stylesheet_directory_uri() . '/library/css/smart-menu/sm-core-css.css', array(), '', 'all' );

	  	// register smart-menu simple theme stylesheet
		wp_register_style( 'rozum-sm-simple', get_stylesheet_directory_uri() . '/library/css/smart-menu/sm-simple.css', array(), '', 'all' );
		
		// register smart-menu simple theme stylesheet
		wp_register_style( 'rozum-sm-perspective', get_stylesheet_directory_uri() . '/library/css/smart-menu/sm-perspective.css', array(), '', 'all' );
		
		// Material icons
		wp_register_style( 'material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), '', 'all' );


		
		
		
	  	// bootstrap.js
	  	//wp_register_script( 'rozum-bootstrap-js', get_stylesheet_directory_uri() . '/library/js/libs/bootstrap.js', array(), '', false );
	  	//wp_register_script( 'rozum-bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', array(), '', false );
	  	//wp_register_script( 'rozum-bootstrap-smartm-js', get_stylesheet_directory_uri() . '/library/js/libs/jquery.smartmenus.min.js', array(), '', false );
	  	//wp_register_script( 'rozum-bootstrap-smartmenu-js', get_stylesheet_directory_uri() . '/library/js/libs/jquery.smartmenus.bootstrap.min.js', array(), '', false );





	    // register main stylesheet
		wp_register_style( 'rozum-stylesheet', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' );
        // register bootstrap stylesheet
		wp_register_style( 'rozum-bootstrap', get_stylesheet_directory_uri() . '/library/css/bootstrap/bootstrap.css', array(), '', 'all' );

	  	// register bootstrap responsive stylesheet
	  	//wp_register_style( 'rozum-bootstrap-responsive', get_stylesheet_directory_uri() . '/library/css/bootstrap/bootstrap-responsive.css', array(), '', 'all' );
	  	//wp_register_style( 'rozum-bootstrap-theme', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', array(), '', 'all' );
	    //wp_register_style( 'rozum-bootstrap-smartmenu', get_stylesheet_directory_uri() . '/library/css/bootstrap/jquery.smartmenus.bootstrap.css', array(), '', 'all' );





		// ie-only style sheet
		wp_register_style( 'rozum-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );

    // comment reply script for threaded comments
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
		  wp_enqueue_script( 'comment-reply' );
    }

		//adding scripts file in the footer
		wp_register_script( 'rozum-js', get_stylesheet_directory_uri() . '/library/js/scripts.js', array( 'jquery' ), '', true );

	  	//wp_register_script( 'rozum-jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', array( 'jquery' ), '', true );



		//***** ENQUEUE STYLES AND SCRIPTS *****//
		wp_enqueue_style( 'rozum-sm-core' );
		wp_enqueue_style( 'rozum-sm-simple' );
		wp_enqueue_style( 'rozum-sm-perspective' );
		wp_enqueue_style( 'rozum-bootstrap' );
	  	wp_enqueue_style( 'rozum-bootstrap-responsive' );
	  	wp_enqueue_style( 'rozum-bootstrap-theme' );
	  	//wp_enqueue_style( 'rozum-bootstrap-smartmenu' );

		wp_enqueue_style( 'rozum-stylesheet' );
		wp_enqueue_style( 'rozum-bookblock-stylesheet' );
		wp_enqueue_style( 'rozum-component-stylesheet' );
	  	wp_enqueue_style( 'rozum-perspective-component' );
	  	wp_enqueue_style( 'rozum-single-book' );
	  	wp_enqueue_style( 'material-icons' );

		wp_enqueue_style( 'rozum-ie-only' );

		$wp_styles->add_data( 'rozum-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet

		
		wp_enqueue_script( 'rozum-modernizr' );
		
		wp_enqueue_script( 'rozum-classie' );
		wp_enqueue_script( 'rozum-bookshelf' );
		wp_enqueue_script( 'rozum-bookblock' );
	  	wp_enqueue_script( 'rozum-perspective-js' );
		/*
		I recommend using a plugin to call jQuery
		using the google cdn. That way it stays cached
		and your site will load faster.
		*/
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'rozum-sm-js' );
	  	wp_enqueue_script( 'rozum-single-book-js' );
	  	//wp_enqueue_script( 'rozum-jquery' );

		wp_enqueue_script( 'rozum-js' );
	  	//wp_enqueue_script( 'rozum-bootstrap-js' );
	  	//wp_enqueue_script( 'rozum-bootstrap-smartm-js' );
	  	//wp_enqueue_script( 'rozum-bootstrap-smartmenu-js' );

	}
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function rozum_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	// wp custom background (thx to @bransonwerner for update)
	add_theme_support( 'custom-background',
	    array(
	    'default-image' => '',    // background image default
	    'default-color' => '',    // background color default (dont add the #)
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			'gallery',           // gallery of images
			'link',              // quick link to other site
			'image',             // an image
			'quote',             // a quick quote
			'status',            // a Facebook like status update
			'video',             // video
			'audio',             // audio
			'chat'               // chat transcript
		)
	);

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'main-nav' => __( 'The Main Menu', 'rozumtheme' ),   // main nav in header
			'footer-links' => __( 'Footer Links', 'rozumtheme' ) // secondary nav in footer
		)
	);

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form'
	) );

} /* end rozum theme support */


/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using rozum_related_posts(); )
function rozum_related_posts() {
	echo '<ul id="rozum-related-posts">';
	global $post;
	$tags = wp_get_post_tags( $post->ID );
	if($tags) {
		foreach( $tags as $tag ) {
			$tag_arr .= $tag->slug . ',';
		}
		$args = array(
			'tag' => $tag_arr,
			'numberposts' => 5, /* you can change this to show more */
			'post__not_in' => array($post->ID)
		);
		$related_posts = get_posts( $args );
		if($related_posts) {
			foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
				<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; }
		else { ?>
			<?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'rozumtheme' ) . '</li>'; ?>
		<?php }
	}
	wp_reset_postdata();
	echo '</ul>';
} /* end rozum related posts function */

/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function rozum_page_navi() {
  global $wp_query;
  $bignum = 999999999;
  if ( $wp_query->max_num_pages <= 1 )
    return;
  echo '<nav class="pagination">';
  echo paginate_links( array(
    'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
    'format'       => '',
    'current'      => max( 1, get_query_var('paged') ),
    'total'        => $wp_query->max_num_pages,
    'prev_text'    => '&larr;',
    'next_text'    => '&rarr;',
    'type'         => 'list',
    'end_size'     => 3,
    'mid_size'     => 3
  ) );
  echo '</nav>';
} /* end page navi */

/*********************
RANDOM CLEANUP ITEMS
*********************/

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function rozum_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link
function rozum_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'rozumtheme' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'rozumtheme' ) .'</a>';
}


/*********************
ROZUM WALKER CLASS
 *********************/

function rozum_nav_wrap() {
	// default value of 'items_wrap' is <ul id="%1$s" class="%2$s">%3$s</ul>'
  // open the <ul>, set 'menu_class' and 'menu_id' values
  $wrap  = '<ul id="%1$s" class="%2$s">';
  // the static link 
  $wrap .= '<li class="my-static-link"><a id="showMenu"><i class="medium material-icons">menu</i></a></li>';
  // get nav items as configured in /wp-admin/
  $wrap .= '%3$s';
  // close the <ul>
  $wrap .= '</ul>';
  // return the result
  
  return $wrap;
}


/*********************
BOOTSTRAP WALKER CLASS
 *********************/
class wp_bootstrap_navwalker extends Walker_Nav_Menu {
	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
	}
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
		} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
		} else {
			$class_names = $value = '';
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			if ( $args->has_children )
				$class_names .= ' dropdown';
			if ( in_array( 'current-menu-item', $classes ) )
				$class_names .= ' active';
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			$output .= $indent . '<li' . $id . $value . $class_names .'>';
			$atts = array();
			$atts['title']  = ! empty( $item->title )	? $item->title	: '';
			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';
			// If item has_children add atts to a.
			if ( $args->has_children && $depth === 0 ) {
				$atts['href']   		= ! empty( $item->url ) ? $item->url : '';
				$atts['data-toggle']	= 'dropdown';
				$atts['class']			= 'has-submenu';
				$atts['aria-haspopup']	= 'true';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			}
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			$item_output = $args->before;
			/*
			 * Glyphicons
			 * ===========
			 * Since the the menu item is NOT a Divider or Header we check the see
			 * if there is a value in the attr_title property. If the attr_title
			 * property is NOT null we apply it as the class name for the glyphicon.
			 */
			if ( ! empty( $item->attr_title ) )
				$item_output .= '<a'. $attributes .'><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
			else
				$item_output .= '<a'. $attributes .'>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="sub-arrow"></span></a>' : '</a>';
			$item_output .= $args->after;
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element )
			return;
		$id_field = $this->db_fields['id'];
		// Display this element.
		if ( is_object( $args[0] ) )
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {
			extract( $args );
			$fb_output = null;
			if ( $container ) {
				$fb_output = '<' . $container;
				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';
				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';
				$fb_output .= '>';
			}
			$fb_output .= '<ul';
			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';
			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';
			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';
			if ( $container )
				$fb_output .= '</' . $container . '>';
			echo $fb_output;
		}
	}
}



?>
