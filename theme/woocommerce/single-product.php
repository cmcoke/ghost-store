<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

// This line ensures that the file cannot be accessed directly from the browser,
// which is a standard WordPress security measure.
if ( ! defined( 'ABSPATH' ) ) {
    // If the WordPress constant ABSPATH is not defined, the script will stop execution.
	exit; // Exit if accessed directly
}

// This line includes the theme's header file.
// It will look for a file named 'header-shop.php' first, and if it doesn't find it,
// it will fall back to the default 'header.php'.
get_header( 'shop' ); ?>

	<?php
        // This is a WooCommerce action hook that allows adding content before the main content area.
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20 (shows the breadcrumb trail)
		 */
        // This line executes any functions that are hooked to 'woocommerce_before_main_content'.
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php
        // This is the standard WordPress loop. It will continue as long as there are posts (products) to display.
        while ( have_posts() ) :
        ?>

			<?php
            // This WordPress function sets up the current post's data, making it available for use in the template.
            the_post();
            ?>

			<?php
            // This WooCommerce function includes another template file to display the actual product content.
            // It looks for 'content-single-product.php' within the theme's 'woocommerce' folder first,
            // before falling back to the default WooCommerce plugin template. This keeps the main template file clean.
            wc_get_template_part( 'content', 'single-product' );
            ?>

		<?php
        // This marks the end of the while loop.
        endwhile; // end of the loop.
        ?>

	<?php
        // This is a WooCommerce action hook that allows adding content after the main content area.
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
        // This line executes any functions that are hooked to 'woocommerce_after_main_content'.
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
        // This is a WooCommerce action hook used to display the sidebar on product pages.
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
        // It is commented out by default in many themes to allow for full-width product pages.
		// do_action( 'woocommerce_sidebar' );
	?>

<?php
// This line includes the theme's footer file.
// It will look for a file named 'footer-shop.php' first, and if it doesn't find it,
// it will fall back to the default 'footer.php'.
get_footer( 'shop' );

/* Omit closing PHP tag to prevent accidental whitespace output errors. */
