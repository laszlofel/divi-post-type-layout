<?php

/**
 * Plugin name: KD Divi Post Type Layout
 * Description: Divi post type layout.
 * Author: Felföldi László
 * Version: 0.0.6
 */

class KDDiviPostTypeLayout {

	private static $instance;
	public static function getInstance() {
		if ( !( self::$instance instanceof KDDiviPostTypeLayout ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {

		add_action( 'template_redirect', [ $this, 'layout_select' ] );

		add_shortcode( 'kd_divi_post_type_layout', [ $this, 'shortcode' ] );
		add_shortcode( 'category_name', [ $this, 'category_name' ] );

	}

	public function layout_select() {

		global $post;

		if ( is_single() ) {

			$post_name = apply_filters( 'kd_dptl_layout', ucfirst( get_post_type() ) );
			$layout = get_page_by_path( $post_name . ' Layout', OBJECT, 'et_pb_layout' );

		}

		if ( is_archive() ) {

			$object =  get_queried_object();
			if ( $object instanceof WP_Term ) {

				$post_name = apply_filters( 'kd_dptl_layout', ucfirst( $object->taxonomy ) );
				$layout = get_page_by_path( $post_name . ' Term Layout', OBJECT, 'et_pb_layout' );

			} else {

				$post_name = apply_filters( 'kd_dptl_layout', ucfirst( get_post_type() ) );
				$layout = get_page_by_path( $post_name . ' Archive Layout', OBJECT, 'et_pb_layout' );

			}
			
		}

		if ( is_search() ) {
			$layout = get_page_by_path( 'Search Layout', OBJECT, 'et_pb_layout' );
		}

		if ( !empty( $layout ) ) {

			get_header();
			echo do_shortcode( $layout->post_content );
			get_footer();
			exit;

		}

	}

	public function shortcode() {

		ob_start();
		the_post();
		the_content();
		return ob_get_clean();

	}

	public function category_name() {
		return get_queried_object()->name;
	}

}

KDDiviPostTypeLayout::getInstance();