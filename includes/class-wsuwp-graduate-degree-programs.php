<?php

class WSUWP_Graduate_Degree_Programs {
	/**
	 * @since 0.0.1
	 *
	 * @var WSUWP_Graduate_Degree_Programs
	 */
	private static $instance;

	/*
	 * Track a version number for the scripts registered in
	 * this object to enable cache busting.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	var $script_version = '0001';

	/**
	 * The slug used to register the factsheet post type.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	var $post_type_slug = 'gs-factsheet';

	/**
	 * A list of post meta keys associated with factsheets.
	 *
	 * @since 0.0.1
	 *
	 * @var array
	 */
	var $post_meta_keys = array(
		'gsdp_degree_description' => array(
			'description' => 'Description of the graduate degree',
			'type' => 'textarea',
			'sanitize_callback' => 'wp_kses_post',
		),
		'gsdp_degree_id' => array(
			'description' => 'Factsheet degree ID',
			'type' => 'int',
			'sanitize_callback' => 'absint',
		),
		'gsdp_accepting_applications' => array(
			'description' => 'Accepting applications',
			'type' => 'bool',
			'sanitize_callback' => 'absint',
		),
		'gsdp_include_in_programs' => array(
			'description' => 'Include in programs list',
			'type' => 'bool',
			'sanitize_callback' => 'absint',
		),
		'gsdp_grad_students_total' => array(
			'description' => 'Total number of grad students',
			'type' => 'int',
			'sanitize_callback' => 'absint',
		),
		'gsdp_grad_students_aided' => array(
			'description' => 'Number of aided grad students',
			'type' => 'int',
			'sanitize_callback' => 'absint',
		),
		'gsdp_admission_gpa' => array(
			'description' => 'Admission GPA',
			'type' => 'float',
			'sanitize_callback' => 'WSUWP_Graduate_Degree_Programs::sanitize_gpa',
		),
		'gsdp_degree_url' => array(
			'description' => 'Degree home page',
			'type' => 'string',
			'sanitize_callback' => 'esc_url_raw',
		),
		'gsdp_plan_name' => array(
			'description' => 'Plan name',
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'gsdp_admission_requirements' => array(
			'description' => 'Admission requirements',
			'type' => 'textarea',
			'sanitize_callback' => 'wp_kses_post',
		),
		'gsdp_student_opportunities' => array(
			'description' => 'Student opportunities',
			'type' => 'textarea',
			'sanitize_callback' => 'wp_kses_post',
		),
		'gsdp_career_opportunities' => array(
			'description' => 'Career opportunities',
			'type' => 'textarea',
			'sanitize_callback' => 'wp_kses_post',
		),
		'gsdp_career_placements' => array(
			'description' => 'Career placements',
			'type' => 'textarea',
			'sanitize_callback' => 'wp_kses_post',
		),
		'gsdp_student_learning_outcome' => array(
			'description' => 'Student learning outcome',
			'type' => 'textarea',
			'sanitize_callback' => 'wp_kses_post',
		),
	);

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
		require_once( dirname( __FILE__ ) . '/class-graduate-degree-faculty-taxonomy.php' );
		require_once( dirname( __FILE__ ) . '/class-graduate-degree-program-name-taxonomy.php' );
		require_once( dirname( __FILE__ ) . '/class-graduate-degree-degree-type-taxonomy.php' );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'init', array( $this, 'register_post_type' ), 15 );
		add_action( 'init', 'WSUWP_Graduate_Degree_Faculty_Taxonomy', 15 );
		add_action( 'init', 'WSUWP_Graduate_Degree_Program_Name_Taxonomy', 15 );
		add_action( 'init', 'WSUWP_Graduate_Degree_Degree_Type_Taxonomy', 15 );

		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( "save_post_{$this->post_type_slug}", array( $this, 'save_factsheet' ), 10, 2 );
	}

	/**
	 * Enqueue scripts and styles used in the admin.
	 *
	 * @since 0.0.1
	 *
	 * @param string $hook_suffix
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		if ( 'post.php' === $hook_suffix && 'gs-factsheet' === get_current_screen()->id ) {
			wp_enqueue_style( 'gsdp-admin', plugins_url( '/css/admin.css', dirname( __FILE__ ) ), array(), $this->script_version );
		}
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
			'supports' => array(
				'title',
			),
			'has_archive' => 'degrees',
			'rewrite' => array( 'slug' => 'degrees/factsheet', 'with_front' => false ),
		);
		register_post_type( $this->post_type_slug, $args );
		register_taxonomy_for_object_type( 'wsuwp_university_location', $this->post_type_slug );
	}

	/**
	 * Register the meta keys used to store degree factsheet data.
	 *
	 * @since 0.0.1
	 */
	public function register_meta() {
		foreach ( $this->post_meta_keys as $key => $args ) {
			if ( 'float' === $args['type'] ) {
				$args['type'] = 'string';
			}

			$args['show_in_rest'] = true;
			$args['single'] = true;
			register_meta( 'post', $key, $args );
		}
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
	 * @since 0.0.1
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function display_factsheet_primary_meta_box( $post ) {
		$data = get_registered_metadata( 'post', $post->ID );

		$wp_editor_settings = array(
			'textarea_rows' => 10,
			'media_buttons' => false,
			'teeny' => true,
		);

		wp_nonce_field( 'save-gsdp-primary', '_gsdp_primary_nonce' );

		echo '<div class="factsheet-primary-inputs">';
		foreach ( $this->post_meta_keys as $key => $meta ) {
			if ( ! isset( $data[ $key ] ) || ! isset( $data[ $key ][0] ) ) {
				$data[ $key ] = array( false );
			}
			?>
			<div class="factsheet-primary-input factsheet-<?php echo esc_attr( $meta['type'] ); ?>"">
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $meta['description'] ); ?>:</label>
			<?php
			if ( 'int' === $meta['type'] ) {
				?><input type="text" name="<?php echo esc_attr( $key ); ?>" value="<?php echo absint( $data[ $key ][0] ); ?>" /><?php
			} elseif ( 'string' === $meta['type'] || 'float' === $meta['type'] ) {
				?><input type="text" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $data[ $key ][0] ); ?>" /><?php
			} elseif ( 'textarea' === $meta['type'] ) {
				wp_editor( $data[ $key ][0], esc_attr( $key ), $wp_editor_settings );
			} elseif ( 'bool' === $meta['type'] ) {
				?><select name="<?php echo esc_attr( $key ); ?>">
					<option value="0" <?php selected( 0, absint( $data[ $key ][0] ) ); ?>>No</option>
					<option value="1" <?php selected( 1, absint( $data[ $key ][0] ) ); ?>>Yes</option>
				</select>
				<?php
			}
			?>
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

	/**
	 * Save additional data associated with a factsheet.
	 *
	 * @since 0.0.1
	 *
	 * @param int     $post_id
	 * @param WP_Post $post
	 */
	public function save_factsheet( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		// Do not overwrite existing information during an import.
		if ( defined( 'WP_IMPORTING' ) && WP_IMPORTING ) {
			return;
		}

		if ( ! isset( $_POST['_gsdp_primary_nonce'] ) || ! wp_verify_nonce( $_POST['_gsdp_primary_nonce'], 'save-gsdp-primary' ) ) {
			return;
		}

		$keys = get_registered_meta_keys( 'post' );

		foreach ( $this->post_meta_keys as $key => $meta ) {
			if ( isset( $_POST[ $key ] ) && isset( $keys[ $key ] ) && isset( $keys[ $key ]['sanitize_callback'] ) ) {
				// Each piece of meta is registered with sanitization.
				update_post_meta( $post_id, $key, $_POST[ $key ] );
			}
		}
	}
}
