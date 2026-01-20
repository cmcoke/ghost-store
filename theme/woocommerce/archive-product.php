<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

// Ensures that this file is not called directly, enhancing security.
// If the WordPress constant ABSPATH is not defined, the script will stop execution.
defined('ABSPATH') || exit;

// Includes the theme's header file. It will look for header-shop.php first,
// and fall back to header.php if not found.
get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * This is a WooCommerce action hook that allows other functions to add content
 * right before the main content area of a WooCommerce page.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs the opening divs for the content area)
 * @hooked woocommerce_breadcrumb - 20 (displays the breadcrumb navigation trail)
 */
do_action('woocommerce_before_main_content');

?>
<header class="woocommerce-products-header">
  <?php
  // This checks a filter to see if the page title should be displayed.
  // It allows developers to disable the title from showing if needed.
  if (apply_filters('woocommerce_show_page_title', false)) :
  ?>
  <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
  <?php endif; // This ends the if-statement for showing the page title. 
  ?>

  <?php
  /**
   * Hook: woocommerce_archive_description.
   *
   * This action hook is used to display the description for a product category or the main shop page.
   *
   * @hooked woocommerce_taxonomy_archive_description - 10 (displays category/tag descriptions)
   * @hooked woocommerce_product_archive_description - 10 (displays the main shop page description)
   */
  do_action('woocommerce_archive_description');
  ?>
</header>
<?php
// This function checks if there are products to display in the current view (e.g., shop, category).
if (woocommerce_product_loop()) {

  /**
   * Hook: woocommerce_before_shop_loop.
   *
   * This action hook allows adding content before the main product loop starts.
   *
   * @hooked woocommerce_output_all_notices - 10 (displays any WooCommerce notices like 'Product added to cart')
   * @hooked woocommerce_result_count - 20 (displays the 'Showing 1-12 of X results' text)
   * @hooked woocommerce_catalog_ordering - 30 (displays the dropdown for sorting products)
   */
  // do_action('woocommerce_before_shop_loop');

  // This function outputs the opening HTML wrapper for the product grid (e.g., <ul class="products">).
  woocommerce_product_loop_start();

  // This checks if there are any products in the current query to loop through.
  if (wc_get_loop_prop('total')) {
    // This is the standard WordPress loop to iterate through posts (in this case, products).
    while (have_posts()) {
      // This sets up the current post data, making it available for use in the loop.
      the_post();

      /**
       * Hook: woocommerce_shop_loop.
       *
       * This is an action hook that is intentionally left empty by default.
       * It allows developers to inject custom code inside the product loop item.
       */
      do_action('woocommerce_shop_loop');

      // This includes the template for a single product grid item (content-product.php).
      wc_get_template_part('content', 'product');
    }
  }

  // This function outputs the closing HTML wrapper for the product grid (e.g., </ul>).
  woocommerce_product_loop_end();

  /**
   * Hook: woocommerce_after_shop_loop.
   *
   * This action hook allows adding content after the main product loop ends.
   *
   * @hooked woocommerce_pagination - 10 (displays the pagination links for navigating between pages of products)
   */
  do_action('woocommerce_after_shop_loop');
} else {
  /**
   * Hook: woocommerce_no_products_found.
   *
   * This action hook is triggered if no products are found for the current query.
   *
   * @hooked wc_no_products_found - 10 (displays a 'No products were found matching your selection.' message)
   */
  do_action('woocommerce_no_products_found');
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * This action hook allows adding content right after the main content area.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs the closing divs for the content area)
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 *
 * This action hook is used to display the WooCommerce sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10 (includes the sidebar.php template)
 */
// do_action( 'woocommerce_sidebar' );

// Includes the theme's footer file. It will look for footer-shop.php first,
// and fall back to footer.php if not found.
get_footer('shop');