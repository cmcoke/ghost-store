<?php
/**
 * WooCommerce Checkout Modifications
 *
 * This file contains functions for modifying the WooCommerce checkout page using hooks.
 * Following the "Hook-First" principle, we use filters and actions to customize
 * the checkout process instead of overriding templates directly. This makes the
 * theme more maintainable and less prone to breaking with WooCommerce updates.
 *
 * @see https://woocommerce.com/document/hooks-actions-filters/
 * @see https://woocommerce.com/document/template-structure/
 *
 * @package Ghost
 */

/******************************************************************************
 * How This File Works with `form-checkout.php`
 *
 * This file, `wc-checkout-mods.php`, contains PHP functions that act as
 * **modifiers** for the checkout page. It doesn't create the page's main
 * structure, but it hooks into it to add, remove, or change content.
 *
 * The main structure of our checkout page is defined in the template file
 * `theme/woocommerce/checkout/form-checkout.php`. That file is like an HTML
 * **blueprint** with several `do_action( 'hook_name' );` placeholders.
 *
 * The functions in this file use `add_action()` or `add_filter()` to attach
 * themselves to those placeholders. For example, `add_action( 'woocommerce_review_order_before_payment', 'ghost_add_payment_options_title' );`
 * tells WooCommerce: "When you reach the `woocommerce_review_order_before_payment`
 * placeholder, execute our `ghost_add_payment_options_title` function."
 *
 * In summary:
 * 1. `form-checkout.php` = Defines the **structure**.
 * 2. `wc-checkout-mods.php` (this file) = Defines **modifications and additions** to that structure.
 ******************************************************************************/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizes the checkout fields.
 *
 * This function is hooked into the 'woocommerce_checkout_fields' filter. It receives
 * an array of all checkout fields and can be used to modify, add, or remove fields.
 * Here, we are adding a custom CSS class to the 'order_comments' field.
 *
 * @param array $fields The original array of checkout fields.
 * @return array The modified array of checkout fields.
 */
function ghost_custom_checkout_fields( $fields ) {
	// This line targets the 'order' section of the checkout fields.
	// Then, it accesses the 'order_comments' field within that section.
	// Finally, it modifies the 'class' property of that field.
	// The new class 'custom-order-notes' is added to the array of classes.
	$fields['order']['order_comments']['class'][] = 'custom-order-notes';

	// It's crucial to return the modified $fields array.
	// If you don't, no fields will be displayed on the checkout page.
	return $fields;
}
// This line connects our custom function to the 'woocommerce_checkout_fields' filter.
// Now, whenever WooCommerce builds the checkout fields, our function will be called.
add_filter( 'woocommerce_checkout_fields', 'ghost_custom_checkout_fields' );

/**
 * Adds a title for the payment options section on the checkout page.
 *
 * This function is hooked into 'woocommerce_review_order_before_payment', which
 * is an action that fires right before WooCommerce displays the available
 * payment gateways. We use it to inject a custom heading.
 */
function ghost_add_payment_options_title() {
	// This line outputs a level-3 heading.
	// We use esc_html_e() to ensure the text is properly escaped for security
	// and translated if a translation is available for the 'ghost' text domain.
	echo '<h3 class="payment-options-title">' . esc_html__( 'Payment Options', 'ghost' ) . '</h3>';
}
// This line connects our function to the action hook.
add_action( 'woocommerce_review_order_before_payment', 'ghost_add_payment_options_title' );
