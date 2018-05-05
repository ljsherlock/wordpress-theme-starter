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

class Custom_Sidebars_Details {

	/**
	 * The class constructor
	 */
	public function __construct() {

		add_action( 'save_post', array( $this, 'save_post' ) );

		foreach ( Custom_Sidebars::get_post_types() as $post_type ) {
			add_action( "add_meta_boxes_{$post_type}", array( $this, 'add_meta_boxes' ) );
		}

	}

	/**
	 * Add the metabox to the post edit screen
	 *
	 * Hooked to add_meta_boxes_{$post_type}
	 */
	public function add_meta_boxes() {

		add_meta_box(
			'custom-sidebar-details',
			__( 'Custom Sidebar', 'custom-sidebar' ),
			array( $this, 'render' ),
			get_post_type(),
			'side',
			'default',
			array()
		);

	}

	public function render() {

		$metabox = $this;
		include dirname( __DIR__ ) . '/templates/custom-sidebar-details.php';

	}

	public function sidebar_select_options() {

		global $wp_registered_sidebars;
		
		foreach ( $wp_registered_sidebars as $key => $sidebar ) {
			printf( '<option value="%1$s"%3$s>%2$s</option>', $key, $sidebar['name'], selected( Custom_Sidebars::get_sidebar(), $key ) );
		}

	}

	public function save_post( $post_id ) {

		$sidebar_id = $_POST['custom-sidebar-select'];

		if ( isset( $_POST['custom-sidebar'] ) ) {
			$sidebar_id = Custom_Sidebars::get_sidebar_id( $post_id );	
		}

		update_post_meta( $post_id, '_custom_sidebar',   ( bool )$_POST['custom-sidebar'] );
		update_post_meta( $post_id, '_custom_sidebar_id', sanitize_text_field( $sidebar_id ) );

	}

	public static function has_custom_sidebar( $post_id = null ) {

		$value = false;

		if ( empty( $post_id ) ) {
			$post_id = get_post()->ID;
		}

		if ( get_post_meta( $post_id, '_custom_sidebar', true ) ) {
			$value = true;
		}

		return $value;

	} 

}
