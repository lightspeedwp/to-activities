<?php
/**
 * Tour Operator - Activity Metabox config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

$metabox = array(
	'title'  => esc_html__( 'Tour Operator Plugin', 'lsx-activities' ),
	'pages'  => 'activity',
	'fields' => array(),
);

$metabox['fields'][] = array(
	'id'   => 'featured',
	'name' => esc_html__( 'Featured', 'lsx-activities' ),
	'type' => 'checkbox',
);

$metabox['fields'][] = array(
	'id'   => 'disable_single',
	'name' => esc_html__( 'Disable Single', 'to-reviews' ),
	'type' => 'checkbox',
);

if ( ! class_exists( 'LSX_Banners' ) ) {
	$metabox['fields'][] = array(
		'id'   => 'tagline',
		'name' => esc_html__( 'Tagline', 'lsx-activities' ),
		'type' => 'text',
	);
}

$metabox['fields'][] = array(
	'id' => 'friendly',
	'name' => 'Friendly',
	'type' => 'select',
	'multiple' => true,
	'options' => array(
		'business'		=> esc_html__( 'Business', 'lsx-activities' ),
		'children'		=> esc_html__( 'Children', 'lsx-activities' ),
		'disability'	=> esc_html__( 'Disability', 'lsx-activities' ),
		'leisure'		=> esc_html__( 'Leisure', 'lsx-activities' ),
		'luxury'		=> esc_html__( 'Luxury', 'lsx-activities' ),
		'pet'			=> esc_html__( 'Pet', 'lsx-activities' ),
		'romance'		=> esc_html__( 'Romance', 'lsx-activities' ),
		'vegetarian'	=> esc_html__( 'Vegetarian', 'lsx-activities' ),
		'weddings'		=> esc_html__( 'Weddings', 'lsx-activities' ),
	),
);

if ( class_exists( 'LSX_TO_Team' ) ) {
	$metabox['fields'][] = array(
		'id'         => 'team_to_activity',
		'name'       => esc_html__( 'Team Member', 'lsx-activities' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => 'team',
			'nopagin'        => true,
			'posts_per_page' => 1000,
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);
}

$metabox['fields'][] = array(
	'id' 	=> 'price_title',
	'name' 	=> __( 'Price','lsx-activities' ),
	'type' 	=> 'title',
);

$metabox['fields'][] = array(
	'id' 	=> 'price',
	'name' 	=> __( 'Price','lsx-activities' ),
	'type' 	=> 'text',
	'cols'       => 6,
);

$metabox['fields'][] = array(
	'id'		=> 'price_type',
	'name'		=> __( 'Price Type','lsx-activities' ),
	'type'		=> 'select',
	'cols'       => 6,
	'options'	=> array(
		'none' 			=> 'Select a type',
		'per_person'	=> __( 'Per Person','lsx-activities' ),
		'per_group'		=> __( 'Per Group','lsx-activities' ),
	),
);

if ( class_exists( 'LSX_TO_Maps' ) ) {
	$tour_operator = tour_operator();
	$api_key = false;

	if ( isset( $tour_operator->options['api']['googlemaps_key'] ) ) {
		$api_key = $tour_operator->options['api']['googlemaps_key'];
	}

	$metabox['fields'][] = array(
		'id'   => 'location_title',
		'name' => esc_html__( 'Location', 'lsx-activities' ),
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'             => 'location',
		'name'           => esc_html__( 'Location', 'lsx-activities' ),
		'type'           => 'gmap',
		'google_api_key' => $api_key,
	);
}

$metabox['fields'][] = array(
	'id'   => 'gallery_title',
	'name' => esc_html__( 'Gallery', 'lsx-activities' ),
	'type' => 'title',
);

$metabox['fields'][] = array(
	'id'         => 'gallery',
	'name'       => esc_html__( 'Gallery', 'lsx-activities' ),
	'type'       => 'image',
	'repeatable' => true,
	'show_size'  => false,
);

if ( class_exists( 'Envira_Gallery' ) ) {
	$metabox['fields'][] = array(
		'id'   => 'envira_title',
		'name' => esc_html__( 'Envira Gallery', 'lsx-activities' ),
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'         => 'envira_gallery',
		'name'       => esc_html__( 'Envira Gallery', 'lsx-activities' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => 'envira',
			'nopagin'        => true,
			'posts_per_page' => '-1',
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);

	if ( class_exists( 'Envira_Videos' ) ) {
		$metabox['fields'][] = array(
			'id'         => 'envira_video',
			'name'       => esc_html__( 'Envira Video Gallery', 'lsx-activities' ),
			'type'       => 'post_select',
			'use_ajax'   => false,
			'allow_none' => true,
			'query'      => array(
				'post_type'      => 'envira',
				'nopagin'        => true,
				'posts_per_page' => '-1',
				'orderby'        => 'title',
				'order'          => 'ASC',
			),
		);
	}
}

$post_types = array(
	'post'          => esc_html__( 'Posts', 'lsx-activities' ),
	'accommodation' => esc_html__( 'Accommodation', 'lsx-activities' ),
	'destination'   => esc_html__( 'Destinations', 'lsx-activities' ),
	'tour'          => esc_html__( 'Tours', 'lsx-activities' ),
);

foreach ( $post_types as $slug => $label ) {
	$metabox['fields'][] = array(
		'id'   => $slug . '_title',
		'name' => $label,
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'         => $slug . '_to_activity',
		'name'       => $label . esc_html__( ' related with this activity', 'lsx-activities' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'repeatable' => true,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => $slug,
			'nopagin'        => true,
			'posts_per_page' => '-1',
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);
}

$metabox['fields'] = apply_filters( 'lsx_to_activity_custom_fields', $metabox['fields'] );

return $metabox;
