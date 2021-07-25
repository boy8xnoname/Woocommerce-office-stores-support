<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Wc_Office_store_Group_Product
{
    
	protected $loader;


	protected $plugin_name;

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
            echo '<div class="group-product-upsell">';     
                echo '<h3 class="tuy-chon-san-pham-title">'. esc_html('Sản phẩm cùng nhóm'). '</h3>';

                echo '<ul class="tuy-chon-san-pham">';
                    woocommerce_product_loop_start();
                        foreach ( $upsell_ids as $upsell_item ) :  
                            $currentItemClass = "";
                            if ($upsell_item == $product_id) {
                                $currentItemClass = "active";
                            }
                            $productObject = new WC_Product( $upsell_item  );
                            $productSKU = $productObject->get_sku();

                            echo '<li class="san-pham-list '. $currentItemClass .'">';
                                echo '<a href="'.esc_url( get_permalink( $upsell_item ) ).'">';
                                    // wc_get_template_part( 'content', 'product' );
                                    if(!empty($productSKU)) {
                                        echo '<span class="config-name product-SKU">'.$productSKU.'</span>';
                                    } else { 
                                        echo '<span class="config-name product-title">'.get_the_title( $upsell_item ).'</span>';
                                    }
                                    echo '<span class="config-price">'.$productObject->get_price_html().'</span>';
                                echo '</a>';
                            echo '</li>';

                        endforeach; 
                    woocommerce_product_loop_end(); 
                echo '</ul>';
            echo '</div>';
        
        endif;        
        wp_reset_postdata();
    }
}
?>
