<?php
// Handle the AJAX request to create a new Timing Card
function cbp_save_timing_card() {
    check_ajax_referer( 'cbp_ajax_nonce', 'nonce' );

    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( 'You do not have permission to perform this action.' );
    }

    $time_name = sanitize_text_field( $_POST['time_name'] );
    $time_value = intval( $_POST['time_value'] );

    if ( empty( $time_name ) || $time_value < 1 || $time_value > 60 ) {
        wp_send_json_error( 'Invalid time or name.' );
    }

    $post_id = wp_insert_post( array(
        'post_title'  => $time_name,
        'post_type'   => 'timing_card',
        'post_status' => 'publish',
        'meta_input'  => array(
            'cbp_time_value' => $time_value,
        ),
    ) );

    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( 'Error creating the timing card.' );
    }

    wp_send_json_success( array(
        'id'    => $post_id,
        'name'  => $time_name,
        'value' => $time_value,
    ) );
}
add_action( 'wp_ajax_cbp_save_timing_card', 'cbp_save_timing_card' );

// AJAX action to delete a timing card
function cbp_delete_timing_card() {
    // Verify nonce for security
    check_ajax_referer('cbp_ajax_nonce', 'nonce');

    if (isset($_POST['post_id'])) {
        $post_id = intval($_POST['post_id']);

        // Check if the post exists and delete it
        if (get_post($post_id) && get_post_type($post_id) === 'timing_card') {
            wp_delete_post($post_id, true); // Force delete
            wp_send_json_success('Timing card deleted.');
        } else {
            wp_send_json_error('Invalid post ID.');
        }
    } else {
        wp_send_json_error('Post ID not provided.');
    }
}
add_action('wp_ajax_cbp_delete_timing_card', 'cbp_delete_timing_card');

