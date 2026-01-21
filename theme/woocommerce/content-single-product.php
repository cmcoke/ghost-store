<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

// This line ensures that the file cannot be accessed directly from the browser,
// which is a standard WordPress security measure.
defined('ABSPATH') || exit;

// This line brings the global $product object into the current scope.
// The $product object is set up by the single-product.php template and contains all product data.
global $product;
?>

<!--
This is a custom container that provides a max-width, centers the content, and adds padding.
It acts as a main wrapper for the entire single product page content for a cleaner, modern layout.
- `max-w-7xl`: Sets a maximum width for the container.
- `mx-auto`: Centers the container horizontally.
- `px-4`: Adds horizontal padding.
- `py-8`: Adds vertical padding.
-->
<div class="max-w-7xl mx-auto px-4 py-8">

<?php
	/**
	 * Hook: woocommerce_before_single_product.
	 *
	 * This action hook is triggered before any of the single product content is displayed.
	 * It's a good place for global notices or structural wrappers.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );

	// This checks if there was an error in posting a review (e.g., from a failed form submission).
	// If there is an error, it scrolls the user down to the review form.
	if ( post_password_required() ) {
		// This line securely outputs the password form if the product is password protected.
		echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		// This stops further execution if a password is required.
		return;
	}
?>
<!--
This div is the main wrapper for the entire single product's content.
The id attribute is dynamically set to 'product-[product_id]', which is useful for direct linking or JavaScript targeting.
The wc_product_class() function adds a series of useful CSS classes to this div,
such as 'product', 'type-simple', 'instock', etc., which helps in styling.
-->
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

  <!--
	This is a custom wrapper div we've added to control the layout of the image and summary.
	- `lg:grid`: On large screens, this turns the container into a CSS Grid container.
	- `lg:grid-cols-2`: This creates a two-column grid on large screens.
	- `lg:gap-8`: This adds a gap between the grid columns.
	-->
    <?php
      /**
       * Hook: woocommerce_before_single_product_summary.
       *
       * This hook fires right before the product summary block (which contains title, price, etc.).
       * It's primarily used to display the product's image.
       *
       * @hooked woocommerce_show_product_sale_flash - 10
       * @hooked woocommerce_show_product_images - 20 (This is the main function for the image)
       */
      do_action('woocommerce_before_single_product_summary');
      ?>

    <div class="summary entry-summary">
      <?php
      /**
       * Hook: woocommerce_single_product_summary.
       *
       * This is the most important hook for the product summary area. It's a container for all the key product details.
       * The default functions are added in a specific order to create the standard WooCommerce layout.
       * You can reorder, remove, or add to these to customize your layout.
       *
       * @hooked woocommerce_template_single_title - 5 (Displays the product title)
       * @hooked woocommerce_template_single_rating - 10 (Displays the average star rating)
       * @hooked woocommerce_template_single_price - 10 (Displays the product price)
       * @hooked woocommerce_template_single_excerpt - 20 (Displays the short description)
       * @hooked woocommerce_template_single_add_to_cart - 30 (Displays the quantity input and Add to Cart button)
       * @hooked woocommerce_template_single_meta - 40 (Displays the SKU, categories, and tags)
       * @hooked woocommerce_template_single_sharing - 50 (Displays social sharing buttons)
       */
      do_action('woocommerce_single_product_summary');
      ?>
    </div>

  <?php
  /**
   * Hook: woocommerce_after_single_product_summary.
   *
   * This hook fires after the main summary block. It's used to display additional product data,
   * most notably the product tabs (long description, additional information, and reviews).
   *
   * @hooked woocommerce_output_product_data_tabs - 10
   * @hooked woocommerce_upsell_display - 15
   * @hooked woocommerce_output_related_products - 20
   */
  do_action('woocommerce_after_single_product_summary');
  ?>
</div>

<?php

/**

 * Hook: woocommerce_after_single_product.

 *

 * This is the final hook on the single product page, firing after all other content.

 * It's often used for tracking scripts or other closing elements.

 */

do_action( 'woocommerce_after_single_product' );

?>



</div><!-- Closes the custom max-w-7xl wrapper -->