<?php
/*
Plugin Name: Client Quarry
Version: 1.0.0
Description: Adds a client user role to only allow the viewing/editing of a page assigned to that client.
Author: James Pistell
Author URI: http://www.intake123.com/
*/

if ( !defined( 'ABSPATH' ) ) exit;


// Add client role and capabilities on plugin activation
function activate_client_quarry() {
	add_role(
    		'client',
    		__( 'Client' ),
    		array(
			'read' => true,
			'create_pages' => true,
			'edit_pages' => true,
			'edit_published_pages' => true,
			'publish_pages' => true,
			'edit_others_pages' => false
    		)
	);
}
register_activation_hook( __FILE__, 'activate_client_quarry' );


// Limit visibility of other client pages
function posts_for_current_author($query) {
	global $user_level;

	if($query->is_admin && $user_level < 5) {
		global $user_ID;
		$query->set('author',  $user_ID);
		unset($user_ID);
	}
	unset($user_level);
	return $query;
}
add_filter('pre_get_posts', 'posts_for_current_author');


// Remove role on plugin deactivation
register_deactivation_hook( __FILE__, 'deactivate_client_quarry' );
function deactivate_client_quarry() {
	remove_role('client');
}
