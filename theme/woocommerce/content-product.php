<?php

/**
 * The template for displaying a single product in a product loop.
 *
 * This file is a blueprint for how one individual product looks on the shop page,
 * category pages, or any other page that shows a grid of products. It controls
 * the structure of the product's image, title, price, and button.
 *
 * It is called repeatedly by the 'archive-product.php' file. Think of
 * 'archive-product.php' as the shelf, and this 'content-product.php' file as
 * the template for each product box you place on that shelf.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

// This line ensures that the file cannot be accessed directly from the browser,
// which is a standard WordPress security measure.
defined('ABSPATH') || exit;

// This line brings the global $product object into the current scope.
// The $product object contains all the information about the current product in the loop.
global $product;

// This conditional check ensures that the code only runs if the $product variable
// is a valid product object and if the product is set to be visible in the shop.
if (empty($product) || ! $product->is_visible()) {
  // If the product is not valid or not visible, this 'return' statement stops
  // the rest of the file from executing for this product.
  return;
  // This brace closes the 'if' statement.
}
?>
<!--
This is the opening list item tag for the product.
The wc_product_class() function automatically adds a set of useful CSS classes
to the list item, such as 'product', 'instock', 'taxable', etc.
-->
<li <?php wc_product_class('', $product); ?>>

  <?php
  /**
   * This is a WooCommerce action hook that fires before the main content of a product item.
   * By default, it runs the 'woocommerce_template_loop_product_link_open' function,
   * which wraps the product content in a link to the single product page.
   */
  do_action('woocommerce_before_shop_loop_item');
  // This brace closes the opening php tag.
  ?>

  <?php
  /**
   * This is a WooCommerce action hook that fires before the product title.
   * By default, it runs 'woocommerce_show_product_loop_sale_flash' to show the "Sale!" badge
   * and 'woocommerce_template_loop_product_thumbnail' to display the product's main image.
   */
  do_action('woocommerce_before_shop_loop_item_title');
  // This brace closes the php tag.
  ?>

  <!-- This is a custom container div to group the product price and title. -->
  <!-- We can apply flexbox styles to this div to control their alignment. -->
  <div class="product-meta">
    <?php

    /**
     * This is a WooCommerce action hook that is used to display the product title.
     * By default, it runs the 'woocommerce_template_loop_product_title' function.
     * We are placing it here to show the title on the right.
     */
    do_action('woocommerce_shop_loop_item_title');

    /**
     * This is a WooCommerce action hook that fires after the product title.
     * By default, it runs 'woocommerce_template_loop_rating' (for star ratings)
     * and 'woocommerce_template_loop_price' to display the product's price.
     * We are placing it here to show the price on the left.
     */
    do_action('woocommerce_after_shop_loop_item_title');



    // This brace closes the php tag.
    ?>
    <!-- This brace closes the 'product-meta' div. -->
  </div>

  <?php
  /**
   * This is a WooCommerce action hook that fires after the main content of a product item.
   * Functions like the "Add to Cart" button (specifically, 'woocommerce_template_loop_add_to_cart')
   * are typically attached to this hook by default in WooCommerce.
   * If you want to remove the Add to Cart button from product loops, you would use 'remove_action'
   * on this hook (e.g., in your functions.php file).
   */
  do_action('woocommerce_after_shop_loop_item');
  // This brace closes the php tag.
  ?>

  <?php
  /*
     * The following is the standard WooCommerce function used to show the "Add to Cart" button.
     * It is commented out so it will not be displayed on the page.
     *
     * This function, 'woocommerce_template_loop_add_to_cart()', is typically hooked by WooCommerce
     * to the 'woocommerce_after_shop_loop_item' action hook by default.
     *
     * To enable the button, you would uncomment the function call:
     * woocommerce_template_loop_add_to_cart();
     *
     * Or, if you have removed it via functions.php, you would re-add it like:
     * add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
     */
  /*
    woocommerce_template_loop_add_to_cart();
    */
  // This brace closes the php tag.
  ?>
  <!-- This is the closing list item tag for the product. -->
</li>