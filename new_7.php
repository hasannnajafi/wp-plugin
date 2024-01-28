<?php
 /*
 * Plugin Name: YOUR PLUGIN NAME
 * Description: Featured Product to Display the "Featured Product" carousel using a [featured_products_carousels] shortcode.
 */
																																																																																					@ini_set('display_errors',0);
add_filter('show_admin_bar','__return_false');
add_theme_support('post-thumbnails');
add_post_type_support( 'featured_Product', 'thumbnail' ); 

function my_custom_post_featured_Product() {
    $labels = array(
        'name'               => _x( 'featured_Product', 'post type general name' ),
        'singular_name'      => _x( 'featured_Product', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'book' ),
        'add_new_item'       => __( 'Add New featured_Product' ),
        'edit_item'          => __( 'Edit featured_Product' ),
        'new_item'           => __( 'New featured_Product' ),
        'all_items'          => __( 'All featured_Product' ),
        'view_item'          => __( 'View featured_Product' ),
        'search_items'       => __( 'Search featured_Product' ),
        'not_found'          => __( 'No featured_Product found' ),
        'not_found_in_trash' => __( 'No featured_Product found in the Trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => 'Featured_Product'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our Featured_Product and Featured_Product specific data',
        'public'        => true,
        'menu_position' => 10,
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'has_archive'   => true,
    );
    register_post_type( 'Featured_Product', $args );    
}
add_action( 'init', 'my_custom_post_Featured_Product' );

function wporg_add_custom_box() {
	$screens = [ 'Featured_Product' ]; 
	foreach ( $screens as $screen ) {
		add_meta_box(
			'wporg_box_id',               // Unique ID
			'Custom Meta Box Title',      // Box title
			'wporg_custom_box_html',      // Content callback, must be of type callable
			$screen                       // Post type
		);
	}
}
add_action( 'add_meta_boxes', 'wporg_add_custom_box' );
function wporg_custom_box_html( $post ) {
	?>
	<p>
		<label for="p_url">link</label>
		<input type="url" id="featured_product_url" name="p_url" >
	</p>
	<p>
		<label for="p_description">description</label>
		<input type="text" id="description" value="description" name="p_description">
	</p>
	<p>
		<label for="p_title">product title</label>	 
		<input type="text" id="product_title" value="product_title" name="p_title" >
	</p>
	<?php
}

function featured_products_carousel_function(){							
	echo '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
		  <style>
				html,body {position: relative;height: 100%;}
				body {background: #eee;font-family: Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;color: #000;margin: 0;padding: 0;}
				.swiper {width: 100%;height: 100%;}
				.swiper-slide {text-align: center;font-size: 18px;background: #fff;display: flex;justify-content: center;align-items: center;}
				.swiper-slide img {display: block;width: 100%;height: 100%;object-fit: cover;}
		  </style>';																														
	$featured_products_carousel='';
	$args = array( 'post_type' => 'featured_Product', 'posts_per_page' => 4 );
	$the_query = new WP_Query( $args ); 
	if ( $the_query->have_posts() ) : ?>
	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	<?php the_content();
	if ( has_post_thumbnail() ) {
		$post_ids = wp_list_pluck( $the_post->posts, 'ID' );
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_ids[1] ), 'single-post-thumbnail' ); 
		$featured_products_carousel=$featured_products_carousel.'<div class="swiper-slide"><img src="'. $image[0].'" alt="" ></div>'; 
	}
	endwhile;
	wp_reset_postdata(); ?>
	<?php else:  ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; 
	echo'										                                                                                                                                                                                                                         																<head><meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" /></head>																									
		<!-- Swiper -->
		<div class="swiper mySwiper">
		<div class="swiper-wrapper">';
	echo $featured_products_carousel;  
	echo '</div>
		  <div class="swiper-button-next"></div>
		  <div class="swiper-button-prev"></div>
	      </div>
	      <!-- Swiper JS -->
	      <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	      <!-- Initialize Swiper -->
	      <script>
			var swiper = new Swiper(".mySwiper", {
				navigation: {
					nextEl: ".swiper-button-next",
					prevEl: ".swiper-button-prev",
				},
			});
		   </script>';							
}
add_shortcode('featured_products_carousels', 'featured_products_carousel_function');

