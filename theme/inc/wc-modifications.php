<?php

/**
 * WooCommerce Modifications
 *
 * This file contains custom actions and filters to modify WooCommerce elements,
 * such as reordering components on the single product page. It is loaded by functions.php.
 * This approach uses the WooCommerce hook system to avoid overriding templates,
 * ensuring better compatibility with future updates.
 *
 * @package Ghost
 * @since 1.0.0
 * @see https://woocommerce.com/document/introduction-to-hooks-actions-and-filters/
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
  die;
}

/**
 * Moves the product price to appear before the product title on the single product page.
 *
 * This function is hooked into 'woocommerce_single_product_summary'. It first removes the
 * default WooCommerce actions for displaying the title and price, then adds them back
 * in a different order by swapping their priorities.
 *
 * @return void
 */
function ghost_move_price_before_title()
{
  // Remove the woocommerce_template_single_price action.
  // The default priority for the price is 10.
  remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

  // Remove the woocommerce_template_single_title action.
  // The default priority for the title is 5.
  remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

  // Add the woocommerce_template_single_price action back with a new priority.
  // We give it a priority of 5 to make it appear where the title used to be.
  add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 5);

  // Add the woocommerce_template_single_title action back with a new priority.
  // We give it a priority of 10 to make it appear where the price used to be.
  add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 10);
}
// Hook the custom function into the 'init' action hook.
// This ensures that WooCommerce has been loaded before our function tries to remove its actions.
add_action('init', 'ghost_move_price_before_title');

/**
 * Moves the 'Related Products' section to the very bottom of the single product page.
 *
 * This function first removes the default placement of 'Related Products' from
 * 'woocommerce_after_single_product_summary' (where it appears after the product summary
 * but before tabs/upsells). It then re-adds it to 'woocommerce_after_single_product',
 * which is the very last hook on the single product page before the footer.
 *
 * @return void
 */
function ghost_move_related_products_to_bottom()
{
  // Remove the default 'Related Products' section from its usual position.
  // It is normally hooked to 'woocommerce_after_single_product_summary' with a priority of 20.
  remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

  // Add the 'Related Products' section to the 'woocommerce_after_single_product' hook.
  // This hook fires at the very end of the single product page content.
  // A priority of 10 ensures it's added early in this new section.
  add_action('woocommerce_after_single_product', 'woocommerce_output_related_products', 10);
}
// Hook the custom function into the 'init' action.
// This ensures that WooCommerce is fully loaded and its default actions are registered
// before we attempt to remove and re-add them.
add_action('init', 'ghost_move_related_products_to_bottom');

/**
 * Conditionally changes the 'Add to Cart' button text to 'Buy Now' for specific products.
 *
 * This function checks if the current product belongs to the 'protein' category.
 * If it does, the button text is changed. Otherwise, the default text is returned.
 * It is applied to both single product pages and product archives/loops.
 *
 * @param string $button_text The original 'Add to Cart' button text.
 * @return string The new button text, or the original if the condition is not met.
 */
function ghost_change_add_to_cart_button_text($button_text)
{
  // Access the global $product object to get the current product's details.
  // This is reliable in both single product views and standard WordPress/WooCommerce loops.
  global $product;

  // Always make sure we have a valid product object before proceeding.
  if (! is_a($product, 'WC_Product')) {
    // If not, return the original text to avoid errors.
    return $button_text;
  }

  // Use has_term() to check if the product is in the 'protein' category.
  // 'protein' is the category slug.
  // 'product_cat' is the taxonomy name for WooCommerce product categories.
  if (has_term('protein', 'product_cat', $product->get_id())) {
    // If the product is in the 'protein' category, return our custom text.
    return __('Buy Now', 'ghost');
  }

  // If the condition is not met, return the original, unmodified button text.
  return $button_text;
}
// Apply the filter to change the button text on single product pages.
add_filter('woocommerce_product_single_add_to_cart_text', 'ghost_change_add_to_cart_button_text', 10, 1);

// Apply the filter to change the button text on product archive pages and loops.
add_filter('woocommerce_product_add_to_cart_text', 'ghost_change_add_to_cart_button_text', 10, 1);

/**
 * Appends a 'GHOST EXCLUSIVE' badge next to the product price HTML.
 *
 * This function uses the 'woocommerce_get_price_html' filter to modify the
 * displayed price string. It adds a custom HTML span with a badge text and
 * Tailwind CSS classes for styling.
 *
 * @param string     $price   The product price HTML string.
 * @param WC_Product $product The product object.
 * @return string The modified price HTML string with the badge.
 */
