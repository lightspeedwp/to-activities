<?php
/**
 * Tour Operator - Activity Post Type config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

$post_type = array(
	'class'               => 'LSX_Activities',
	'menu_icon'           => 'dashicons-universal-access',
	'labels'              => array(
		'name'               => esc_html__( 'Activities', 'lsx-activities' ),
		'singular_name'      => esc_html__( 'Activity', 'lsx-activities' ),
		'add_new'            => esc_html__( 'Add New', 'lsx-activities' ),
		'add_new_item'       => esc_html__( 'Add New Activity', 'lsx-activities' ),
		'edit_item'          => esc_html__( 'Edit Activity', 'lsx-activities' ),
		'new_item'           => esc_html__( 'New Activities', 'lsx-activities' ),
		'all_items'          => esc_html__( 'Activities', 'lsx-activities' ),
		'view_item'          => esc_html__( 'View Activity', 'lsx-activities' ),
		'search_items'       => esc_html__( 'Search Activities', 'lsx-activities' ),
		'not_found'          => esc_html__( 'No activities found', 'lsx-activities' ),
		'not_found_in_trash' => esc_html__( 'No activities found in Trash', 'lsx-activities' ),
		'parent_item_colon'  => '',
		'menu_name'          => esc_html__( 'Activities', 'lsx-activities' ),
	),
	'public'              => true,
	'publicly_queryable'  => true,
	'show_ui'             => true,
	'show_in_menu'        => 'tour-operator',
	'menu_position'       => 50,
	'query_var'           => true,
	'rewrite'             => array(
		'slug'       => 'activity',
		'with_front' => false,
	),
	'exclude_from_search' => false,
	'capability_type'     => 'post',
	'has_archive'         => 'activities',
	'hierarchical'        => false,
	'show_in_rest'        => true,
	'supports'            => array(
		'title',
		'slug',
		'editor',
		'thumbnail',
		'excerpt',
		'custom-fields',
	),
);

return $post_type;
