<?php
/*
Template Name: Booking Page Template
*/

get_header(); ?>

<div class="booking-form">
    <?php
    // You can use a shortcode or directly include your booking form here
    echo do_shortcode('[cbp_booking_form]');
    ?>
</div>

<?php get_footer(); ?>

