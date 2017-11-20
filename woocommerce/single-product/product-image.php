<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$book_author = $product->get_attribute( 'book-author' );
//-> Featured Image (For front cover)
$img_url = $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->id ), 'single-post-thumbnail' );


?>
<div class="images">
<style>
.bk-cover{
	background: url(<?php  echo $image[0]; ?>);
}
.bk-list li .bk-left{
	background: linear-gradient(to right, transparent 0%, rgba(0, 0, 0, 0.9) 5%, rgba(0, 0, 0, 0.5) 5%, transparent 100%), url(<?php  echo $image[0]; ?>), #f2f2f2;
	background-size: contain;
	/*
	background: url(<?php  //echo $image[0]; ?>);
	background-size: cover;
	*/
}
</style>
	<ul id="bk-list" class="bk-list clearfix">
		<li>
			<div class="bk-book book-1 bk-bookdefault">
				<div class="bk-front">
					<div class="bk-cover-back"></div>
					<div class="bk-cover">
					</div>
				</div>
				<div class="bk-page">
					<div class="bk-content bk-content-current">
						<div class="single-book-container">
							<?php
							if ( has_post_thumbnail() ) {
								$attachment_count = count( $product->get_gallery_attachment_ids() );
								$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
								$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
								$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
									'title'	 => $props['title'],
									'alt'    => $props['alt'],
								) );
								echo apply_filters(
									'woocommerce_single_product_image_html',
									sprintf(
										'<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto%s">%s</a>',
										esc_url( $props['url'] ),
										esc_attr( $props['caption'] ),
										$gallery,
										$image
									),
									$post->ID
								);
							} else {
								echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
							}

							do_action( 'woocommerce_product_thumbnails' );
							?>
						</div>
					</div>
					<div class="bk-content">
						<p>Whale catfish leatherjacket deep sea anglerfish grenadier sawfish pompano dolphinfish carp large-eye bream, squeaker amago. Sandroller; rough scad, tiger shovelnose catfish snubnose parasitic eel? Black bass soldierfish duckbill--Rattail Atlantic saury Blind shark California halibut; false trevally warty angler!</p>
					</div>
					<div class="bk-content">
						<p>Trahira giant wels cutlassfish snapper koi blackchin mummichog mustard eel rock bass whiff murray cod. Bigmouth buffalo ling cod giant wels, sauger pink salmon. Clingfish luderick treefish flatfish Cherubfish oldwife Indian mul gizzard shad hagfish zebra danio. Butterfly ray lizardfish ponyfish muskellunge Long-finned sand diver mullet swordfish limia ghost carp filefish.</p>
					</div>
				</div>
				<div class="bk-back">
					<p><?php echo $post->post_excerpt; ?></p>
				</div>
				<div class="bk-right"></div>
				<div class="bk-left">
					<h2>
						<span><?php the_title(); ?></span>
						<span><?php echo $book_author; ?></span>
					</h2>
				</div>
				<div class="bk-top"></div>
				<div class="bk-bottom"></div>
			</div>
			<div class="bk-info">
				<button class="bk-bookview">View inside</button>
				</div>
		</li>
	</ul>
</div>
