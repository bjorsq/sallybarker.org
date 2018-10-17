<?php
/**
 * Sallybarker.org theme actions
 *
 * @package Sally_Barker_Theme
 */

if ( ! class_exists( 'SB_Actions' ) ) {
	/**
	 * Class to create custom fields for sallybarker.org theme
	 */
	class SB_Actions {
		/**
		 * Constructor - registers field definitions using ACF init hook
		 */
		public function __construct() {
			/* Add generatepress action for gallery display */
			add_action( 'generate_before_content', array( $this, 'display_gallery' ) );
			/* modify or remove any existing generatepress actions */
			add_action( 'after_setup_theme', array( $this, 'gp_modify_actions' ) );
			/* remove author from posts */
			add_filter( 'generate_post_author', '__return_false', 10001 );
			/* filter archive titles */
			add_filter( 'get_the_archive_title', array( $this, 'get_archive_title' ) );
		}

		/**
		 * Action function called before main content display
		 */
		public function display_gallery() {
			global $post;
			if ( is_single() ) {
				if ( is_page_template( 'home_page.php' ) ) {
					if ( have_rows( 'linked_images' ) ) {
						print( '<div class="flexbin">' );
						while ( have_rows( 'linked_images' ) ) {
							the_row();
							$image = get_sub_field( 'image' );
							$caption = get_sub_field( 'caption' );
							$caption = $caption ? sprintf( '<span class="caption">%s</span>', esc_html( $caption ) ) : '';
							$url = get_sub_field( 'link' );
							$url = $url ? $url : esc_attr( $image['sizes']['large'] );
							printf( '<a href="%s"><img src="%s">%s</a>', $url, esc_attr( $image['sizes']['medium'] ), $caption );
						}
						print( '</div>' );
					}
				} else {
					$images = get_field( 'page_images', $post->ID );
					if ( $images ) {
						print( '<div class="flexbin">' );
						foreach ( $images as $image ) {
							$caption = ( ! empty( $image['caption'] ) ) ? sprintf( '<span class="caption">%s</span>', esc_html( $image['caption'] ) ) : '';
							printf( '<a href="%s"><img src="%s">%s</a>', esc_attr( $image['sizes']['large'] ), esc_attr( $image['sizes']['medium'] ), $caption );
						}
						print( '</div>' );
					}
				}
			}
		}

		/**
		 * modifies output for post type archive and taxonomy archive titles
		 */
		function get_archive_title( $title ) {
			if ( is_tax( 'education_category' ) || is_tax( 'art_category' ) ) {
				$title = single_term_title( '', false );
			}
			if ( is_post_type_archive( 'art' ) || is_post_type_archive( 'education' ) ) {
				$title = ucfirst( get_post_type() );
			}
			return $title;
		}
		

		/**
		 * method used to modify generatepress actions after theme has loaded
		 */
		public function gp_modify_actions() {
			remove_action( 'generate_before_content', 'generate_featured_page_header_inside_single', 10 );
		}
	}
	new SB_Actions();
}
