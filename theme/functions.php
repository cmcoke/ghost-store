<?php

/**
 * ghost functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ghost
 */

if (! defined('GHOST_VERSION')) {
  /*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
  define('GHOST_VERSION', '0.1.0');
}

if (! defined('GHOST_TYPOGRAPHY_CLASSES')) {
  /*
	 * Set Tailwind Typography classes for the front end, block editor and
	 * classic editor using the constant below.
	 *
	 * For the front end, these classes are added by the `ghost_content_class`
	 * function. You will see that function used everywhere an `entry-content`
	 * or `page-content` class has been added to a wrapper element.
	 *
	 * For the block editor, these classes are converted to a JavaScript array
	 * and then used by the `./javascript/block-editor.js` file, which adds
	 * them to the appropriate elements in the block editor (and adds them
	 * again when they’re removed.)
	 *
	 * For the classic editor (and anything using TinyMCE, like Advanced Custom
	 * Fields), these classes are added to TinyMCE’s body class when it
	 * initializes.
	 */
  define(
    'GHOST_TYPOGRAPHY_CLASSES',
    'prose prose-neutral max-w-none prose-a:text-primary'
  );
}

if (! function_exists('ghost_setup')) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
  function ghost_setup()
  {
    /*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/directory.
		 * If you're building a theme based on ghost, use a find and replace
		 * to change 'ghost' to the name of your theme in all the template files.
		 */
    load_theme_textdomain('ghost', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
    add_theme_support('title-tag');

    /*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus(
      array(
        'menu-1' => __('Primary', 'ghost'),
        'menu-2' => __('Footer Menu', 'ghost'),
      )
    );

    /*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
    add_theme_support(
      'html5',
      array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
      )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for editor styles.
    add_theme_support('editor-styles');

    // Enqueue editor styles.
    add_editor_style('style-editor.css');

    // Add support for responsive embedded content.
    add_theme_support('responsive-embeds');

    // Remove support for block templates.
    remove_theme_support('block-templates');
  }
endif;
add_action('after_setup_theme', 'ghost_setup');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ghost_widgets_init()
{
  register_sidebar(
    array(
      'name'          => __('Footer', 'ghost'),
      'id'            => 'sidebar-1',
      'description'   => __('Add widgets here to appear in your footer.', 'ghost'),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    )
  );
}
add_action('widgets_init', 'ghost_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function ghost_scripts()
{
  wp_enqueue_style('ghost-style', get_stylesheet_uri(), array(), GHOST_VERSION);
  // Enqueue the Google Font 'Teko'.
  wp_enqueue_style('ghost-teko-font', '//fonts.googleapis.com/css2?family=Teko:wght@300;400;500;600;700&display=swap', array(), null, 'all');
  wp_enqueue_script('ghost-script', get_template_directory_uri() . '/js/script.min.js', array(), GHOST_VERSION, true);

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'ghost_scripts');

/**
 * Enqueue the block editor script.
 */
function ghost_enqueue_block_editor_script()
{
  $current_screen = function_exists('get_current_screen') ? get_current_screen() : null;

  if (
    $current_screen &&
    $current_screen->is_block_editor() &&
    'widgets' !== $current_screen->id
  ) {
    wp_enqueue_script(
      'ghost-editor',
      get_template_directory_uri() . '/js/block-editor.min.js',
      array(
        'wp-blocks',
        'wp-edit-post',
      ),
      GHOST_VERSION,
      true
    );
    wp_add_inline_script('ghost-editor', "tailwindTypographyClasses = '" . esc_attr(GHOST_TYPOGRAPHY_CLASSES) . "'.split(' ');", 'before');
  }
}
add_action('enqueue_block_assets', 'ghost_enqueue_block_editor_script');

/**
 * Add the Tailwind Typography classes to TinyMCE.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function ghost_tinymce_add_class($settings)
{
  $settings['body_class'] = GHOST_TYPOGRAPHY_CLASSES;
  return $settings;
}
add_filter('tiny_mce_before_init', 'ghost_tinymce_add_class');

/**
 * Limit the block editor to heading levels supported by Tailwind Typography.
 *
 * @param array  $args Array of arguments for registering a block type.
 * @param string $block_type Block type name including namespace.
 * @return array
 */
function ghost_modify_heading_levels($args, $block_type)
{
  if ('core/heading' !== $block_type) {
    return $args;
  }

  // Remove <h1>, 5 and <h6>.
  $args['attributes']['levelOptions']['default'] = array(2, 3, 4);

  return $args;
}
add_filter('register_block_type_args', 'ghost_modify_heading_levels', 10, 2);

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Include custom WooCommerce functions safely.
 *
 * We hook this into 'after_setup_theme' to ensure it loads after the main theme
 * and any core functionalities are set up. Priority 11 ensures it runs after
 * WooCommerce support is declared (which is usually at priority 10).
 */
function ghost_include_wc_custom_functions() {
	$file_path = get_template_directory() . '/inc/woocommerce-custom-functions.php';
	// Restore the require_once to execute the file.
	require_once $file_path;
}
add_action( 'after_setup_theme', 'ghost_include_wc_custom_functions', 11 );


/**
 * WooCommerce element modifications.
 */
require_once get_template_directory() . '/inc/wc-modifications.php';

/**
 * Enable WooCommerce Support for the Ghost Theme
 */
function ghost_add_woocommerce_support()
{
  add_theme_support('woocommerce', array(
    'thumbnail_image_width' => 450, // Sets the width for the shop 'grid' images.
    'single_image_width'    => 800, // Sets the width for the main product page image.
    'product_grid'          => array(
      'default_rows'    => 3,    // Sets default grid display.
      'min_rows'        => 1,
      'max_rows'        => 6,
      'default_columns' => 3,    // Works well with Tailwind's 'grid-cols-3'.
      'min_columns'     => 1,
      'max_columns'     => 5,
    ),
  ));
}
// 6. Connect our function to the 'after_setup_theme' hook.
// This is the standard WP "launch" hook for theme-wide configurations.
add_action('after_setup_theme', 'ghost_add_woocommerce_support');


/**
 * Remove 'Add to Cart' button from product loops.
 *
 * This function specifically targets the 'woocommerce_after_shop_loop_item' action hook
 * to remove the 'Add to Cart' button that WooCommerce automatically adds to products
 * displayed in loops (e.g., on the main shop page, category archives, search results).
 * It uses 'remove_action' to detach the 'woocommerce_template_loop_add_to_cart' function.
 *
 * IMPORTANT: This only affects product loops. The 'Add to Cart' button on
 * individual product pages (single product view) remains unaffected, as it is
 * hooked into different WooCommerce actions.
 */
function ghost_remove_add_to_cart_button()
{
  // This line precisely targets and removes the 'woocommerce_template_loop_add_to_cart' function
  // from the 'woocommerce_after_shop_loop_item' action hook.
  // The '10' is the priority at which the function was originally added.
  remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}

// This hooks our custom function into the 'init' action, which runs early in the WordPress load process.
// Using 'init' ensures this removal happens before WooCommerce starts displaying products.
add_action('init', 'ghost_remove_add_to_cart_button');



/**
 * Enable Woocommerce MCP
 */
add_filter('woocommerce_features', function ($features) {
  $features['mcp_integration'] = true;
  return $features;
});


/**
 * Woocommerce MCP requests require HTTPS by default. For local development is disabled using the filter hook below,
 */
add_filter('woocommerce_mcp_allow_insecure_transport', '__return_true');