function ghost_add_exclusive_badge_to_price($price, $product)
{
  // Define the HTML for our exclusive badge.
  // We're using Tailwind CSS classes for basic styling:
  // text-sm for small text, bg-orange-500 for a orange background, text-white for white text,
  // px-2 py-1 for horizontal and vertical padding, rounded for rounded corners,
  // and ml-2 for a left margin to separate it from the price.
  $exclusive_badge = ' <span class="ghost-exclusive-badge text-sm bg-orange-500 text-white px-2 py-1 rounded ml-2">' . __('GHOST EXCLUSIVE', 'ghost') . '</span>';

  // Append the exclusive badge HTML to the existing price HTML.
  // Only for products that are in the proten category
  if (has_term('protein', 'product_cat', $product->get_id())) {
    $price .= $exclusive_badge;
  }


  // Return the modified price HTML.
  return $price;
}
// Apply the filter to 'woocommerce_get_price_html'.
// Priority 10 is standard. We specify 2 accepted arguments ($price, $product).
add_filter('woocommerce_get_price_html', 'ghost_add_exclusive_badge_to_price', 10, 2);

/**
 * Renders the HTML for the header cart link, including the item count.
 *
 * This function is designed to be reusable. It generates the complete anchor tag
 * for the cart link, dynamically displaying the number of items currently in the cart.
 * We will call this function directly in the header template and also use it
 * to generate the updated content for our AJAX cart fragment.
 *
 * @return void
 */
function ghost_render_header_cart_count() {
	?>
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="ghost-header-cart-link uppercase font-[teko] text-[1.5rem] md:text-2xl cursor-pointer">
		<?php
		/* translators: %d: number of items in the cart */
		printf(
			esc_html__( 'cart [%d]', 'ghost' ),
			(int) WC()->cart->get_cart_contents_count()
		);
		?>
	</a>
	<?php
}

/**
 * Adds the header cart count to WooCommerce's AJAX fragments.
 *
 * This function hooks into 'woocommerce_add_to_cart_fragments'. When an item is added
 * to the cart, WooCommerce uses this filter to update specific elements on the page
 * without a full refresh. We are telling it to update our header cart count.
 *
 * @param array $fragments The array of existing WooCommerce AJAX fragments.
 * @return array The modified array of fragments including our header cart count.
 */
function ghost_add_cart_count_fragment( $fragments ) {
	// Start output buffering to capture the HTML from our rendering function.
	ob_start();

	// Call the function that renders our cart link.
	ghost_render_header_cart_count();

	// Get the captured HTML and add it to the fragments array.
	// The key 'a.ghost-header-cart-link' is the CSS selector for the element to be replaced.
	// The value is the new HTML that will replace it.
	$fragments['a.ghost-header-cart-link'] = ob_get_clean();

	// Return the updated fragments array to WooCommerce.
	return $fragments;
}
// Apply the filter to add our fragment.
add_filter( 'woocommerce_add_to_cart_fragments', 'ghost_add_cart_count_fragment', 10, 1 );

/**
 * Enqueues necessary WordPress Interactivity API and WooCommerce Blocks JavaScripts
 * specifically on single product pages.
 *
 * These scripts are required for the AJAX add-to-cart functionality introduced by
 * the updated simple.php template override. The theme likely doesn't enqueue these
 * by default on single product pages.
 *
 * @return void
 */
