jQuery(document).ready(function($) {
    console.log("admin-js");
    var addButton = $(".cbp-add-button");
    var inputField = $(".cbp-input-field");
    var addTimeBtn = $("#cbp-add-time-btn");
    var pop_up = $("#popup");

    addButton.on("click", function() {
        $('a[href="#popup2"]').click();
        console.log('click');
        // inputField.toggle();
        // pop_up.toggle();
    });

    addTimeBtn.on("click", function() {
        var newTime = $("#cbp-new-time").val();
        var newTimeName = $("#cbp-new-time-name").val();
        
        if (newTime >= 15 && newTime <= 60 && newTimeName) {
            var data = {
                action: 'cbp_save_timing_card',
                time_name: newTimeName,
                time_value: newTime,
                nonce: cbp_ajax.nonce
            };

            $.post(cbp_ajax.ajax_url, data, function(response) {
                if (response.success) {
                    let url = window.location.href;
                    url = url.replace('#popup2', '');
                    window.location.href = url;
                    // var newButton = $("<button>")
                    //     .addClass("cbp-meeting-button")
                    //     .data('value', response.data.value)
                    //     .text(response.data.value + " minutes meeting -" + response.data.name);

                    // $(".cbp-button-container").append(newButton);

                    // inputField.hide();
                    // $("#cbp-new-time").val('');
                    // $("#cbp-new-time-name").val('');
                } else {
                    alert("Error saving the timing card.");
                }
            });
        } else {
            alert("Please enter a meeting name and valid minutes.");
        }
    });

    $('.cbp-meeting-button').on('click', function() {
        var meetingValue = $(this).data('value');
        var bookingPageUrl = $(this).data('url');

        if (bookingPageUrl) {
            window.location.href = bookingPageUrl + '?meeting_duration=' + meetingValue;
        } else {
            alert('Booking page URL is not defined.');
        }
    });

    $('.cbp-button-container').on('click', '.cbp-delete-button', function() {
        var postId = $(this).data('post-id');
    
        if (confirm('Are you sure you want to delete this timing card?')) {

            var data = {
                action: 'cbp_delete_timing_card',
                post_id: postId,
                nonce: cbp_ajax.nonce
            };

            $.post(cbp_ajax.ajax_url, data, function(response) {
                if (response.success) {
                    let url = window.location.href;
                    url = url.replace('#popup2', '');
                    window.location.href = url;
                } else {
                    alert("Error saving the timing card.");
                }
            });

        }

    });

});
