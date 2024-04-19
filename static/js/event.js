/*  
@@@@@@ @@@@@@  @@@      @@@@@@@      @@@@@@@  @@@@@@@@ @@@  @@@
@@         @@! @@!        @@!        @@!  @@@ @@!      @@!  @@@
!@@!!   @!!!:  @!!        @!!        @!@  !@! @!!!:!   @!@  !@!
   !:!     !!: !!:        !!:        !!:  !!! !!:       !: .:! 
::.: :  ::: ::  : ::.: :    :         :: :  :  : :: :::    ::   

link  https://github.com/S3LTDev/S3LT-Social-Template
author  max2tz https://github.com/S3LT
license  GPL-3.0 License
*/
function updateEvents() {
    $.getJSON("api.event", function(data) {

        $('.project-boxes').empty();

        $.each(data.events, function(key, val) {
            let current_datetime = new Date(val.start_timestamp)

            $('.project-boxes').append(
                '<div class="project-box-wrapper" id="' + val.game + '">' +
                '<div class="project-box random-blue">' +
                '<div class="project-box-header">' +
                '<span>' + moment(current_datetime).format("MMM Do YY | h:mm a") + '</span>' +
                '</div>' +
                '<div class="project-box-content-header">' +
                '<p class="box-content-header">' + val.game + '</p>' +
                '<p class="box-content-subheader">' + val.platform + '</p>' +
                '</div>' +
                '<div class="project-box-footer">' +
                '<div class="participants" title="Host: ' + val.host.username + '">' +
                '<a href="user/' + val.host.id + '"><img src="' + val.host.avatar + '" alt="participant"></a>' +
                '</div>' +
                '<div class="badge-storage">' +
                '<div class="badge-trans">' +
                'Starts in ' + moment(current_datetime).fromNow() +
                '</div>' +
                '<div class="badge-trans">' +
                '<a href="">Join now</a>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>')
        });
    }).done(function() {
        colorRandomBlue()
        var eventid = document.getElementsByClassName('project-box-wrapper');
        document.getElementById('all-events').innerHTML = eventid.length
    });;

}


document.addEventListener('DOMContentLoaded', function() {

    updateEvents()

    setInterval(function() {
        updateEvents()
    }, 30000);
});
