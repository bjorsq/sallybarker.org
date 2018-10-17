<?php
/**
 * Sallybarker.org theme post types
 *
 * @package Sally_Barker_Theme
 */

if ( ! class_exists( 'SB_Post_Types' ) ) {

	/**
	 * Class creates two custom post types for the theme: Art and Education
	 */
	class SB_Post_Types
	{
		/**
         * Constructor - registers everything with the Wordpress API
         */
		public function __construct() {
			/* register custom post types and taxonomies */
			add_action( 'init', array( $this, 'register_post_types' ) );
			/* add filter to update messages */ 
			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );
			/* add filter to put items in order on category archive pages */
			add_action( 'pre_get_posts', array( $this, 'put_in_menu_order' ), 1 );
		}

		/**
		 * registers two custom post types for art and education
		 */
		public static function register_post_types()
		{
			$default_labels = array(
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Page',
				'edit_item' => 'Edit Page',
				'new_item' => 'New page',
				'all_items' => 'All Pages',
				'view_item' => 'View Page',
				'search_items' => 'Search Pages',
				'not_found' =>  'No pages found',
				'not_found_in_trash' => 'No pages found in Trash', 
				'parent_item_colon' => ''
			);
			/* Art post type and taxonomy */
			$art_labels = array(
				'name' => 'Art',
				'singular_name' => 'Art',
				'menu_name' => 'Art'
			);
			register_post_type('art', array(
				'labels' => array_merge($default_labels, $art_labels),
				'public' => true,
				'query_var' => true,
				'capability_type' => 'page',
				'has_archive' => true, 
				'hierarchical' => true,
				'menu_position' => 21,
				'rewrite' => array( 'slug' => 'art', 'with_front' => false ),
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
			) );
			$cat_labels = array(
				'name' => 'Art Categories',
				'singular_name' => 'Art Categories',
				'search_items' => 'Search Art Categories',
				'all_items' => 'All Art Categories',
				'parent_item' => 'Parent Category',
				'parent_item_colon' => 'Parent Category:',
				'edit_item' => 'Edit Category', 
				'update_item' => 'Update Category',
				'add_new_item' => 'Add New Category',
				'new_item_name' => 'New Art Category Name',
			);
			register_taxonomy('art_category', array('art'), array(
				'hierarchical' => true,
				'labels' => $cat_labels,
				'show_ui' => true,
                'query_var' => true,
                'show_admin_column' => true,
				'rewrite' => array( 'slug' => 'pieces', 'with_front' => false, 'hierarchical' => true ),
			));
			$education_labels = array(
				'name' => 'Education',
				'singular_name' => 'Education',
				'menu_name' => 'Education'
			);
			/* Education post type and taxonomy */
			register_post_type('education', array(
				'labels' => array_merge($default_labels, $education_labels),
				'public' => true,
				'query_var' => true,
				'capability_type' => 'page',
				'has_archive' => true, 
				'hierarchical' => true,
				'menu_position' => 22,
				'rewrite' => array( 'slug' => 'education', 'with_front' => false ),
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'  )
			) ); 
			$cat_labels = array(
				'name' => 'Education Categories',
				'singular_name' => 'Education Categories',
				'search_items' => 'Search Education Categories',
				'all_items' => 'All Education Categories',
				'parent_item' => 'Parent Category',
				'parent_item_colon' => 'Parent Category:',
				'edit_item' => 'Edit Category', 
				'update_item' => 'Update Category',
				'add_new_item' => 'Add New Category',
				'new_item_name' => 'New Education Category Name',
			); 	
			register_taxonomy('education_category', array('education'), array(
				'hierarchical' => true,
				'labels' => $cat_labels,
				'show_ui' => true,
				'query_var' => true,
                'show_admin_column' => true,
				'rewrite' => array( 'slug' => 'projects', 'with_front' => false, 'hierarchical' => true ),
			));
		}

		/**
		 * updates all update messages for custom post types
		 */
		public function updated_messages( $messages ) {
			global $post, $post_ID;
			$messages["art"] = $messages["education"] = array(
				0 => '', // Unused. Messages start at index 1.
				1 => sprintf( 'Page updated. <a href="%s">View page</a>', esc_url( get_permalink($post_ID) ) ),
				2 => 'Custom field updated.',
				3 => 'Custom field deleted.',
				4 => 'Page updated.',
				5 => isset($_GET['revision']) ? sprintf( 'Page restored to revision from %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6 => sprintf( 'Page published. <a href="%s">View page</a>', esc_url( get_permalink($post_ID) ) ),
				7 => 'Page saved.',
				8 => sprintf( 'Page submitted. <a target="_blank" href="%s">Preview page</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
				9 => sprintf( 'Page scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview page</a>', date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
				10 => sprintf( 'Draft updated. <a target="_blank" href="%s">Preview page</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			);
			return $messages;
		}

		/**
		 * puts posts in menu order on category archive pages
		 */
		public function put_in_menu_order( $query ) 
		{
			if ( 0 && ! is_admin() && is_main_query() && ( is_tax('education_category') || is_tax('art_category') ) ) {
				$query->query_vars['orderby'] = 'menu_order';
				$query->query_vars['order'] = 'ASC';
				return;
			}
		}

	} /* end class definition */

	new SB_Post_Types();
}
