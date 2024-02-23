<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RT_Posts' ) ) {

	class RT_Posts {

		protected static $instance = null;
		private $post_types        = [];
		private $taxonomies        = [];

		private function __construct() {
			add_action( 'init', [ $this, 'initialize' ] );
		}

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			$this->register_taxonomies();
			$this->register_custom_post_types();
		}

		public function add_post_types( $post_types ) {

			foreach ( $post_types as $post_type ) {

				$labels = [
					'name'               => _x( $post_type['plural'], 'post type general name', 'rt-framework' ),
					'singular_name'      => _x( $post_type['singular'], 'post type singular name', 'rt-framework' ),
					'menu_name'          => _x( $post_type['plural'], 'admin menu', 'rt-framework' ),
					'name_admin_bar'     => _x( $post_type['singular'], 'add new on admin bar', 'rt-framework' ),
					'add_new'            => _x( 'Add New ' . $post_type['singular'], 'rt-framework' ),
					'add_new_item'       => __( 'Add New ' . $post_type['singular'], 'rt-framework' ),
					'new_item'           => __( 'New ' . $post_type['singular'], 'rt-framework' ),
					'edit_item'          => __( 'Edit ' . $post_type['singular'], 'rt-framework' ),
					'view_item'          => __( 'View ' . $post_type['singular'], 'rt-framework' ),
					'view_items'         => __( 'View ' . $post_type['plural'], 'rt-framework' ),
					'all_items'          => __( 'All ' . $post_type['plural'], 'rt-framework' ),
					'search_items'       => __( 'Search' . $post_type['plural'], 'rt-framework' ),
					'parent_item_colon'  => __( 'Parent ' . $post_type['plural'], 'rt-framework' ),
					'not_found'          => __( 'No ' . $post_type['plural'] . ' found.', 'rt-framework' ),
					'not_found_in_trash' => __( 'No ' . $post_type['plural'] . ' found in Trash.', 'rt-framework' ),
				];
				$args   = [
					'labels'             => $labels,
					'description'        => __( $post_type['description'], 'rt-framework' ),
					'public'             => $post_type['public'] ?? true,
					'publicly_queryable' => $post_type['publicly_queryable'] ?? true,
					'show_ui'            => $post_type['show_ui'] ?? true,
					'show_in_menu'       => $post_type['show_in_menu'] ?? true,
					'menu_icon'          => $post_type['menu_icon'],
					'query_var'          => $post_type['query_var'] ?? true,
					'rewrite'            => [ 'slug' => $post_type['slug'] ],
					'capability_type'    => $post_type['capability_type'] ?? 'post',
					'has_archive'        => $post_type['has_archive'] ?? true,
					'hierarchical'       => $post_type['hierarchical'] ?? false,
					'menu_position'      => $post_type['menu_position'],
					'supports'           => $post_type['supports'],
					'show_in_rest'       => $post_type['show_in_rest'] ?? true,
				];

				$this->post_types[ $post_type['id'] ] = $args;
			}
		}

		public function add_taxonomies( $taxonomies ) {

			foreach ( $taxonomies as $taxonomy ) {

				$labels                              = [
					'name'              => _x( $taxonomy['plural'], 'taxonomy general name', 'rt-framework' ),
					'singular_name'     => _x( $taxonomy['singular'], 'taxonomy singular name', 'rt-framework' ),
					'menu_name'         => _x( $taxonomy['plural'], 'admin menu', 'rt-framework' ),
					'add_new_item'      => _x( 'Add New ' . $taxonomy['singular'], 'rt-framework' ),
					'search_items'      => __( 'Search ' . $taxonomy['plural'], 'rt-framework' ),
					'all_items'         => __( 'All ' . $taxonomy['plural'], 'rt-framework' ),
					'parent_item'       => __( 'Parent ' . $taxonomy['singular'], 'rt-framework' ),
					'parent_item_colon' => __( 'Parent ' . $taxonomy['singular'], 'rt-framework' ),
					'edit_item'         => __( 'Edit ' . $taxonomy['singular'], 'rt-framework' ),
					'update_item'       => __( 'Update ' . $taxonomy['singular'], 'rt-framework' ),
					'new_item_name'     => __( 'New ' . $taxonomy['singular'], 'rt-framework' ),
				];
				$args                                = [
					'labels'             => $labels,
					'description'        => __( '', 'rt-framework' ),
					'hierarchical'       => $taxonomy['hierarchical'] ?? true,
					'public'             => $taxonomy['public'] ?? true,
					'publicly_queryable' => $taxonomy['publicly_queryable'] ?? true,
					'show_ui'            => $taxonomy['show_ui'] ?? true,
					'show_in_menu'       => $taxonomy['show_in_menu'] ?? true,
					'show_in_nav_menus'  => $taxonomy['show_in_nav_menus'] ?? true,
					'show_tagcloud'      => $taxonomy['show_tagcloud'] ?? true,
					'show_in_quick_edit' => $taxonomy['show_in_quick_edit'] ?? true,
					'show_admin_column'  => $taxonomy['show_admin_column'] ?? false,
					'show_in_rest'       => $taxonomy['show_in_rest'] ?? true,
					'post_type'          => $taxonomy['post_type'],
				];
				$this->taxonomies[ $taxonomy['id'] ] = $args;
			}
		}

		private function register_custom_post_types() {
			$post_types = apply_filters( 'rt_framework_post_types', $this->post_types );
			foreach ( $post_types as $post_type => $args ) {
				register_post_type( $post_type, $args );
			}
		}

		private function register_taxonomies() {
			$taxonomies = apply_filters( 'rt_framework_taxonomies', $this->taxonomies );
			foreach ( $taxonomies as $taxonomy => $args ) {
				$post_type = $args['post_type'];
				unset( $args['post_type'] );
				register_taxonomy( $taxonomy, $post_type, $args );
			}
		}
	}
}

RT_Posts::getInstance();
