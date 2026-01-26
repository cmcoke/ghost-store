<?php
/**
 * Checkout Form (Customized for Ghost Theme)
 *
 * This template has been overridden to create a single-column layout and
 * provide clear sections for the user, following the site's design principles.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Ghost\WooCommerce\Templates
 * @version 9.4.0
 */

/******************************************************************************
 * How This Template Works with `wc-checkout-mods.php`
 *
 * This file, `form-checkout.php`, acts as the main HTML **blueprint** for the
 * checkout page. It defines the overall structure, layout, and the order of
 * the major sections (like customer details and the order summary).
 *
 * You'll see `do_action( 'hook_name' );` calls throughout this template. These
 * are **placeholders**. WooCommerce uses them to insert dynamic content.
 *
 * The file `theme/inc/wc-checkout-mods.php` contains functions that "hook into"
 * these placeholders. For example, when WooCommerce gets to the
 * `do_action( 'woocommerce_review_order_before_payment' );` placeholder (which
 * is inside the `woocommerce_checkout_order_review` action), it runs the
 * `ghost_add_payment_options_title()` function from our `wc-checkout-mods.php`
 * file, which injects the "Payment Options" title.
 *
 * In summary:
 * 1. `form-checkout.php` (this file) = Defines the **structure**.
 * 2. `wc-checkout-mods.php` = Defines **modifications and additions** to that structure.
 ******************************************************************************/

// This is a standard WordPress security measure. It checks if the file is being accessed directly
// and, if so, stops it from running. This prevents malicious actors from accessing the file's code.
if ( ! defined( 'ABSPATH' ) ) {
	// If the file is accessed directly, the script will stop execution.
	exit;
}

// This action hook allows other plugins or themes to add custom content
// right before the main checkout form is displayed. The '$checkout' object is passed
// so that other functions can access checkout properties.
do_action( 'woocommerce_before_checkout_form', $checkout );

// This conditional block checks for a specific scenario:
// 1. If the store does not allow customers to register on the checkout page.
// 2. AND if the store requires registration to checkout.
// 3. AND if the person viewing the page is not currently logged in.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	// If all those conditions are true, a message is displayed telling the user they must log in.
	// The 'apply_filters' allows the message to be customized by other code.
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	// The 'return' statement stops the rest of the template from loading, so the form is not displayed.
	return;
}

?>

<!-- This is the main form element for the checkout process. -->
<!-- 'name="checkout"' is a required identifier for WooCommerce. -->
<!-- 'method="post"' specifies that the form data will be sent to the server. -->
<!-- 'class="checkout woocommerce-checkout"' provides styling hooks for CSS. -->
<!-- The 'action' attribute gets the dynamic URL for the checkout page, ensuring the form submits to the correct place. 'esc_url' secures the URL. -->
<!-- 'enctype="multipart/form-data"' is included to ensure file uploads (if any) are handled correctly. -->
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<!-- This is a custom wrapper div we added to help with overall layout and styling. -->
	<div class="checkout-wrapper">

		<?php
		// This checks if there are any fields to display for the checkout form (e.g., billing, shipping).
		if ( $checkout->get_checkout_fields() ) :
			?>

			<!-- This div acts as a container for all customer-related fields. -->
			<div id="customer_details" class="checkout-section">
				<!-- This div is an inner wrapper for the content of the customer details section. -->
				<div class="customer-details-content">
					<!-- This is the main title for the contact and shipping information section. 'esc_html_e' translates the text and escapes it for security. -->
					<h2 class="section-title"><?php esc_html_e( 'Contact & Shipping', 'ghost' ); ?></h2>
					<?php
					// This action hook renders all the billing fields (e.g., name, email, address).
					do_action( 'woocommerce_checkout_billing' );
					?>
					<?php
					// This action hook renders all the shipping fields (if needed).
					do_action( 'woocommerce_checkout_shipping' );
					?>
				</div>
			</div>

		<?php
		// This ends the 'if' statement from above.
		endif;
		?>

		<!-- This div acts as a container for the order review and payment section. -->
		<div class="order-review-section checkout-section">
			<!-- This is the main title for the order review section. -->
			<h2 id="order_review_heading" class="section-title"><?php esc_html_e( 'Your Order', 'woocommerce' ); ?></h2>

			<?php
			// This action hook allows custom content to be added before the order review table.
			do_action( 'woocommerce_checkout_before_order_review' );
			?>

			<!-- This div is the container where WooCommerce will place the entire order summary. -->
			<!-- It's updated via AJAX when the customer changes their shipping options or address. -->
			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php
				// This is the core action that renders the order summary table, shipping options, and payment gateways.
				do_action( 'woocommerce_checkout_order_review' );
				?>
			</div>

			<?php
			// This action hook allows custom content to be added after the order review table.
			do_action( 'woocommerce_checkout_after_order_review' );
			?>
		</div>

	</div>

</form>

<!-- This is a custom div we added to contain the link back to the cart page. -->
<div class="return-to-cart-link">
	<!-- This 'a' tag is the link itself. -->
	<!-- The 'href' attribute dynamically gets the URL for the cart page and escapes it for security. -->
	<!-- 'esc_html_e' provides the translated and secured text for the link. -->
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( '← Return to Cart', 'ghost' ); ?></a>
</div>

<?php
// This is the final action hook, allowing other code to add content after the entire checkout form.
do_action( 'woocommerce_after_checkout_form', $checkout );
?>
