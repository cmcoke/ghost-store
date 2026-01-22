<?php

/**
 * WooCommerce Custom Functions
 *
 * This file contains custom functions and filters to extend or modify
 * WooCommerce functionality within the Ghost theme.
 */

if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

/**
 * Custom Add to Cart form for simple products.
 * This replaces WooCommerce's default output for the quantity and add to cart button.
 */
function ghost_custom_add_to_cart_form()
{
  global $product;

  // This function only applies to simple products. For other types, we fall back to the default.
  if ($product && $product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {

    // Get quantity arguments for the input field.
    $min_value = apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product);
    $max_value = apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product);
    $step      = apply_filters('woocommerce_quantity_input_step', 1, $product);
    $input_value = apply_filters('woocommerce_quantity_input_initial_value', $product->get_min_purchase_quantity(), $product);

    $input_id = 'quantity_' . mt_rand(1000, 9999); // Ensure a unique ID for the input.

    // Output our custom form for simple products.
?>
<form class="cart"
  action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink(), $product)); ?>"
  method="post" enctype="multipart/form-data">

  <?php do_action('woocommerce_before_add_to_cart_button'); ?>

  <div class="ghost-quantity-buttons-wrapper">
    <label class="screen-reader-text"
      for="<?php echo esc_attr($input_id); ?>"><?php echo esc_html__('Quantity', 'woocommerce'); ?></label>
    <div class="ghost-quantity-buttons" data-min="<?php echo esc_attr($min_value); ?>"
      data-max="<?php echo esc_attr($max_value); ?>" data-step="<?php echo esc_attr($step); ?>">
      <button type="button" class="ghost-qty-minus">-</button>
      <input type="number" id="<?php echo esc_attr($input_id); ?>" class="input-text qty text"
        step="<?php echo esc_attr($step); ?>" min="<?php echo esc_attr($min_value); ?>"
        <?php if ($max_value > 0) : ?>max="<?php echo esc_attr($max_value); ?>" <?php endif; ?> name="quantity"
        value="<?php echo esc_attr($input_value); ?>"
        title="<?php echo esc_attr_x('Qty', 'Product quantity input tooltip', 'woocommerce'); ?>" size="4"
        placeholder="" inputmode="numeric" autocomplete="off" />
      <button type="button" class="ghost-qty-plus">+</button>
    </div>
  </div>

  <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
    class="single_add_to_cart_button button alt">
    <?php echo esc_html($product->single_add_to_cart_text()); ?>
  </button>

  <?php do_action('woocommerce_after_add_to_cart_button'); ?>
</form>
<?php
  } else {
    // For other product types (variable, grouped, external), we fall back to WooCommerce's default.
    woocommerce_template_single_add_to_cart();
  }
}

// Remove WooCommerce's default add to cart form action.

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

// Add our custom add to cart form action.

add_action( 'woocommerce_single_product_summary', 'ghost_custom_add_to_cart_form', 30 );

/**
 * Enable WooCommerce product gallery features (zoom, lightbox, slider).
 *
 * These features enhance the product image display on single product pages.
 * They are added via add_theme_support to ensure proper script enqueuing and functionality.
 *
 * @see https://woocommerce.com/document/woocommerce-3-0-developer-notes/#product-gallery-features
 */
function ghost_woocommerce_theme_support() {
	// Add theme support for WooCommerce product gallery zoom.
	add_theme_support( 'wc-product-gallery-zoom' );
	// Add theme support for WooCommerce product gallery lightbox.
	add_theme_support( 'wc-product-gallery-lightbox' );
	// Add theme support for WooCommerce product gallery slider.
	add_theme_support( 'wc-product-gallery-slider' );
}
// Hook into the after_setup_theme action to ensure theme support is added at the correct time.
add_action( 'after_setup_theme', 'ghost_woocommerce_theme_support' );
