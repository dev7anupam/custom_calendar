<?php
/*
Plugin Name: Booking Calendar Plugin
Plugin URI: https://google.com
Description: A custom plugin for calendar-based booking with Google and Outlook integration.
Version: 1.0
Author: Dev Rudra
Author URI: https://google.com
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
function cbp_enqueue_scripts()
{
    wp_enqueue_style('cbp-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('cbp-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), null, true);
    wp_localize_script('cbp-script', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', 'cbp_enqueue_scripts');

include(plugin_dir_path(__FILE__) . 'admin/includes/admin-settings.php');
include(plugin_dir_path(__FILE__) . 'includes/shortcode.php');
include(plugin_dir_path(__FILE__) . 'admin/includes/admin-ajax.php');

// Enqueue scripts and styles
function cbp_enqueue_admin_scripts() {
    wp_enqueue_style('cbp-admin-style', plugins_url('admin/css/admin-style.css', __FILE__));
    wp_enqueue_script('cbp-admin-js', plugin_dir_url(__FILE__) . 'admin/js/cbp-admin.js', array('jquery'), null, true);
    wp_localize_script('cbp-admin-js', 'cbp_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('cbp_ajax_nonce'),
    ));
}
add_action('admin_enqueue_scripts', 'cbp_enqueue_admin_scripts');

register_activation_hook(__FILE__, 'cbp_create_booking_page');

function cbp_create_booking_page() {
    $booking_page = get_page_by_path('booking-page');

    if (!$booking_page) {
        $new_page_id = wp_insert_post(array(
            'post_title'     => 'Booking Page',
            'post_name'      => 'booking-page',
            'post_content'   => '[cbp_booking_form]', 
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_author'    => get_current_user_id(),
        ));

        if ($new_page_id && !is_wp_error($new_page_id)) {
            update_post_meta($new_page_id, '_wp_page_template', 'templates/template-booking-page.php');
        }
    }
}

add_filter('template_include', 'cbp_load_custom_template');

function cbp_load_custom_template($template) {
    if (is_page('booking-page')) {
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/template-booking-page.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}

// Register the Custom Post Type "Timing Cards"
function cbp_register_timing_cards_cpt()
{
    $labels = array(
        'name'                  => _x('Timing Cards', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Timing Card', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Timing Cards', 'text_domain'),
        'name_admin_bar'        => __('Timing Card', 'text_domain'),
        'archives'              => __('Item Archives', 'text_domain'),
        'attributes'            => __('Item Attributes', 'text_domain'),
        'parent_item_colon'     => __('Parent Item:', 'text_domain'),
        'all_items'             => __('All Items', 'text_domain'),
        'add_new_item'          => __('Add New Item', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Item', 'text_domain'),
        'edit_item'             => __('Edit Item', 'text_domain'),
        'update_item'           => __('Update Item', 'text_domain'),
        'view_item'             => __('View Item', 'text_domain'),
        'view_items'            => __('View Items', 'text_domain'),
        'search_items'          => __('Search Item', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Featured Image', 'text_domain'),
        'set_featured_image'    => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image'    => __('Use as featured image', 'text_domain'),
        'insert_into_item'      => __('Insert into item', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
        'items_list'            => __('Items list', 'text_domain'),
        'items_list_navigation' => __('Items list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter items list', 'text_domain'),
    );
    $args = array(
        'label'                 => __('Timing Card', 'text_domain'),
        'description'           => __('Timing Card Description', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type('timing_card', $args);
}
add_action('init', 'cbp_register_timing_cards_cpt', 0);
