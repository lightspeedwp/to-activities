<?php
/**
 * LSX_Activities_Frontend
 *
 * @package   LSX_Activities_Frontend
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_Activities_Frontend
 * @author  LightSpeed
 */
class LSX_Activities_Frontend extends LSX_Activities {

	/**
	 * Holds the $page_links array while its being built on the single activity page.
	 *
	 * @var array
	 */
	public $page_links = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();

		add_filter( 'lsx_to_entry_class', array( $this, 'entry_class' ) );
		add_action( 'init',array( $this, 'init' ) );

		if ( ! class_exists( 'LSX_TO_Template_Redirects' ) ) {
			require_once( LSX_ACTIVITIES_PATH . 'classes/class-template-redirects.php' );
		}

		$this->redirects = new LSX_TO_Template_Redirects( LSX_ACTIVITIES_PATH, array_keys( $this->post_types ) );

		add_action( 'lsx_to_activity_content', array( $this->redirects, 'content_part' ), 10 , 2 );

		add_filter( 'lsx_to_page_navigation', array( $this, 'page_links' ) );

		add_action( 'lsx_entry_top',      array( $this, 'archive_entry_top' ), 15 );
		add_action( 'lsx_entry_bottom',   array( $this, 'archive_entry_bottom' ) );
		add_action( 'lsx_content_bottom', array( $this, 'single_content_bottom' ) );
		add_action( 'lsx_to_fast_facts', array( $this, 'single_fast_facts' ) );
	}

	/**
	 * Runs on init after all files have been parsed.
	 */
	public function init() {
		if ( ! class_exists( 'LSX_Currencies' ) ) {
			add_filter( 'lsx_to_custom_field_query',array( $this, 'price_filter' ),5,10 );
		}
	}

	/**
	 * A filter to set the content area to a small column on single
	 */
	public function entry_class( $classes ) {
		global $lsx_to_archive;

		if ( 1 !== $lsx_to_archive ) {
			$lsx_to_archive = false;
		}

		if ( is_main_query() && is_singular( 'activity' ) && false === $lsx_to_archive ) {
			$classes[] = 'col-xs-12 col-sm-12 col-md-6';
		}

		return $classes;
	}

	/**
	 * Adds in additional info for the price custom field
	 */
	public function price_filter( $html = '', $meta_key = false, $value = false, $before = '', $after = '' ) {
		if ( get_post_type() === 'activity' && 'price' === $meta_key ) {
			$price_type = get_post_meta( get_the_ID(),'price_type',true );
			$value = preg_replace( '/[^0-9,.]/', '', $value );
			$value = ltrim( $value, '.' );
			$value = str_replace( ',','',$value );
			$value = number_format( (int) $value,2 );
			$tour_operator = tour_operator();
			$currency = '';

			if ( is_object( $tour_operator ) && isset( $tour_operator->options['general'] ) && is_array( $tour_operator->options['general'] ) ) {
				if ( isset( $tour_operator->options['general']['currency'] ) && ! empty( $tour_operator->options['general']['currency'] ) ) {
					$currency = $tour_operator->options['general']['currency'];
					$currency = '<span class="currency-icon ' . mb_strtolower( $currency ) . '">' . $currency . '</span>';
				}
			}

			switch ( $price_type ) {
				case 'per_person':
				case 'per_person_per_night':
				case 'per_person_sharing':
				case 'per_person_sharing_per_night':
					$value = $currency . $value . ' ' . ucwords( str_replace( '_',' ',$price_type ) ) . '';
					$value = str_replace( 'Per Person', 'P/P', $value );
				break;

				case 'total_percentage':
					$value .= '% ' . __( 'Off','to-specials' ) . '';
					$before = str_replace( 'from price', '', $before );
				break;

				case 'none':
				default:
					$value = $currency . $value;
				break;
			}

			$html = $before . $value . $after;
		}

		return $html;
	}

	/**
	 * Adds our navigation links to the activity single post
	 *
	 * @param $page_links array
	 * @return $page_links array
	 */
	public function page_links( $page_links ) {
		if ( is_singular( 'activity' ) ) {
			$this->page_links = $page_links;

			$this->get_map_link();
			$this->get_related_tours_link();
			$this->get_related_accommodation_link();
			$this->get_gallery_link();
			$this->get_videos_link();
			$this->get_related_posts_link();

			$page_links = $this->page_links;
		}

		return $page_links;
	}

	/**
	 * Tests for the Related Tours and returns a link for the section
	 */
	public function get_related_tours_link() {
		$connected_tours = get_post_meta( get_the_ID(), 'tour_to_activity', false );

		if ( post_type_exists( 'tour' ) && is_array( $connected_tours ) && ! empty( $connected_tours ) ) {
			$connected_tours = new \WP_Query( array(
				'post_type' => 'tour',
				'post__in' => $connected_tours,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_tours = $connected_tours->posts;

			if ( is_array( $connected_tours ) && ! empty( $connected_tours ) ) {
				$this->page_links['tours'] = esc_html__( 'Tours', 'lsx-activities' );
			}
		}
	}

	/**
	 * Tests for the Related Accommodation and returns a link for the section
	 */
	public function get_related_accommodation_link() {
		$connected_accommodation = get_post_meta( get_the_ID(), 'accommodation_to_activity', false );

		if ( post_type_exists( 'accommodation' ) && is_array( $connected_accommodation ) && ! empty( $connected_accommodation ) ) {
			$connected_accommodation = new \WP_Query( array(
				'post_type' => 'accommodation',
				'post__in' => $connected_accommodation,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_accommodation = $connected_accommodation->posts;

			if ( is_array( $connected_accommodation ) && ! empty( $connected_accommodation ) ) {
				$this->page_links['accommodation'] = esc_html__( 'Accommodation', 'lsx-activities' );
			}
		}
	}

	/**
	 * Tests for the Google Map and returns a link for the section
	 */
	public function get_map_link() {
		if ( function_exists( 'lsx_to_has_map' ) && lsx_to_has_map() ) {
			$this->page_links['activity-map'] = esc_html__( 'Map', 'lsx-activities' );
		}
	}

	/**
	 * Tests for the Gallery and returns a link for the section
	 */
	public function get_gallery_link() {
		$gallery_ids = get_post_meta( get_the_ID(), 'gallery', false );
		$envira_gallery = get_post_meta( get_the_ID(), 'envira_gallery', true );

		if ( ( ! empty( $gallery_ids ) && is_array( $gallery_ids ) ) || ( function_exists( 'envira_gallery' ) && ! empty( $envira_gallery ) && false === lsx_to_enable_envira_banner() ) ) {
			if ( function_exists( 'envira_gallery' ) && ! empty( $envira_gallery ) && false === lsx_to_enable_envira_banner() ) {
				// Envira Gallery
				$this->page_links['gallery'] = esc_html__( 'Gallery', 'lsx-activities' );
				return;
			} else {
				if ( function_exists( 'envira_dynamic' ) ) {
					// Envira Gallery - Dynamic
					$this->page_links['gallery'] = esc_html__( 'Gallery', 'lsx-activities' );
					return;
				} else {
					// WordPress Gallery
					$this->page_links['gallery'] = esc_html__( 'Gallery', 'lsx-activities' );
					return;
				}
			}
		}
	}

	/**
	 * Tests for the Videos and returns a link for the section
	 */
	public function get_videos_link() {
		$videos_id = false;

		if ( class_exists( 'Envira_Videos' ) ) {
			$videos_id = get_post_meta( get_the_ID(), 'envira_video', true );
		}

		if ( empty( $videos_id ) && function_exists( 'lsx_to_videos' ) ) {
			$videos_id = get_post_meta( get_the_ID(), 'videos', true );
		}

		if ( ! empty( $videos_id ) ) {
			$this->page_links['videos'] = esc_html__( 'Videos', 'lsx-activities' );
		}
	}

	/**
	 * Tests for the Related Posts and returns a link for the section
	 */
	public function get_related_posts_link() {
		$connected_posts = get_post_meta( get_the_ID(), 'post_to_activity', false );

		if ( is_array( $connected_posts ) && ! empty( $connected_posts ) ) {
			$connected_posts = new \WP_Query( array(
				'post_type' => 'post',
				'post__in' => $connected_posts,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_posts = $connected_posts->posts;

			if ( is_array( $connected_posts ) && ! empty( $connected_posts ) ) {
				$this->page_links['posts'] = esc_html__( 'Posts', 'lsx-activities' );
			}
		}
	}

	/**
	 * Adds the template tags to the top of the archive activity
	 */
	public function archive_entry_top() {
		global $lsx_to_archive;

		if ( 'activity' === get_post_type() && ( is_archive() || $lsx_to_archive ) ) {
			if ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_metadata'] ) ) { ?>
				<div class="lsx-to-archive-meta-data lsx-to-archive-meta-data-grid-mode">
					<?php
						$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

						lsx_to_price( '<span class="' . $meta_class . 'price"><span class="lsx-to-meta-data-key">' . esc_html__( 'From price', 'lsx-activities' ) . ':</span> ', '</span>' );
						lsx_to_accommodation_activity_friendly( '<span class="' . $meta_class . 'friendly"><span class="lsx-to-meta-data-key">' . __( 'Friendly', 'lsx-activities' ) . ':</span> ', '</span>' );
						lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . __( 'Location', 'lsx-activities' ) . ':</span> ','</span>' );
						lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . __( 'Accommodation', 'lsx-activities' ) . ':</span> ', '</span>' );
						lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . __( 'Tours', 'lsx-activities' ) . ':</span> ', '</span>' );
					?>
				</div>
			<?php }
		}
	}

	/**
	 * Adds the template tags to the bottom of the archive activity
	 */
	public function archive_entry_bottom() {
		global $lsx_to_archive;

		if ( 'activity' === get_post_type() && ( is_archive() || $lsx_to_archive ) ) { ?>
				</div>

				<?php if ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_metadata'] ) ) { ?>
					<div class="lsx-to-archive-meta-data lsx-to-archive-meta-data-list-mode">
						<?php
							$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

							lsx_to_price( '<span class="' . $meta_class . 'price"><span class="lsx-to-meta-data-key">' . esc_html__( 'From price', 'lsx-activities' ) . ':</span> ', '</span>' );
							lsx_to_accommodation_activity_friendly( '<span class="' . $meta_class . 'friendly"><span class="lsx-to-meta-data-key">' . __( 'Friendly', 'lsx-activities' ) . ':</span> ', '</span>' );
							lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . __( 'Location', 'lsx-activities' ) . ':</span> ','</span>' );
							lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . __( 'Accommodation', 'lsx-activities' ) . ':</span> ', '</span>' );
							lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . __( 'Tours', 'lsx-activities' ) . ':</span> ', '</span>' );
						?>
					</div>
				<?php } ?>
			</div>

			<?php $has_single = ! lsx_to_is_single_disabled(); ?>

			<?php if ( $has_single && 'grid' === tour_operator()->archive_layout ) : ?>
				<a href="<?php the_permalink(); ?>" class="moretag"><?php esc_html_e( 'View more', 'lsx-activities' ); ?></a>
			<?php endif; ?>
		<?php }
	}

	/**
	 * Adds the template tags fast facts
	 */
	public function single_fast_facts() {
		if ( is_singular( 'activity' ) ) { ?>
			<section id="fast-facts">
				<div class="lsx-to-section-inner">
					<h3 class="lsx-to-section-title"><?php esc_html_e( 'Activity Summary', 'lsx-activities' ); ?></h3>

					<div class="lsx-to-single-meta-data">
						<?php
							$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

							// lsx_to_price( '<span class="' . $meta_class . 'price"><span class="lsx-to-meta-data-key">' . esc_html__( 'From price', 'lsx-activities' ) . ':</span> ', '</span>' );
							lsx_to_accommodation_activity_friendly( '<span class="' . $meta_class . 'friendly"><span class="lsx-to-meta-data-key">' . __( 'Friendly', 'lsx-activities' ) . ':</span> ', '</span>' );
							lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . __( 'Location', 'lsx-activities' ) . ':</span> ', '</span>' );
							lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . __( 'Tours', 'lsx-activities' ) . ':</span> ', '</span>' );
							lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . __( 'Accommodation', 'lsx-activities' ) . ':</span> ', '</span>' );
						?>
					</div>
				</div>
			</section>
		<?php }
	}

	/**
	 * Adds the template tags to the bottom of the single activity
	 */
	public function single_content_bottom() {
		if ( is_singular( 'activity' ) ) {
			if ( function_exists( 'lsx_to_has_map' ) && lsx_to_has_map() ) : ?>
				<section id="activity-map" class="lsx-to-section lsx-to-collapse-section">
					<h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title hidden-lg" data-toggle="collapse" data-target="#collapse-activity-map"><?php esc_html_e( 'Map', 'lsx-activities' ); ?></h2>

					<div id="collapse-activity-map" class="collapse in">
						<div class="collapse-inner">
							<?php lsx_to_map(); ?>
						</div>
					</div>
				</section>
				<?php
			endif;

			lsx_to_activity_tours();

			lsx_to_activity_accommodation();

			lsx_to_gallery( '<section id="gallery" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-gallery">' . esc_html__( 'Gallery', 'lsx-activities' ) . '</h2><div id="collapse-gallery" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );

			if ( function_exists( 'lsx_to_videos' ) ) {
				lsx_to_videos( '<section id="videos" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-videos">' . esc_html__( 'Videos', 'lsx-activities' ) . '</h2><div id="collapse-videos" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );
			} elseif ( class_exists( 'Envira_Videos' ) ) {
				lsx_to_envira_videos( '<section id="videos" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-videos">' . esc_html__( 'Videos', 'lsx-activities' ) . '</h2><div id="collapse-videos" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );
			}

			lsx_to_activity_posts();
		}
	}

}

new LSX_Activities_Frontend();
