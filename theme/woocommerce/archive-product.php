<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

// Ensures the file is not accessed directly.
defined('ABSPATH') || exit;

// Retrieves the header for the shop.
get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
// Executes the woocommerce_before_main_content action hook.
do_action('woocommerce_before_main_content');

/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10
 */
// Executes the woocommerce_shop_loop_header action hook to display the header.
do_action('woocommerce_shop_loop_header');

// Checks if there are products to display.
if (woocommerce_product_loop()) {

  /**
   * Hook: woocommerce_before_shop_loop.
   *
   * @hooked woocommerce_output_all_notices - 10
   * @hooked woocommerce_result_count - 20
   * @hooked woocommerce_catalog_ordering - 30
   */
  // Intentionally commented out to match the previous template's customization.
  // do_action( 'woocommerce_before_shop_loop' );

  // Starts the product loop with the opening markup.
  woocommerce_product_loop_start();

  // Checks if there are products in the current query.
  if (wc_get_loop_prop('total')) {
    // Loops through the products.
    while (have_posts()) {
      // Sets up the post data for the current product.
      the_post();

      /**
       * Hook: woocommerce_shop_loop.
       */
      // Executes the woocommerce_shop_loop action hook.
      do_action('woocommerce_shop_loop');

      // Loads the template part for displaying a single product.
      wc_get_template_part('content', 'product');
    }
  }

  // Ends the product loop with the closing markup.
  woocommerce_product_loop_end();

  /**
   * Hook: woocommerce_after_shop_loop.
   *
   * @hooked woocommerce_pagination - 10
   */
  // Executes the woocommerce_after_shop_loop action hook for pagination.
  do_action('woocommerce_after_shop_loop');
} else {
  /**
   * Hook: woocommerce_no_products_found.
   *
   * @hooked wc_no_products_found - 10
   */
  // Executes the woocommerce_no_products_found action hook if no products are found.
  do_action('woocommerce_no_products_found');
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
// Executes the woocommerce_after_main_content action hook.
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
// Intentionally commented out to match the previous template's customization.
// do_action( 'woocommerce_sidebar' );

// Retrieves the footer for the shop.
get_footer('shop');