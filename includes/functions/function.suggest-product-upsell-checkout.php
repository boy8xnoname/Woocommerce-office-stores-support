<?php 

if ( ! defined( 'WPINC' ) ) {
	die;
}

class WC_suggest_product_upsell_checkout {

    protected $loader;

	protected $plugin_name;

	protected $version;

    public function __construct()
    {
        $this->actions = array();
        $this->filters = array();
    }

    

    public static function wc_suggest_carousel_products($limit = '-1', $columns = 4, $orderby = 'rand', $order = 'desc') {
        ob_start();
        $cart  = WC()->cart;
        if ( WC()->cart->is_empty() ) {
            // echo 'nothing';
            return;
        }

        $upsell_ids = $cart_item_ids = array();
        
        // Loop through cart items
        foreach( $cart->get_cart() as $cart_item ) { 
            // Merge all cart items upsells ids
            $upsell_ids      = array_merge( $upsell_ids, $cart_item['data']->get_upsell_ids() );
            $cart_item_ids[] = $cart_item['product_id'];
        }
        
        // Remove cart item ids from upsells
        $upsell_ids = array_diff($upsell_ids, $cart_item_ids);
        $upsell_ids = array_unique($upsell_ids); // Remove duplicated Ids
        
        // Handle the legacy filter which controlled posts per page etc.
        $args = apply_filters( 'woocommerce_upsell_display_args', array(
            'posts_per_page' => $limit,
            'orderby'        => $orderby,
            'order'          => $order,
            'columns'        => $columns,
        ));
        
        
        wc_set_loop_prop( 'name', 'up-sells' );
        wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_upsells_columns', isset( $args['columns'] ) ? $args['columns'] : $columns ) );

        $orderby = apply_filters( 'woocommerce_upsells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : $orderby );
        $_order  = apply_filters( 'woocommerce_upsells_order', isset( $args['order'] ) ? $args['order'] : $order );
        $limit   = apply_filters( 'woocommerce_upsells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit );
        
        // Get visible upsells then sort them at random, then limit result set.
        $upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $upsell_ids ), 'wc_products_array_filter_visible' ), $orderby, $_order );
        $upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;

        if ( $upsells ) :
            echo '<div class="checkout-suggest-products">';
                echo'<h3 class="suggest-products-title">Có thể bạn muốn mua thêm cùng trong đơn hàng này</h3>';
                echo '<div class="suggest-products-list">';
                foreach ( $upsells as $upsell ) { 
                $productObject = new WC_Product( $upsell  );
                $productSKU = $productObject->get_sku();
                // var_dump($productObject);
                echo '<div class="single-product-item">';
                    echo '<div class="product-image-box">'. $productObject->get_image('thumbnail').'</div>';
                    echo '<div class="product-content-box">';
                        echo '<div class="product-name-and-price">';                        
                            if(!empty($productSKU)) {
                                echo '<p><span class="config-name product-SKU">'.$productSKU.'</span></p>';
                            } else { 
                                echo '<p><span class="config-name product-title">'.get_the_title( $upsell).'</span></p>';
                            }
                            echo '<span class="config-price">'.$productObject->get_price_html().'</span>';
                        echo '</div>';
                        echo '<div class="product-action"><a href="#" class="checkout_single_add_to_cart_button" data_product_id= "'.$productObject->get_id().'"  data_product_qty="1">'.__( 'Mua ngay' ).'</a></div>';
                    echo '</div>';
                echo '</div>';
                }
            echo '</div></div>';
        endif;
        wp_reset_postdata();
        
        // get_template_part( WC_OFFICE_STORE_DIR.'/templates/single-product/checkout-up-sells.php',
        //     array(
        //         'upsells'        => $upsells,
        //         'posts_per_page' => $limit,
        //         'orderby'        => $orderby,
        //         'columns'        => $columns,
        //     ) 
        // );
    
        $result = ob_get_contents();
        ob_end_clean();
    
        return $result;
    }
}


add_action('wp_ajax_ql_woocommerce_ajax_add_to_cart', 'ql_woocommerce_ajax_add_to_cart'); 
add_action('wp_ajax_nopriv_ql_woocommerce_ajax_add_to_cart', 'ql_woocommerce_ajax_add_to_cart');          
function ql_woocommerce_ajax_add_to_cart() {
    $product_id = apply_filters('ql_woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    // $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('ql_woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id); 
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity) && 'publish' === $product_status) { 
        do_action('ql_woocommerce_ajax_added_to_cart', $product_id);
        if ('yes' === get_option('ql_woocommerce_cart_redirect_after_add')) { 
            wc_add_to_cart_message(array($product_id => $quantity), true); 
        } 
        WC_AJAX :: get_refreshed_fragments(); 
    } else { 
            $data = array( 
                'error' => true,
                'product_url' => apply_filters('ql_woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));
            echo wp_send_json($data);
    }
    wp_die();
}