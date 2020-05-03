<?php
/**
 * Register a new task_status field and set it automatically
 * based on the status of the taskbook_outcome field.
 *
 * Called from taskbook.php.
 * 
 * @package  Taskbook
 * @link     https://developer.wordpress.org/reference/functions/register_rest_field/
 */

add_action( 'rest_api_init', 'taskbook_register_task_status' );
 
function taskbook_register_task_status() {
 
    register_rest_field(
         'task', 
         'post-meta-fields', 
         array(
           'get_callback'    => 'get_post_meta_for_api',
           'schema'          => null,
        )
    );
}
 
function get_post_meta_for_api( $object ) {
    //get the id of the post object array
    $post_id = $object['id'];
 
    //return the post meta
    return get_post_meta( $post_id );
}