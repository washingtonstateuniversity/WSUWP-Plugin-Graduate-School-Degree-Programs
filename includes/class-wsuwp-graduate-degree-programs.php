<?php

class WSUWP_Graduate_Degree_Programs {
	/**
	 * @var WSUWP_Graduate_Degree_Programs
	 */
	private static $instance;

	/**
	 * The slug used to register the factsheet post type.
	 *
	 * @var string
	 */
	var $post_type_slug = 'gs-factsheet';

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.0.1
	 *
	 * @return \WSUWP_Graduate_Degree_Programs
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSUWP_Graduate_Degree_Programs();
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
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	/**
	 * Register the degree program factsheet post type.
	 *
	 * @since 0.0.1
	 */
	public function register_post_type() {
		$labels = array(
			'name' => 'Factsheets',
			'singular_name' => 'Factsheet',
			'all_items' => 'All Factsheets',
			'add_new_item' => 'Add Factsheet',
			'edit_item' => 'Edit Factsheet',
			'new_item' => 'New Factsheet',
			'view_item' => 'View Factsheet',
			'search_items' => 'Search Factsheets',
			'not_found' => 'No factsheets found',
			'not_found_in_trash' => 'No factsheets found in trash',
		);

		$args = array(
			'labels' => $labels,
			'description' => 'Graduate degree program factsheets',
			'public' => true,
			'hierarchical' => false,
			'menu_icon' => 'dashicons-groups',
			'supports' => array (
				'title',
				'editor',
				'revisions',
				'thumbnail',
			),
			'has_archive' => 'degrees',
			'rewrite' => array( 'slug' => 'degrees/factsheet', 'with_front' => false ),
		);
		register_post_type( $this->post_type_slug, $args );
	}

	/**
	 * Register the meta keys used to store degree factsheet data.
	 *
	 * @since 0.1.0
	 */
	public function register_meta() {
		$default_args = array(
			'show_in_rest' => true,
			'single' => true,
		);

		$args = $default_args;

		$args['description'] = 'Factsheet degree ID';
		$args['type'] = 'int';
		$args['sanitize_callback'] = 'absint';
		register_meta( $this->post_type_slug, 'gsdp_degree_id', $args );

		$args['description'] = 'Total number of grad students';
		$args['type'] = 'int';
		$args['sanitize_callback'] = 'absint';
		register_meta( $this->post_type_slug, 'gsdp_grad_students_total', $args );

		$args['description'] = 'Number of aided grad students';
		$args['type'] = 'int';
		$args['sanitize_callback'] = 'absint';
		register_meta( $this->post_type_slug, 'gsdp_grad_students_aided', $args );

		$args['description'] = 'Admission GPA';
		$args['type'] = 'string';
		$args['sanitize_callback'] = 'WSUWP_Graduate_Degree_Programs::sanitize_gpa';
		register_meta( $this->post_type_slug, 'gsdp_admission_gpa', $args );

		$args['description'] = 'Degree home page';
		$args['type'] = 'string';
		$args['sanitize_callback'] = 'esc_url_raw';
		register_meta( $this->post_type_slug, 'gsdp_degree_url', $args );

		$args['description'] = 'Program name';
		$args['type'] = 'string';
		$args['sanitize_callback'] = 'sanitize_text_field';
		register_meta( $this->post_type_slug, 'gsdp_program_name', $args );

		$args['description'] = 'Oracle program name';
		$args['type'] = 'string';
		$args['sanitize_callback'] = 'sanitize_text_field';
		register_meta( $this->post_type_slug, 'gsdp_oracle_name', $args );

		$args['description'] = 'Plan name';
		$args['type'] = 'string';
		$args['sanitize_callback'] = 'sanitize_text_field';
		register_meta( $this->post_type_slug, 'gsdp_plan_name', $args );
	}

	/**
	 * Add the meta boxes used to capture information about a degree factsheet.
	 *
	 * @since 0.0.1
	 *
	 * @param string $post_type
	 */
	public function add_meta_boxes( $post_type ) {
		if ( $this->post_type_slug !== $post_type ) {
			return;
		}

		add_meta_box( 'factsheet-primary', 'Factsheet Data', array( $this, 'display_factsheet_primary_meta_box' ), null, 'normal', 'high' );
	}

	/**
	 * Capture the main set of data about a degree factsheet.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function display_factsheet_primary_meta_box( $post ) {
		$keys = get_registered_meta_keys( $post->post_type );
		$data = get_registered_metadata( $post->post_type, $post->ID );

		echo '<div class="factsheet-primary-inputs">';
		foreach( $keys as $key => $meta ) {
			?>
			<div class="factsheet-primary-input factsheet-<?php echo esc_attr( $meta['type'] ); ?>"">
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $meta['description']); ?></label>
				<input type="text"
				       name="<?php echo esc_attr( $key ); ?>"
				       value="<?php if ( 'int' === $meta['type'] ) { echo absint( $data[ $key ] ); } else { echo esc_attr( $data[ $key ] ); } ?>" />
			</div>
			<?php
		}
		echo '</div>';
	}

	/**
	 * @param string $gpa The unsanitized GPA.
	 *
	 * @return string The sanitized GPA.
	 */
	public static function sanitize_gpa( $gpa ) {
		$dot_count = substr_count( $gpa, '.' );

		if ( 0 === $dot_count ) {
			$gpa = absint( $gpa ) . '.0';
		} elseif ( 1 === $dot_count ) {
			$gpa = explode( '.', $gpa );
			$gpa = absint( $gpa[0] ) . '.' . absint( $gpa[1] );
		} else {
			$gpa = '0.0';
		}

		return $gpa;
	}
}
