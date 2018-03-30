<?php
/**
 * Template Tags
 *
 * @package   LSX_Activities
 * @license   GPL-2.0+
 */

/**
 * Outputs the posts attached activity
 *
 * @package 	to-activities
 * @subpackage	template-tags
 * @category 	activity
 */
if ( ! function_exists( 'lsx_to_activity_posts' ) ) {
	function lsx_to_activity_posts() {
		global $lsx_to_archive;

		$args = array(
			'from'		=> 'post',
			'to'		=> 'activity',
			'column'	=> '3',
			'before'	=> '<section id="posts" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-posts">' . esc_html__( 'Featured Posts', 'lsx-activities' ) . '</h2><div id="collapse-posts" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Find the content part in the plugin
 *
 * @package 	to-activities
 * @subpackage	template-tag
 * @category 	content
 */
function lsx_to_activity_content( $slug, $name = null ) {
	do_action( 'lsx_to_activity_content', $slug, $name );
}

/**
 * Outputs the activities tours
 *
 * @package 	to-activities
 * @subpackage	template-tags
 * @category 	activity
 */
if ( ! function_exists( 'lsx_to_activity_tours' ) ) {
	function lsx_to_activity_tours() {
		global $wp_query;

		if ( post_type_exists( 'tour' ) && is_singular( 'activity' ) ) {
			$args = array(
				'from'			=> 'tour',
				'to'			=> 'activity',
				'content_part'	=> 'tour',
				'column'		=> '3',
				'before'		=> '<section id="tours" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-tours">' . __( lsx_to_get_post_type_section_title( 'tour', '', 'Featured Tours' ), 'lsx-activities' ) . '</h2><div id="collapse-tours" class="collapse in"><div class="collapse-inner">',
				'after'			=> '</div></div></section>',
			);

			lsx_to_connected_panel_query( $args );
		}
	}
}
/**
 * Outputs the activites accommodation
 *
 * @package 	to-activities
 * @subpackage	template-tags
 * @category 	activity
 */
if ( ! function_exists( 'lsx_to_activity_accommodation' ) ) {
	function lsx_to_activity_accommodation() {
		global $wp_query;

		if ( post_type_exists( 'accommodation' ) && is_singular( 'activity' ) ) {
			$args = array(
				'from'			=> 'accommodation',
				'to'			=> 'activity',
				'content_part'	=> 'accommodation',
				'column'		=> '3',
				'before'		=> '<section id="accommodation" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-accommodation">' . __( lsx_to_get_post_type_section_title( 'accommodation', '', 'Featured Accommodations' ), 'lsx-activities' ) . '</h2><div id="collapse-accommodation" class="collapse in"><div class="collapse-inner">',
				'after'			=> '</div></div></section>',
			);

			lsx_to_connected_panel_query( $args );
		}
	}
}

/**
 * Gets the current items connected activities
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	to-activities
 * @subpackage	template-tags
 * @category 	connections
 */
function lsx_to_connected_activities( $before = '', $after = '', $echo = true ) {
	lsx_to_connected_items_query( 'activity', get_post_type(), $before, $after, $echo );
}
