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
define('RETREAT_TYPE_TAXONOMY', 'retreat_type');
define('COURSE_POST_TYPE', 'osn_course');
define('ADVISOR_POST_TYPE', 'osn_advisor');
define('OTHER_RESOURCES_POST_TYPE', 'osn_other_resources');

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
            'supports' => array('title', 'author', 'custom-fields', 'excerpt'),
            'taxonomies' => array('retreat_type', 'post_tag')
        )
    );

    register_post_type(COURSE_POST_TYPE,
        array(
            'labels' => array(
                'name' => __('Courses', 'textdomain'),
                'singular_name' => __('Course', 'textdomain')
            ),
            'public' => true,
            'show_ui' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'courses'),
            'supports' => array('title', 'author', 'custom-fields', 'excerpt')
        )
    );

    register_post_type(ADVISOR_POST_TYPE,
        array(
            'labels' => array(
                'name' => __('Advisors', 'textdomain'),
                'singular_name' => __('Advisor', 'textdomain')
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'advisors'),
            'supports' => array('title', 'author', 'custom-fields', 'excerpt')
        )
    );

    register_post_type(OTHER_RESOURCES_POST_TYPE,
        array(
            'labels' => array(
                'name' => __('Resources', 'textdomain'),
                'singular_name' => __('Resource', 'textdomain')
            ),
            'public' => false, // NO SINGLE PAGE/PERMALINK
            'publicly_queryable' => true,
            'show_ui' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'resources'),
            'supports' => array('title', 'author', 'custom-fields', 'excerpt')
        )
    );
}

function osn_change_title_placeholder($title, $post) {
    $post_type = $post->post_type;
    if ($post_type == TEACHER_POST_TYPE){
        return "Teacher Name";
    } else if ($post_type == RETREAT_LOCATION_POST_TYPE){
        return "Retreat Location Name";
    } else if ($post_type == HOSTED_RETREAT_POST_TYPE){
        return "Retreat Event Name";
    } else if ($post_type == COURSE_POST_TYPE) {
        return "Course Name";
    } else if ($post_type == ADVISOR_POST_TYPE) {
        return "Advisor Name";
    } else if ($post_type == OTHER_RESOURCES_POST_TYPE) {
        return "Resource Name";
    }

    return $title;
}

function osn_register_taxonomy_retreat_type() {
    $labels = array(
        'name'              => _x( 'Retreat Types', 'taxonomy general name' ),
        'singular_name'     => _x( 'Retreat Type', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Retreat Types' ),
        'all_items'         => __( 'All Retreat Types' ),
        'parent_item'       => __( 'Parent Retreat Type' ),
        'parent_item_colon' => __( 'Parent Retreat Type:' ),
        'edit_item'         => __( 'Edit Retreat Type' ),
        'update_item'       => __( 'Update Retreat Type' ),
        'add_new_item'      => __( 'Add New Retreat Type' ),
        'new_item_name'     => __( 'New Retreat Type' ),
        'menu_name'         => __( 'Retreat Type' ),
    );
    $args   = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'retreat-type' ],
        'show_in_rest' => true
    );
    register_taxonomy( 'retreat_type', [ 'osn_host_r' ], $args );
}

function osn_unregister_taxonomy_retreat_type() {
    register_taxonomy('retreat_type', array());
}

// given relationship A <- B -> C
// $source_post_id is the id of a row in A, $custom_field_name is name of the field to search in B
function meta_query_args(string $source_post_id, string $custom_field_name) {
    return array(
        array(
            'key' => $custom_field_name,
            'value' => "\"$custom_field_name\"",
            'compare' => 'LIKE'
        )
    );
}

function osn_activate_setup() {
    osn_register_taxonomy_retreat_type();
    osn_setup_post_types();
    flush_rewrite_rules();
}

function osn_deactivate_setup() {
    unregister_post_type(register_post_type(RETREAT_LOCATION_POST_TYPE));
    unregister_post_type(register_post_type(TEACHER_POST_TYPE));
    unregister_post_type(register_post_type(HOSTED_RETREAT_POST_TYPE));
    unregister_post_type(register_post_type(COURSE_POST_TYPE));
    unregister_post_type(register_post_type(ADVISOR_POST_TYPE));
    unregister_post_type(register_post_type(OTHER_RESOURCES_POST_TYPE));
    osn_unregister_taxonomy_retreat_type();
    flush_rewrite_rules();
}

add_action('init', 'osn_setup_post_types');
add_action( 'init', 'osn_register_taxonomy_retreat_type');
register_activation_hook(__FILE__, 'osn_activate_setup');
register_deactivation_hook(__FILE__, 'osn_deactivate_setup');

add_filter('enter_title_here', 'osn_change_title_placeholder', 10, 2);

?>