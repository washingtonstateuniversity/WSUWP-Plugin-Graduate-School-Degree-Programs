<?php

class WSUWP_Graduate_Degree_Contact_Taxonomy {
	/**
	 * @since 0.0.1
	 *
	 * @var WSUWP_Graduate_Degree_Contact_Taxonomy
	 */
	private static $instance;

	/**
	 * The slug used to register the contact taxonomy.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	var $taxonomy_slug = 'gs-contact';

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.0.1
	 *
	 * @return \WSUWP_Graduate_Degree_Contact_Taxonomy
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSUWP_Graduate_Degree_Contact_Taxonomy();
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
		add_action( "{$this->taxonomy_slug}_edit_form_fields", array( $this, 'term_edit_form_fields' ), 10 );
		add_action( "edit_{$this->taxonomy_slug}", array( $this, 'save_term_form_fields' ) );
	}

	/**
	 * Registers the contact taxonomy that tracks one or more pieces of contact information
	 * displayed with a degree program's factsheet.
	 *
	 * @since 0.0.1
	 */
	public function register_taxonomy() {
		$labels = array(
			'name'          => 'Contacts',
			'singular_name' => 'Contact',
			'search_items'  => 'Search contacts',
			'all_items'     => 'All Contacts',
			'edit_item'     => 'Edit Contact',
			'update_item'   => 'Update Contact',
			'add_new_item'  => 'Add New Contact',
			'new_item_name' => 'New Contact',
			'menu_name'     => 'Contacts',
		);
		$args = array(
			'labels'            => $labels,
			'description'       => 'Contacts associated with degree program factsheets.',
			'public'            => false,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'rewrite'           => false,
		);
		register_taxonomy( $this->taxonomy_slug, array( WSUWP_Graduate_Degree_Programs()->post_type_slug ), $args );
	}

	/**
	 * Saves the additional form fields whenever a term is updated.
	 *
	 * @since 0.0.1
	 *
	 * @param int $term_id The ID of the term being edited.
	 */
	public function save_term_form_fields( $term_id ) {
		global $wp_list_table;

		if ( 'editedtag' !== $wp_list_table->current_action() ) {
			return;
		}

		// Reuse the default nonce that is checked in `edit-tags.php`.
		check_admin_referer( 'update-tag_' . $term_id );

		return;
	}

	/**
	 * Captures information about a contact as term meta.
	 *
	 * @since 0.0.1
	 *
	 * @param WP_Term $term
	 */
	public function term_edit_form_fields( $term ) {

	}
}
