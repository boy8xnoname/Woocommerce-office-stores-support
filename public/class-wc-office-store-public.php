<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Wc_Office_store_Public {

	private $plugin_name;
	private $version;
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('woocommerce_before_add_to_cart_form', array($this,'singleProductGroupUpSell'));

		add_action('wc_office_store_hook_under_image', array($this,'singleProductGroupUpSell'));

		add_action('woocommerce_checkout_before_order_review', array($this,'checkoutSuggestProductSell'));
		// add_action('woocommerce_checkout_before_order_review', array($this,'checkoutSuggestProductSell'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-office-store-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'fontawesome.min', plugin_dir_url( __FILE__ ) . 'css/all.fontawesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'slick-theme', plugin_dir_url( __FILE__ ) . 'css/slick-theme.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'slick-style', plugin_dir_url( __FILE__ ) . 'css/slick.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name.'slick-script', plugin_dir_url( __FILE__ ) . 'js/slick.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'upsell-suggest', plugin_dir_url( __FILE__ ) . 'js/wc-office-upsell-checkout-suggest.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-office-store-public.js', array( 'jquery' ), $this->version, false );

	}

	public function singleProductGroupUpSell() {
		global $product;
		$enableGroupProduct = !empty(get_option( 'wc_group_product')) ? get_option( 'wc_group_product') : 'off';
		if($enableGroupProduct != 1) {
			return;
		}

		echo Wc_Office_store_Group_Product::showUpsellProductToSummaryContent();
	}


	public function checkoutSuggestProductSell() {
		global $product;
		$enableSuggestProduct= !empty(get_option( 'wc_suggest_product')) ? get_option( 'wc_suggest_product') : 'off';
		if($enableSuggestProduct != 1) {
			return;
		}

		echo WC_suggest_product_upsell_checkout::wc_suggest_carousel_products();
	}


}
