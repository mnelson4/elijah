<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce_loop, $pinnacle;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0; 
}
$woocommerce_loop['loop']++;

if(is_shop() || is_product_category() || is_product_tag()) {
	if(isset($pinnacle['product_cat_layout'])) {
		$product_cat_column = $pinnacle['product_cat_layout'];
	} else { 
		$product_cat_column = 3;
	}
} else {
	if ( empty( $woocommerce_loop['columns'] ) ) {
		$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );
	}
	$product_cat_column = $woocommerce_loop['columns'];
}
	if ($product_cat_column == '3'){ 
		$itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; 
	} else if ($product_cat_column == '5'){
		$itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6';
	} else {
		$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-4 tcol-ss-6'; 
	}
	if(!is_shop() && !is_product_category() && !is_product_tag()) {
		$woocommerce_loop['columns'] = $product_cat_column;
	}
	 ?> 

	<div class="<?php echo esc_attr($itemsize); ?> kad_shop_default kad_product">
		<div <?php wc_product_cat_class('product-category postclass grid_item ', $category ); ?>>

		<?php 
			/**
			 * woocommerce_before_subcategory hook.
			 *
			 * @hooked woocommerce_template_loop_category_link_open - 10
			 */
			do_action( 'woocommerce_before_subcategory', $category );

			 /**
			 * woocommerce_before_subcategory_title hook.
			 *
			 * @hooked woocommerce_subcategory_thumbnail - 10
			 */
			do_action( 'woocommerce_before_subcategory_title', $category );
	     	
	     	/**
			 * woocommerce_shop_loop_subcategory_title hook.
			 *
			 * @hooked woocommerce_template_loop_category_title - 10
			 */
			do_action( 'woocommerce_shop_loop_subcategory_title', $category );

			/**
			 * woocommerce_after_subcategory_title hook
			 */
			do_action( 'woocommerce_after_subcategory_title', $category );
		
			do_action( 'woocommerce_after_subcategory', $category ); ?>

</div>
</div>