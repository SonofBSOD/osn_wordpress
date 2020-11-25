<?php
/**
 * Plugin Name: Open Sangha Network System Plugin
 * Description: This plugin provides the basic implementation of OSN's teachers and retreats system
 * Author: Willy Leung
 * Version: 1.00
 */


// rloc = retreat location

define('RETREAT_LOCATION_POST_TYPE', 'osn_rloc');
define('TEACHER_POST_TYPE', 'osn_teacher');
define('HOSTED_RETREAT_POST_TYPE', 'osn_host_r');

function osn_setup_post_types() {
    register_post_type(RETREAT_LOCATION_POST_TYPE,
        array(
            'labels' => array(
                'name' => __('Retreat Locations', 'textdomain'),
                'singular_name' => __('Retreat Location', 'textdomain')
            ),
            'public' => true,
            'show_ui' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'retreat-locations'),
            'supports' => array('title', 'author', 'custom-fields', 'excerpt')
        )
    );

    register_post_type(TEACHER_POST_TYPE,
        array(
            'labels' => array(
                'name' => __('Teachers', 'textdomain'),
                'singular_name' => __('Teacher', 'textdomain')
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'teachers'),
            'supports' => array('title', 'author', 'custom-fields', 'excerpt')
        )
    );

    register_post_type(HOSTED_RETREAT_POST_TYPE,
        array(
            'labels' => array(
                'name' => __('Retreat Events', 'textdomain'),
                'singular_name' => __('Retreat Event', 'textdomain')
            ),
            'public' => true,
            'show_ui' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'retreat-events'),
            'supports' => array('title', 'author', 'custom-fields', 'excerpt')
        )
    );
}

function osn_change_title_placeholder($title, $post) {
    if ( $post->post_type == TEACHER_POST_TYPE ){
        return "Teacher Name";
    } else if( $post->post_type == RETREAT_LOCATION_POST_TYPE ){
        return "Retreat Location Name";
    } else if( $post->post_type == HOSTED_RETREAT_POST_TYPE ){
        return "Retreat Event Name";
    }

    return $title;
}

function osn_activate_setup() {
    osn_setup_post_types();
    flush_rewrite_rules();
}

function osn_deactivate_setup() {
    unregister_post_type(register_post_type(RETREAT_LOCATION_POST_TYPE));
    unregister_post_type(register_post_type(TEACHER_POST_TYPE));
    unregister_post_type(register_post_type(HOSTED_RETREAT_POST_TYPE));
    flush_rewrite_rules();
}

add_action('init', 'osn_setup_post_types');
register_activation_hook(__FILE__, 'osn_activate_setup');
register_deactivation_hook(__FILE__, 'osn_deactivate_setup');

add_filter('enter_title_here', 'osn_change_title_placeholder', 10, 2);

?>