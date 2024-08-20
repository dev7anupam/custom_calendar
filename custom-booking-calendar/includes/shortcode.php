<?php
// Shortcode to display the booking form
function cbp_booking_form_shortcode() {
    ob_start();
    
    if (isset($_GET['meeting_duration'])) {
        $meeting_duration = intval($_GET['meeting_duration']);
        echo '<h2>You selected a ' . esc_html($meeting_duration) . ' minute meeting.</h2>';
    }    
    ?>
    <div class="cbp-booking-form">
        <form id="cbp_booking_form">
            <label for="cbp_date">Select Date:</label>
            <input type="date" id="cbp_date" name="cbp_date" min="<?php echo date('Y-m-d'); ?>" required>
            <br>
            <label for="cbp_time_slot">Select Time Slot:</label>
            <select id="cbp_time_slot" name="cbp_time_slot" required>
                <!-- Time slots will be dynamically loaded here -->
            </select>
            <br>
            <label for="cbp_name">Name:</label>
            <input type="text" id="cbp_name" name="cbp_name" required>
            <br>
            <label for="cbp_email">Email:</label>
            <input type="email" id="cbp_email" name="cbp_email" required>
            <br>
            <label for="cbp_description">Description:</label>
            <textarea id="cbp_description" name="cbp_description" required></textarea>
            <br>
            <button type="submit">Book Appointment</button>
        </form>
        <div id="cbp_booking_result"></div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('#cbp_date').on('change', function() {
                var selectedDate = $(this).val();
                $.ajax({
                    url: ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'cbp_get_time_slots',
                        date: selectedDate
                    },
                    success: function(response) {
                        $('#cbp_time_slot').html(response);
                    }
                });
            });

            $('#cbp_booking_form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'cbp_handle_booking',
                        data: $(this).serialize()
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#cbp_booking_result').html('<p>' + response.data.message + '</p>');
                        } else {
                            $('#cbp_booking_result').html('<p>' + response.data.message + '</p>');
                        }
                    }
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('cbp_booking_form', 'cbp_booking_form_shortcode');