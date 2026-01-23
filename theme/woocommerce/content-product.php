<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

// This line ensures that the file cannot be accessed directly from the browser,
// which is a standard WordPress security measure.
defined( 'ABSPATH' ) || exit;

// This line brings the global $product object into the current scope.
// The $product object contains all the information about the current product in the loop.
global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, 'WC_Product' ) || ! $product->is_visible() ) {
  // If the product is not valid or not visible, this 'return' statement stops
  // the rest of the file from executing for this product.
	return;
}
?>
<!--
This is the opening list item tag for the product.
The wc_product_class() function automatically adds a set of useful CSS classes
to the list item, such as 'product', 'instock', 'taxable', etc.
-->
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	// This hook is responsible for wrapping the product image and title in a link to the product's page.
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * Hook: woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	// This hook displays the 'Sale!' flash and the product's thumbnail image.
	do_action( 'woocommerce_before_shop_loop_item_title' );
	?>

  <!-- This is a custom container div to group the product price and title. -->
  <!-- We can apply flexbox styles to this div to control their alignment. -->
  <div class="product-meta">
    <?php
	  /**
	   * Hook: woocommerce_shop_loop_item_title.
	   *
	   * @hooked woocommerce_template_loop_product_title - 10
	   */
	  // This hook displays the product's title.
	  do_action( 'woocommerce_shop_loop_item_title' );

	  /**
	   * Hook: woocommerce_after_shop_loop_item_title.
	   *
	   * @hooked woocommerce_template_loop_rating - 5
	   * @hooked woocommerce_template_loop_price - 10
	   */
	  // This hook displays the product's star rating and price.
	  do_action( 'woocommerce_after_shop_loop_item_title' );
    ?>
  </div>

	<?php
	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	// This hook closes the product link and displays the 'Add to Cart' button.
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
