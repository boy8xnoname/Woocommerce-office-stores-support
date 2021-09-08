'use strict'
var wcUpsellSuggest = {}

;
(function($) {


    wcUpsellSuggest.initFunction = function() {
        $(document).ready(function() {
            wcUpsellSuggest.ProdductCarousel()
        })
        setInterval(function() {
            wcUpsellSuggest.ProdductCarousel()
        }, 6000);

        wcUpsellSuggest.ProdductAddToCart()
    }

    wcUpsellSuggest.ProdductCarousel = function() {
        $('.suggest-carousel-products').slick({
            dots: true,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

    }
    wcUpsellSuggest.ProdductAddToCart = function() {
        jQuery(document).ready(function($) {
            $('.checkout_single_add_to_cart_button').on('click', function(e) {
                e.preventDefault();
                let $thisbutton = $(this),
                    $form = $thisbutton.closest('form.cart'),
                    product_qty = $thisbutton.attr('data_product_qty') || 1,
                    product_id = $thisbutton.attr('data_product_id')
                    // variation_id = $form.find('input[name=variation_id]').val() || 0;
                var data = {
                    action: 'ql_woocommerce_ajax_add_to_cart',
                    product_id: product_id,
                    product_sku: '',
                    quantity: product_qty,
                    // variation_id: variation_id,
                };
                $.ajax({
                    type: 'post',
                    url: wc_add_to_cart_params.ajax_url,
                    data: data,
                    beforeSend: function(response) {
                        $thisbutton.removeClass('added').addClass('loading');
                    },
                    complete: function(response) {
                        $thisbutton.addClass('added').removeClass('loading');
                    },
                    success: function(response) {
                        if (response.error & response.product_url) {
                            window.location = response.product_url;
                            return;
                        } else {
                            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                        }

                        jQuery('body').trigger('update_checkout');

                    },
                });
            });
        });
    }

    $(function() {
        wcUpsellSuggest.initFunction()
    })
})(jQuery) // eslint-disable-line