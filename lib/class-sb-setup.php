<?php
/**
 * Sallybarker.org theme setup
 *
 * @package Sally_Barker_Theme
 */

if ( ! class_exists( 'SB_Setup' ) ) {

	/**
	 * Class to setup theme
	 */
	class SB_Setup {
		/**
		 * Theme version
		 *
		 * @var string $version - number followin semver standard
		 * @see https://semver.org/
		 */
		private static $version = '0.1.0';

		/**
		 * Method to return current version of theme
		 */
		public static function version() {
			return self::$version;
		}

		/**
		 * Constructor - registers everything with the WordPress API
		 */
		public function __construct() {
			/* run any upgrade routines */
			add_action( 'init', array( $this, 'upgrade' ) );
			/* filter archive titles */
			add_filter( 'get_the_archive_title', array( $this, 'get_archive_title' ) );
		}

		function get_archive_title( $title ) {
			if ( is_tax( 'education_category' ) || is_tax( 'art_category' ) ) {
				$title = single_term_title( '', false );
			}
			return $title;
		}
		
		/**
		 * Upgrade function called when the stored version number is
		 * different to the plugin version identified in this file
		 */
		public function upgrade() {

			$current_version = get_option( 'sbtheme_version' );
			if ( self::version() !== $current_version ) {
				switch ( $current_version ) {
					case false:
						/* upgrade */
						$pages = get_posts( array(
							'post_type'   => array( 'art', 'education' ),
							'post_status' => 'publish',
							'numberposts' => -1,
						) );
						if ( $pages ) {
							foreach ( $pages as $page ) {
								$attachments = get_posts( array(
									'post_type'   => 'attachment',
									'numberposts' => -1,
									'post_parent' => $page->ID,
									'orederby'    => 'menu_order',
								) );
								if ( $attachments ) {
									$gallery_items = array();
									foreach ( $attachments as $attachment ) {
										$gallery_items[] = strval( $attachment->ID );
									}
									if ( count( $gallery_items ) ) {
										update_post_meta( $page->ID, '_page_images', 'field_sb_page_gallery_images' );
										update_post_meta( $page->ID, 'page_images', $gallery_items );
									}
								}
							}
						}
						break;
				}
			}
			/* update the version option */
			update_option( 'sbtheme_version', self::version() );
		}
	}
	new SB_Setup();
}
