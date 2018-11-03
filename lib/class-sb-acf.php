<?php
/**
 * Sallybarker.org theme Advanced Custom Fields definitions
 *
 * @package Sally_Barker_Theme
 */

if ( ! class_exists( 'SB_ACF' ) ) {
	/**
	 * Class to create custom fields for sallybarker.org theme
	 */
	class SB_ACF {
		/**
		 * Constructor - registers field definitions using ACF init hook
		 */
		public function __construct() {
			/* Add fields to pages */
			add_action( 'acf/init', array( $this, 'register_page_fields' ) );
			/* Add fields to home page */
			add_action( 'acf/init', array( $this, 'register_home_page_fields' ) );
		}

		/**
		 * Registers a gallery field for each page
		 */
		public function register_page_fields() {
			acf_add_local_field_group(array(
				'key'                   => 'group_sb_page_gallery',
				'title'                 => 'Image gallery',
				'fields'                => array(
					array(
						'key'     => 'field_sb_page_gallery_images',
						'label'   => 'Images',
						'name'    => 'page_images',
						'type'    => 'gallery',
						'insert'  => 'append',
						'library' => 'all',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
						array(
							'param'    => 'page_template',
							'operator' => '!=',
							'value'    => 'home_page.php',
						),
					),
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'education',
						),
					),
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'art',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'acf_after_title',
				'style'                 => 'seamless',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => 1,
				'description'           => '',
			));
		}

		/**
		 * Registers a repeater for the home page for a gallery which links to other pages and has captions
		 */
		public function register_home_page_fields() {
			acf_add_local_field_group( array(
				'key'                   => 'group_sb_home_gallery',
				'title'                 => 'Linked Images',
				'fields'                => array(
					array(
						'key'          => 'field_sb_home_gallery_images',
						'label'        => 'Images',
						'name'         => 'linked_images',
						'type'         => 'repeater',
						'collapsed'    => 'field_sb_home_gallery_image',
						'layout'       => 'row',
						'button_label' => 'Add image',
						'sub_fields'   => array(
							array(
								'key'           => 'field_sb_home_gallery_image',
								'label'         => 'Image',
								'name'          => 'image',
								'type'          => 'image',
								'required'      => 1,
								'return_format' => 'array',
								'preview_size'  => 'medium',
								'library'       => 'all',
							),
							array(
								'key'   => 'field_sb_home_gallery_caption',
								'label' => 'Caption',
								'name'  => 'caption',
								'type'  => 'text',
							),
							array(
								'key'           => 'field_sb_home_gallery_link',
								'label'         => 'Link',
								'name'          => 'link',
								'type'          => 'post_object',
								'return_format' => 'url',
								'ui'            => 1,
							),
						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'home_page.php',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'acf_after_title',
				'style'                 => 'seamless',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => 1,
				'description'           => '',
			));
		}
	}
	new SB_ACF();
}
