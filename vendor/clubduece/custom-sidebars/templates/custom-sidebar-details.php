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
 * The custom sidebar details metabox
 *
 * @var Custom_Sidebars_Details $metabox
 */
?>
<label for="custom-sidebar"><?php _e( 'Register custom sidebar for this post?', 'custom-sidebars' ); ?></label>
<input type="checkbox" name="custom-sidebar" value="1" <?php checked( Custom_Sidebars_Details::has_custom_sidebar(), 1 ); ?> />
<label for="custom-sidebar-select"><?php _e( 'Use the following sidebar for this page:', 'custom-sidebars' ); ?></label>
<select name="custom-sidebar-select">
	<option value=""><?php _e( 'Default Sidebar', 'custom-sidebars' ); ?></option>
	<option value="none" <?php selected( Custom_Sidebars::get_sidebar(), 'none'); ?>><?php /* translators: as in 'No sidebar' */ _e( 'None', 'custom-sidebars' ); ?></option>
	<?php $metabox->sidebar_select_options(); ?>
</select>

