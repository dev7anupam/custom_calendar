<?php
// Add the menu page
add_action('admin_menu', 'cbp_add_booking_settings_page');
function cbp_add_booking_settings_page()
{
    add_menu_page(
        'Booking Settings', // Page title
        'Booking Settings', // Menu title
        'manage_options', // Capability
        'booking-settings', // Menu slug
        'cbp_booking_settings_page', // Callback function
        'dashicons-calendar-alt', // Icon
        6 // Position
    );
}


// Callback function for the booking settings page
function cbp_booking_settings_page()
{
?>
    <div class="wrap">
        <h1>Booking Settings</h1>
        <div class="cbp-button-container">
            <?php

            $booking_page = get_page_by_path('booking-page');
            $booking_page_url = $booking_page ? get_permalink($booking_page) : '';

            $default_timings = get_posts(array(
                'post_type' => 'timing_card',
                'post_status' => 'publish',
                'posts_per_page' => -1
            ));
            foreach ($default_timings as $post) {
                echo '<div class="cbp-timing-card">';
                $timing_value = get_post_meta($post->ID, 'cbp_time_value', true);
                $timing_name = $post->post_title;
                echo '<button class="cbp-meeting-button" data-value="' . esc_attr($timing_value) . '" data-url="' . esc_attr($booking_page_url) . '" >' . esc_attr($timing_value) . ' minute meeting -' . esc_html($timing_name) . '</button>';
                echo '<button class="cbp-delete-button" data-post-id="' . esc_attr($post->ID) . '"></button>';
                echo '</div>';
            }
            ?>
        </div>
        <a class="cbp-add-link-button" href="#popup2">+</a>
    </div>
    <div id="popup2" class="popup-container popup-style-2">
        <div class="popup-content">
            <a href="#" class="close">&times;</a>
            <div class="outer">
                <div class="cbp-field">
                    <label for="cbp-new-time-name">Meeting Name:</label>
                    <input type="text" id="cbp-new-time-name">
                </div>
                <div class="cbp-fiel">
                    <label for="cbp-new-time">Minutes:</label>
                    <input type="number" id="cbp-new-time" min="15" max="60">
                </div>
            </div>
            <button id="cbp-add-time-btn">Submit</button>

        </div>
    </div>
<?php
}
