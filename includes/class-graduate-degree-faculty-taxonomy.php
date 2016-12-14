<?php

class WSUWP_Graduate_Degree_Faculty_Taxonomy {
	/**
	 * @var WSUWP_Graduate_Degree_Faculty_Taxonomy
	 */
	private static $instance;

	/**
	 * The slug used to register the faculty taxonomy.
	 *
	 * @var string
	 */
	var $taxonomy_slug = 'gs-faculty';

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.0.1
	 *
	 * @return \WSUWP_Graduate_Degree_Faculty_Taxonomy
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSUWP_Graduate_Degree_Faculty_Taxonomy();
			self::$instance->setup_hooks();
		}
		return self::$instance;
	}

	/**
	 * Setup hooks to include.
	 *
	 * @since 0.0.1
	 */
	public function setup_hooks() {
		add_action( 'init', array( $this, 'register_taxonomy' ), 20 );
	}

	/**
	 * Registers the faculty taxonomy that will track faculty members that should be
	 * displayed in a degree program's factsheet.
	 *
	 * @since 0.0.1
	 */
	public function register_taxonomy() {
		$labels = array(
			'name'          => 'Faculty Members',
			'singular_name' => 'Faculty Member',
			'search_items'  => 'Search faculty members',
			'all_items'     => 'All Faculty',
			'edit_item'     => 'Edit Faculty Member',
			'update_item'   => 'Update Faculty Member',
			'add_new_item'  => 'Add New Faculty Member',
			'new_item_name' => 'New Faculty Member',
			'menu_name'     => 'Faculty Members',
		);
		$args = array(
			'labels'            => $labels,
			'description'       => 'Faculty associated with degree program factsheets.',
			'public'            => false,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'rewrite'           => false,
		);
		register_taxonomy( $this->taxonomy_slug, array( WSUWP_Graduate_Degree_Programs()->post_type_slug ), $args );
	}
}
