<?php
/**
 * @package POF Translation status
 */
/*
Plugin Name: POF Translation status
*/

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


/* Helper functions */
include( plugin_dir_path( __FILE__ ) . 'pof-translation-status-generic.php');
include( plugin_dir_path( __FILE__ ) . 'pof-translation-status-localization.php');

add_action( 'admin_menu', 'pof_translation_status_menu' );

function pof_translation_status_menu() {
	add_menu_page('POF K&auml;&auml;nn&ouml;kset status', 'POF K&auml;&auml;nn&ouml;kset status', 'manage_options', 'pof_translation_status_frontpage-handle', 'pof_translation_status_frontpage', 'dashicons-dashboard');
	add_submenu_page( 'pof_translation_status_frontpage-handle', 'Yleiset', 'Yleiset', 'manage_options', 'pof_translation_status_generic-handle', 'pof_translation_status_generic');
	add_submenu_page( 'pof_translation_status_frontpage-handle', 'Sis&auml;ll&ouml;t', 'Sis&auml;ll&ouml;t', 'manage_options', 'pof_translation_status_localization-handle', 'pof_translation_status_localization');
}

function pof_translation_status_frontpage() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	echo '<div class="wrap">';
	echo '<h1>POF K&auml;&auml;nn&ouml;kset status</h1>';
	echo '<p>Valitse vasemmasta valikosta, mit&auml; haluat katsoa.</p>';
	echo '</div>';
}