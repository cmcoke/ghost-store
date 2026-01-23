<?php
/**
 * WooCommerce Custom Functions
 *
 * This file contains custom functions for modifying WooCommerce behavior and layout
 * through hooks and filters. It helps to keep customizations organized and separate
 * from the main functions.php file.
 *
 * @package Ghost
 * @see     https://woocommerce.com/document/introduction-to-hooks-actions-and-filters/
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1. Open the main flex container for the cart page.
 *
 * This function is hooked into 'woocommerce_before_cart'.
 * It injects an opening <div> that will act as a flex container for the
 * entire cart contents, allowing for a two-column layout.
 *
 * @return void
 */
function ghost_wrap_cart_flex_open() {
	// This div creates a responsive flexbox container.
	// - `flex`: Activates flexbox layout.
	// - `flex-col`: Stacks items vertically on mobile.
	// - `lg:flex-row`: Switches to a horizontal layout on large screens.
	// - `gap-8`: Adds spacing between the flex items (cart table and totals).
	echo '<div class="ghost-cart-wrapper flex flex-col lg:flex-row gap-8">';
	// This div is the first column, containing the main cart table.
	// - `w-full`: Takes up full width on mobile.
	// - `lg:w-2/3`: Takes up two-thirds of the width on large screens.
	echo '<div class="ghost-cart-form-wrapper w-full lg:w-3/4">';
}
add_action( 'woocommerce_before_cart', 'ghost_wrap_cart_flex_open', 5 );


/**
 * 2. Open the wrapper for the cart collaterals (totals).
 *
 * This function is hooked into 'woocommerce_after_cart_table'.
 * It closes the first column and opens the second column for the cart totals.
 *
 * @return void
 */
function ghost_wrap_cart_collaterals_open() {
	// Closes the div for the cart table wrapper.
	echo '</div>';
	// This div is the second column, which will contain the cart totals.
	// - `w-full`: Takes up full width on mobile.
	// - `lg:w-1/3`: Takes up one-third of the width on large screens.
	echo '<div class="ghost-cart-collaterals-wrapper w-full lg:w-1/4">';
}
add_action( 'woocommerce_after_cart_table', 'ghost_wrap_cart_collaterals_open', 5 );


/**
 * 3. Reposition the cart totals.
 *
 * This function unhooks the cart totals from their default location and
 * re-hooks them inside our new second column.
 *
 * @return void
 */
function ghost_reposition_cart_totals() {
	// Removes the entire 'cart-collaterals' section, which includes totals and cross-sells.
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

	// Re-adds the cart totals to a new hook that we can place anywhere.
	// We will trigger this hook inside our new column.
	add_action( 'ghost_cart_collaterals', 'woocommerce_cart_totals', 10 );
}
add_action( 'init', 'ghost_reposition_cart_totals' );


/**
 * 4. Close the wrapper divs.
 *
 * This function is hooked into 'woocommerce_after_cart'.
 * It closes the remaining open divs from our custom layout structure.
 *
 * @return void
 */
function ghost_wrap_cart_close() {
	// This is where we trigger our custom hook to display the cart totals.
	do_action( 'ghost_cart_collaterals' );
	// Closes the div for the cart collaterals wrapper.
	echo '</div>';
	// Closes the main flexbox container.
	echo '</div>';
}
add_action( 'woocommerce_after_cart', 'ghost_wrap_cart_close', 20 );
