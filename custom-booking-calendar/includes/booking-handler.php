<?php

function cbp_handle_booking() {
    global $wpdb;

    parse_str($_POST['data'], $form_data);

    $booking_date = sanitize_text_field($form_data['cbp_date']);
    $booking_time = sanitize_text_field($form_data['cbp_time_slot']);
    $name = sanitize_text_field($form_data['cbp_name']);
    $email = sanitize_email($form_data['cbp_email']);
    $description = sanitize_textarea_field($form_data['cbp_description']);

    $table_name = $wpdb->prefix . 'cbp_bookings';

    // Check if the time slot is already booked.
    $existing_booking = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE booking_date = %s AND booking_time = %s",
            $booking_date, $booking_time
        )
    );

    if ($existing_booking > 0) {
        wp_send_json_error(array('message' => 'This time slot is already booked. Please select a different time.'));
    } else {
        $result = $wpdb->insert(
            $table_name,
            array(
                'booking_date' => $booking_date,
                'booking_time' => $booking_time,
                'name' => $name,
                'email' => $email,
                'description' => $description
            )
        );

        if ($result) {
            wp_send_json_success(array('message' => 'Booking confirmed!'));
        } else {
            wp_send_json_error(array('message' => 'Failed to book. Please try again.'));
        }
    }
}
add_action('wp_ajax_cbp_handle_booking', 'cbp_handle_booking');
add_action('wp_ajax_nopriv_cbp_handle_booking', 'cbp_handle_booking');
