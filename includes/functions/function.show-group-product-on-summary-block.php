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

        $groupDatas = array();

        $groupDataTitle = !empty(get_option( 'product_group_title')) ? get_option( 'product_group_title') : 'Sản phẩm cùng nhóm';

        $product_id = $product->is_type('variation') ? $product->get_parent_id() : $product->get_id();
        $product = wc_get_product( $product_id );
        $groupDataOption = get_option( 'select_group_product_data');
        if($groupDataOption == '' || $groupDataOption == 'up_sell') {
            $groupDatas = $product->get_upsell_ids();
        } else {
            $groupDatas = $product->get_cross_sell_ids();
        }
        $groupDatas[] = $product_id;
        
        if ( !empty($groupDatas) && count($groupDatas) >= 2 ) :
            echo '<div class="group-product-upsell">';     
                echo '<h3 class="tuy-chon-san-pham-title">'. esc_html($groupDataTitle). ':</h3>';
                echo '<ul class="tuy-chon-san-pham">';
                    woocommerce_product_loop_start();
                        foreach ( $groupDatas as $group_item ) :  
                            $currentItemClass = "";
                            if ($group_item == $product_id) {
                                $currentItemClass = "active";
                            }
                            $productObject = new WC_Product( $group_item  );
                            $productSKU = $productObject->get_sku();
                            echo '<li class="san-pham-list '. $currentItemClass .'">';
                                echo '<a href="'.esc_url( get_permalink( $group_item ) ).'">';
                                    // wc_get_template_part( 'content', 'product' );
                                    if(!empty($productSKU)) {
                                        echo '<span class="config-name product-SKU">'.$productSKU.'</span>';
                                    } else { 
                                        echo '<span class="config-name product-title">'.  mb_strimwidth(get_the_title( $group_item ), 0, 36, '...').'</span>';
                                    }
                                    echo '<span class="config-price">'.$productObject->get_price_html().'</span>';
                                echo '</a>';
                            echo '</li>';
                        endforeach; 
                    woocommerce_product_loop_end(); 
                echo '</ul>';
            echo '</div>';
            echo '<style> .colorgb-shop-info{ display: none !important; } hr.colorgb-shop-info-hr { display: none !important; } </style>';
        endif;        
        wp_reset_postdata();
    }
}
?>