function ghost_enqueue_interactivity_scripts() {
	// Check if we are on a single product page.
	if ( is_product() ) {
		// Ensure jQuery is loaded as many WooCommerce scripts depend on it.
		wp_enqueue_script( 'jquery' );

		// Enqueue core WooCommerce scripts related to add-to-cart functionality.
		// These are crucial for intercepting form submissions and making AJAX calls.
		wp_enqueue_script( 'wc-add-to-cart', plugins_url( WC_PLUGIN_FILE ) . '/assets/js/frontend/add-to-cart.min.js', array( 'jquery' ), WC_VERSION, true );
		wp_enqueue_script( 'wc-single-product', plugins_url( WC_PLUGIN_FILE ) . '/assets/js/frontend/single-product.min.js', array( 'jquery', 'wc-add-to-cart' ), WC_VERSION, true );
		wp_enqueue_script( 'wc-cart-fragments', plugins_url( WC_PLUGIN_FILE ) . '/assets/js/frontend/cart-fragments.min.js', array( 'jquery', 'wc-add-to-cart' ), WC_VERSION, true );

		// Enqueue the core WordPress Interactivity API script.
		// This script is essential for data-wp-interactive and data-wp-on--click attributes
		// used in the modern simple.php template.
		wp_enqueue_script( 'wp-interactivity' );

		// Enqueue WooCommerce Blocks data and registry scripts.
		// These provide the context and actions for the product button block functionality
		// that the new simple.php template leverages. They depend on wp-interactivity.
		wp_enqueue_script( 'wc-blocks-data', plugins_url( 'woocommerce-blocks/build/wc-blocks-data.js', WC_PLUGIN_FILE ), array( 'wp-interactivity', 'wp-api-fetch' ), '9.9.9', true ); // Using a generic version
		wp_enqueue_script( 'wc-blocks-registry', plugins_url( 'woocommerce-blocks/build/wc-blocks-registry.js', WC_PLUGIN_FILE ), array( 'wp-interactivity', 'wc-blocks-data' ), '9.9.9', true ); // Using a generic version

		// Localize the script for wc-add-to-cart, as it needs specific parameters.
		// This is usually done by WooCommerce itself, but re-doing it here ensures it's available.
		wp_localize_script( 'wc-add-to-cart', 'wc_add_to_cart_params', array(
			'ajax_url'                => admin_url( 'admin-ajax.php' ),
			'wc_ajax_url'             => WC_AJAX::get_endpoint( '%%endpoint%%' ),
			'i18n_view_cart'          => esc_attr__( 'View cart', 'woocommerce' ),
			'cart_url'                => esc_url( wc_get_cart_url() ),
			'is_cart'                 => is_cart(),
			'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ),
		) );
	}
}
// Hook this function into 'wp_enqueue_scripts' to ensure scripts are added correctly.
// Use a high priority to try and override any potential theme dequeueing or improper loading.
add_action( 'wp_enqueue_scripts', 'ghost_enqueue_interactivity_scripts', 999 );

/**
 * Custom JavaScript to manually handle AJAX add-to-cart on single product pages.
 *
 * This script intercepts the click event on the Add to Cart button, prevents the
 * default form submission, and directly makes an AJAX call to WooCommerce.
 * Upon a successful AJAX response, it triggers WooCommerce's 'added_to_cart' event,
 * allowing cart fragments to update the UI without a full page refresh.
 * This is implemented as a fallback due to persistent issues with default event binding.
 *
 * @return void
 */
function ghost_custom_add_to_cart_ajax_script() {
	// Only load this script on single product pages.
	if ( ! is_product() ) {
		return;
	}
	?>
	<script type="text/javascript">
		// Ensure jQuery is available and the DOM is ready.
		jQuery(function($) {
			const addToCartButton = $('button.single_add_to_cart_button');
			const cartForm = addToCartButton.closest('form.cart');

			// Check if the button and form exist.
			if (addToCartButton.length && cartForm.length) {
				// Prevent the default form submission behavior.
				cartForm.on('submit', function(e) {
					e.preventDefault();

					const button = $(this).find('button.single_add_to_cart_button');
					const productId = button.val();
					const quantity = $(this).find('input.qty').val();

					// Add loading class to the button
					button.addClass('loading');

					// Ensure wc_add_to_cart_params is defined, as it holds the AJAX URL.
					if (typeof wc_add_to_cart_params === 'undefined') {
						console.error('wc_add_to_cart_params is undefined. Cannot perform AJAX add to cart.');
						button.removeClass('loading');
						return;
					}

					// Construct the data for the AJAX request.
					const data = {
						product_id: productId,
						quantity: quantity
					};

					// Perform the AJAX request.
					$.ajax({
						type: 'POST',
						// Use the modern wc_ajax_url endpoint, replacing the placeholder with 'add_to_cart'.
						url: wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart'),
						data: data,
						success: function(response) {
							if (response.error && response.product_url) {
								// If an error and product_url is present, redirect.
								window.location = response.product_url;
								return;
							}
							if (response.fragments) {
								// Trigger the 'added_to_cart' event. This is crucial!
								// WooCommerce's cart-fragments.js listens for this event
								// and updates all registered fragments (like our header cart).
								$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, button]);
							}

							// Remove loading class.
							button.removeClass('loading');
						},
						error: function(jqXHR, textStatus, errorThrown) {
							console.error('AJAX error:', textStatus, errorThrown);
							button.removeClass('loading');
							// Fallback to normal submission or show an error message.
							// For now, we'll let it refresh on error if the above fails completely.
							cartForm.off('submit').submit();
						},
						dataType: 'json'
					});
				});
			}
		});
	</script>
	<?php
}
// Hook this custom JavaScript into the footer.
add_action( 'wp_footer', 'ghost_custom_add_to_cart_ajax_script', 999 );
