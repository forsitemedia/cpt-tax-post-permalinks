<?php
/*
Plugin Name: ForSite Rewrite
Version: 0.1
Author: ForSite Media
Author URI: http://forsitemedia.net/
*/


class FS_CPT_Tax_Post_Permalinks {

	/**
	 * Default custom post type arguments
	 *
	 * @var array $cpt_args
	 */
	public $cpt_args = array(
		'post_type'           => '',
		'label'               => '',
		'description'         => '',
		'labels'              => array(),
		'supports'            => array(),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'rewrite'             => array(),
	);

	/**
	 * Default taxonomy arguments
	 *
	 * @var array $tax_args
	 */
	public $tax_args = array(
		'taxonomy'        => '',
		'post_type'       => '',
		'label'           => '',
		'singular_label'  => '',
		'hierarchical'    => true,
		'query_var'       => true,
		'rewrite'         => array()
	);

	/**
	 * Default permalink arguments
	 *
	 * @var array $permalink_args
	 */
	public $permalink_args = array(
		'taxonomy'        => ''
	);

	/**
	 * Hooks into init with cpt function, merges default arguments
	 *
	 * @param array $cpt_args Custom post type arguments
	 */
	public function add_cpt( $cpt_args ) {
		$this->cpt_args = array_merge( $this->cpt_args, $cpt_args );
		add_action( 'init', array( $this, 'cpt' ) );
	}

	/**
	 * Hooks into init with tax function, merges default arguments
	 *
	 * @param array $tax_args Taxonomy arguments
	 */
	public function add_tax( $tax_args ) {
		$this->tax_args = array_merge( $this->tax_args, $tax_args );
		add_action( 'init', array( $this, 'tax' ) );
	}

	/**
	 * Filters post_type_link with add_permalink function, merges default arguments
	 *
	 * @param array $tax_args Permalink arguments
	 */
	public function add_permalink( $permalink_args ) {
		$this->permalink_args = array_merge( $this->permalink_args, $permalink_args );
		add_filter( 'post_type_link', array( $this, 'permalink_structure' ), 10, 4 );
	}

	/**
	 * Registers custom post type
	 *
	 * @return void
	 */
	public function cpt() {

		$args = array(
			'label'               => $this->cpt_args['label'],
			'description'         => $this->cpt_args['description'],
			'labels'              => $this->cpt_args['labels'],
			'supports'            => $this->cpt_args['supports'],
			'taxonomies'          => $this->cpt_args['taxonomies'],
			'hierarchical'        => $this->cpt_args['hierarchical'],
			'public'              => $this->cpt_args['public'],
			'show_ui'             => $this->cpt_args['show_ui'],
			'show_in_menu'        => $this->cpt_args['show_in_menu'],
			'show_in_nav_menus'   => $this->cpt_args['show_in_nav_menus'],
			'show_in_admin_bar'   => $this->cpt_args['show_in_admin_bar'],
			'menu_position'       => $this->cpt_args['menu_position'],
			'menu_icon'           => $this->cpt_args['menu_icon'],
			'can_export'          => $this->cpt_args['can_export'],
			'has_archive'         => $this->cpt_args['has_archive'],
			'exclude_from_search' => $this->cpt_args['exclude_from_search'],
			'publicly_queryable'  => $this->cpt_args['publicly_queryable'],
			'capability_type'     => $this->cpt_args['capability_type'],
			'rewrite'             => $this->cpt_args['rewrite'],
		);

		register_post_type( $this->cpt_args['post_type'] , $args );
	}

	/**
	 * Registers custom taxonomy
	 *
	 * @return void
	 */
	public function tax() {

		register_taxonomy(
			$this->tax_args['taxonomy'],
			$this->tax_args['post_type'],
			array(
				'label'           => $this->tax_args['label'],
				'singular_label'  => $this->tax_args['singular_label'],
				'hierarchical'    => $this->tax_args['hierarchical'],
				'query_var'       => $this->tax_args['query_var'],
				'rewrite'         => $this->tax_args['rewrite'],
			)
		);
	}

	/**
	 * Returns custom post link
	 * @param  string $post_link  Default post link
	 * @param  object $post       Post object
	 * @param  bool   $leavename  Optional, defaults to false. Whether to keep post name.
	 * @param  bool   $sample     Optional, defaults to false. Is it a sample permalink.
	 * @return string $post_link  Post link
	 */
	public function permalink_structure( $post_link, $post, $leavename, $sample ) {

		if ( false !== strpos( $post_link, '%' . $this->permalink_args['taxonomy'] . '%' ) ) {
			if ( $type_term = get_the_terms( $post->ID, $this->permalink_args['taxonomy'] ) )
				$post_link = str_replace( '%' . $this->permalink_args['taxonomy'] . '%', array_pop( $type_term )->slug, $post_link );
		}
		return $post_link;
	}

}
