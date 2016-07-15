<?php

/*
 * Plugin Name: Post Likes
 * Description: WP REST API Post Likes
 * Author: Joe Hoyle
 * Version: 1.0
 * Author URI: http://joehoyle.co.uk
 */

namespace PostLikes;

use WP_Error;
use WP_REST_Server;
use WP_REST_Response;

add_action( 'rest_api_init', __NAMESPACE__ . '\\add_rest_field' );
add_action( 'rest_api_init', __NAMESPACE__ . '\\add_rest_route' );

/*
 * Register a new field on the REST API "post" object
 * so clients can display the Post Like count with posts.
 */
function add_rest_field() {

	if ( ! function_exists( 'register_rest_field' ) ) {
		return;
	}

	register_rest_field( 'post', 'liveblog_likes', array(
		'get_callback' => function( $request ) {
			return (int) get_post_meta( $request['id'], 'liveblog_likes', true );
		},
		'schema'       => array(
			'type'        => 'integer',
			'description' => 'The number of Liveblog Likes the post has.',
			'context'     => array( 'view', 'edit', 'embed' ),
		),
	) );
}

/*
 * Register a new REST API route for /liveblog-likes/v1/posts/$id/like
 * to allow clients to "like" any post. Only allow posts that are of
 * type "post" and published to be liked.
 */
function add_rest_route() {
	register_rest_route( 'liveblog-likes/v1', '/posts/(?P<id>[\d]+)/like', array(
		'methods'             => WP_REST_Server::CREATABLE,
		'args'                => array(
			'id' => array(
				'sanitize_callback' => 'absint',
			),
		),
		'permission_callback' => function( $request ) {

			if ( 'post' !== get_post_type( $request['id'] ) ) {
				return new WP_Error( 'invalid-post-type', 'You can only like posts.', array( 'status' => 400 ) );
			}

			if ( 'publish' !== get_post_status( $request['id'] ) ) {
				return new WP_Error( 'invalid-post-status', 'You can only like posts that are published.', array( 'status' => 400 ) );
			}

			return true;
		},
		'callback'            => function( $request ) {
			$likes = absint( get_post_meta( $request['id'], 'liveblog_likes', true ) ) + 1;
			update_post_meta( $request['id'], 'liveblog_likes', $likes );
			$response = array(
				'count' => $likes,
			);
			return new WP_REST_Response( $response, 201 );
		},
	));
}

