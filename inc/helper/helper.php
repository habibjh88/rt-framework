<?php

use RT\NewsFitCore\Helper\Fns;

function get_builder_type() {

	$allPost = [
		'post' => __( 'Post', 'newsfit-core' ),
		'page' => __( 'Post', 'newsfit-core' ),
	];
	if ( defined( 'NEWSFIT_CORE' ) ) {
		$allPost = Fns::get_post_types();
	}

	$default_pages = [
		'is_front_page' => __( 'Front Page', 'newsfit-core' ),
		'is_home'       => __( 'Blog / Posts Page', 'newsfit-core' ),
		'is_search'     => __( 'Search Page', 'newsfit-core' ),
		'is_archive'    => __( 'Archive', 'newsfit-core' ),
		'is_404'        => __( '404 Page', 'newsfit-core' ),
	];

	if ( class_exists( 'WooCommerce' ) ) {
		$default_pages['is_shop'] = __( 'WooCommerce Shop Page', 'newsfit-core' );
	}

	$selection_options = [
		'sitewide' => [
			'label' => __( 'Sitewide', 'newsfit-core' ),
			'value' => [
				'sitewide-global'    => __( 'Entire Website', 'newsfit-core' ),
				'sitewide-singulars' => __( 'All Singulars', 'newsfit-core' ),
				'sitewide-archives'  => __( 'All Archives', 'newsfit-core' ),
			],
		],

		'default-pages' => [
			'label' => __( 'Default Pages', 'newsfit-core' ),
			'value' => $default_pages,
		],
	];


	foreach ( $allPost as $post_type => $post_type_name ) {
		$pTypeVal = [];
		if ( $post_type == 'page' ) {
			$pTypeVal['single|page'] = __( "All Pages", "newsfit-core" );
		} else {
			$pTypeVal = [
				"single|$post_type"  => sprintf( __( 'All %s Single', 'newsfit-core' ), $post_type_name ),
				"archive|$post_type" => sprintf( __( 'All %s Archive', 'newsfit-core' ), $post_type_name ),
			];
		}

		$taxonomies = get_taxonomies( [
			'object_type' => [ $post_type ]
		], 'object' );

		if ( $taxonomies ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( in_array( $taxonomy->name, [ 'post_format' ] ) ) {
					continue;
				}
				$pTypeVal[ 'taxonomy|' . $taxonomy->name ] = $taxonomy->label;
			}
		}

		$selection_options[ $post_type ] = [
			'label' => $post_type_name,
			'value' => $pTypeVal,
		];

	}

	//Custom Page / Post
	$selection_options['custom'] = [
		'label' => __( 'Custom Page / Post', 'newsfit-core' ),
		'value' => [
			'custom' => __( 'Choose custom page / post', 'newsfit-core' )
		]
	];

	return apply_filters( 'newsfit_builder_type', $selection_options );

}