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
		add_action( "{$this->taxonomy_slug}_edit_form_fields", array( $this, 'term_edit_form_fields' ), 10 );
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

	/**
	 * Captures information about a faculty member as term meta.
	 *
	 * @since 0.0.1
	 *
	 * @param WP_Term $term
	 */
	public function term_edit_form_fields( $term ) {
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="degree-abbreviation">Degree abbreviation</label>
			</th>
			<td>
				<input type="text" name="degree_abbreviation" id="degree-abbreviation" value="" />
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="degree-abbreviation">Email</label>
			</th>
			<td>
				<input type="text" name="email" id="email" value="" />
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="may-chair">May chair committee</label>
			</th>
			<td>
				<select name="may_chair" id="may-chair">
					<option value="yes">Yes</option>
					<option value="no">No</option>
				</select>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="may-cochair">May cochair committee</label>
			</th>
			<td>
				<select name="may_cochair" id="may-cochair">
					<option value="yes">Yes</option>
					<option value="no">No</option>
				</select>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="teaching-interests">Teaching interests</label>
			</th>
			<td>
				<textarea name="teaching_interests" id="teaching-interests" rows="5" cols="50" class="large-text"></textarea>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="research-interests">Research interests</label>
			</th>
			<td>
				<textarea name="research_interests" id="research-interests" rows="5" cols="50" class="large-text"></textarea>
			</td>
		</tr>
		<?php
	}
}
