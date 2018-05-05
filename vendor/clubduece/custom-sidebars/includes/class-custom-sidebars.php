<?php
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Class Custom_Sidebars
 */
class Custom_Sidebars {

	/**
	 * @var Custom_Sidebars_Details
	 */
	private $custom_sidebar_details;

	/**
	 * The default sidebar id
	 *
	 * @var    string
	 * @access private
	 * @static
	 */
	private static $default_sidebar_id;

	/**
	 * The class constructor
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_filter( 'sidebars_widgets', array( $this, 'sidebars_widgets' ) );

	}

	/**
	 * Instantiate the custom sidebars metabox class
	 *
	 * This method is hooked to the WP admin_init action
	 */
	public function admin_init() {

		$this->custom_sidebar_details = new Custom_Sidebars_Details();

	}

	/**
	 * The WP admin_init action callback
	 *
	 * Get all pages and posts and add sidebars for each individual post
	 */
	public function init() {

		$posts = array();

		if ( ! $posts = wp_cache_get( 'sidebars', 'custom_sidebars' ) ) {
			$args = array(
				'post_type' => Custom_Sidebars::get_post_types(),
				'meta_query' => array(
					array(
						'key'   => '_custom_sidebar',
						'value' => true,
					)
				)
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				$posts = $query->posts;
			}

			wp_cache_set( 'sidebars', 'custom_sidebars' );
		}

		foreach ( $posts as $post ) {
			$args = apply_filters( 'custom_sidebar_args', array(
				'name'          => sprintf( __( 'Sidebar: %s', 'custom_sidebars' ), $post->post_title ),
				'id'            => $this->get_sidebar_id( $post->ID ),
				'description'   => sprintf( __( 'This sidebar will appear on the %s single page.', 'cd-sidebar' ), $post->post_title ),
				'class'         => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );

			register_sidebar( $args );
		}

	}

	/**
	 * Get the sidebar id for a specific post
	 *
	 * @param  int|null The WP post id
	 * @return string   The sidebar id
	 */
	public function get_sidebar_id( $post_id = null ) {

		$post = get_post( $post_id );

		return "custom-sidebar-{$post->ID}";

	}

	/**
	 * Get the post types for which custom sidebars should be registered
	 */
	public static function get_post_types() {

		return apply_filters( 'custom_sidebar_post_types', array_values( get_post_types() ) );

	}

	/**
	 * Get the sidebar_id assigned to a specific post
	 */
	public static function get_sidebar( $post_id = null ) {

		if ( empty( $post_id ) ) {
			$post_id = get_post()->ID;
		}

		$sidebar = get_post_meta( $post_id, '_custom_sidebar_id', true );

		if ( $sidebar == false && isset( self::$default_sidebar_id ) ) {
			$sidebar = self::$default_sidebar_id;
		}

		return $sidebar;

	}
	
	/**
	 * Register a default sidebar to use when a post has no specific sidebar assigned
	 */
	public static function register_default_sidebar( $sidebar_id ) {

		self::$default_sidebar_id = $sidebar_id;

	}

	/**
	 * Filter the sidebar widgets
	 *
	 * This method is hooked to the WP filter sidebars_widgets. 
	 *
	 * Since we have no way to know what sidebar is currently being called, this method
	 * replaces the array of widgets for ALL registered sidebars with the widgets
	 * assigned to the sidebar specified for use on this particular post/page. This is a
	 * carpet-bomb approach that should be more specific, but current limitiations in the
	 * WP infrastructure require it to be done this way.
	 *
	 * @param  array $sidebar_widgets
	 * @return array $sidebar_widgets The filtered widget array
	 * @since  0.3
	 */
	public function sidebars_widgets( $sidebar_widgets ) {

		if ( ! is_admin() && ! empty( $this->get_sidebar() ) ) {
			foreach ( $sidebar_widgets as $key => $widgets ) {
				$sidebar_widgets[ $key ] = $sidebar_widgets[ $this->get_sidebar() ];
			}
		}

		return $sidebar_widgets;

	}

}
