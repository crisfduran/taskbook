<?php
/*
Plugin Name: Task Book
Description: Track stress and anxiety levels around tasks.
Version:     1.0.0
Author:      Cris Duran
Author URI:  crisduran.se
Text Domain: taskbook
Domain Path: /languages
License:     GPL3

Task Book is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Task Book is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Task Book. If not, see https://www.gnu.org/licenses/gpl.html.
*/

/**
 * Register Task post type.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/posttypes.php';
register_activation_hook( __FILE__, 'taskbook_rewrite_flush' );
 
/**
 * Register Task Logger role.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/roles.php';
register_activation_hook( __FILE__, 'taskbook_register_role' );
register_deactivation_hook( __FILE__, 'taskbook_remove_role' );

/**
 * Add capabilities.
 */
register_activation_hook( __FILE__, 'taskbook_add_capabilities' );
register_deactivation_hook( __FILE__, 'taskbook_remove_capabilities' );

/**
 * Register task_status field.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/status.php';

/**
 * Register CMB2 metaboxes and fields.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/CMB2-functions.php';

/**
 * Grant access to tasks only to authenticated users
 * with either administrator or task logger roles.
 */
add_action( 'pre_get_posts', 'taskbook_grant_access' );

function taskbook_grant_access( $query ) {

    if ( isset($query->query_vars['post_type']) ) {

        if ( $query->query_vars['post_type'] == 'task' ) {

            if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {

                if ( current_user_can( 'administrator') ) {
                    $query->set( 'post_status', 'private' );
                } elseif ( current_user_can( 'task_logger' ) ) {
                    $query->set( 'post_status', 'private' );
                    $query->set( 'author', get_current_user_id() );
                }
            }
        }
    }
}