<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

//-> Featured Image (For front cover)
$img_url = $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->id ), 'single-post-thumbnail' );

//-> Image Gallery (For side cover)
$attachment_ids = $product->get_gallery_attachment_ids();
$attachment_urls = array();
foreach( $attachment_ids as $attachment_id ) 
{
  array_push($attachment_urls, wp_get_attachment_url( $attachment_id ));
}

//-> Book attributes
$book_author = $product->get_attribute( 'book-author' );
$publishing_house = $product->get_attribute( 'publishing-house' );
$publishing_date = $product->get_attribute( 'publishing-date' );
$n_pages = $product->get_attribute( 'number-of-pages' );
//var_dump($attachment_urls);

$classes = array(
	'col-sm-6',
	'col-xs-12',
);

?>


<figure <?php post_class(); ?>>
<style>
.book[data-book="book-<?php echo $product->id; ?>"] .cover::before {
<?php if($attachment_urls): ?>
    background: linear-gradient(to right, transparent 0%, rgba(0, 0, 0, 0.5) 5%, rgba(0, 0, 0, 0.5) 10%, transparent 100%), url(<?php  echo $attachment_urls[0]; ?>), #f2f2f2;
	background-size: cover;
<?php else : ?>
	background: linear-gradient(to right, transparent 0%, rgba(0, 0, 0, 0.5) 5%, rgba(0, 0, 0, 0.5) 60%, transparent 100%), url(<?php  echo $image[0]; ?>), #f2f2f2;
	background-size: cover;
<?php endif; ?>
}
</style>
						<div class="perspective">
							<div class="book" data-book="book-<?php echo $product->id; ?>">
								<div class="cover book_info">
									<a href="#">
									<div class="front" style="background: linear-gradient(to right, rgba(0, 0, 0, 0.1) 0%, rgba(211, 211, 211, 0.1) 5%, rgba(255, 255, 255, 0.15) 5%, rgba(255, 255, 255, 0.1) 9%, rgba(0, 0, 0, 0.01) 100%), url(<?php  echo $image[0]; ?>), #f2f2f2; background-repeat: round;">
									</div>
									</a>
									<div class="inner inner-left"></div>
								</div>
								<div class="inner inner-right"></div>
							</div>
						</div>

   
    <div class="buttons">
		<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
    <p><?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?></p>
    </div>
    
						<figcaption>
						<a href="<?php echo get_permalink(); ?>">
						<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>
						</a>
						<h2><span style="min-height: 52px;"><?php echo $book_author; ?></span></h2>
						</figcaption>
						<div class="details">
							<ul>
                                <li class="book_excerpt"><?php the_excerpt(); ?></li>
								<li><?php  echo $publishing_house; ?></li>
								<li><?php  echo $publishing_date; ?></li>
								<li><?php  echo $n_pages; ?> pages</li>
							</ul>
                        </div>
</figure>

