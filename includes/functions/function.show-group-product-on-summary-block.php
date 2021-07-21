<?php
class Wc_Office_store_Group_Product
{
    /**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wc_Office_store_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

    public function __construct()
    {
        $this->actions = array();
        $this->filters = array();
    }

    public static function showUpsellProductToSummaryContent() {
        global $product;
        $upsell_ids = array();

        $product_id = $product->is_type('variation') ? $product->get_parent_id() : $product->get_id();
        $product = wc_get_product( $product_id );
        $upsell_ids = $product->get_upsell_ids();
        $upsell_ids[] = $product_id;
        
        if ( $upsell_ids && count($upsell_ids) >= 2 ) :
                echo '<h2>'. esc_html('Sản phẩm cùng nhóm'). '</h2>';

                echo '<ul class="tuy-chon-san-pham">';
                    woocommerce_product_loop_start();
                        foreach ( $upsell_ids as $upsell_item ) :  
                            $currentItemClass = "";
                            if ($upsell_item == $product_id) {
                                $currentItemClass = "active";
                            }
                            $upsell_product = new WC_Product( $upsell_item );
                            echo '<li class="san-pham-list '. $currentItemClass .'">';
                                echo '<a href="">';
                                    // wc_get_template_part( 'content', 'product' );
                                    the_title('<span class="config-name product-title">', '</span>', $upsell_item);
                                    echo '<span class="config-price">'.$upsell_product->get_price_html().'</span>';
                                echo '</a>';
                            echo '</li>';

                        endforeach; 
                    woocommerce_product_loop_end(); 
                echo '</ul>';
        
        endif;        
        wp_reset_postdata();

    }
}
?>
