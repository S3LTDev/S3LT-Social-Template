<?php ob_start(); ?>
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

<script>
    const _userId = "<?php echo $userId; ?>"
</script>

<div class="element-wrapper" id="gems-wrapper">
    <div class="projects-section-header">
        <p>Gems</p>
    </div>

    <div class="project-boxes jsGridView">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%; text-align: center;">
                    <div id="gems">
                        <span></span>
                    </div>                                    
                </td>
                <td>
                    <input type="number" id="gems_val" style="width: 80px; text-align: center;" value="0" />
                    <a href='#' onclick='updateGems(true)'>Add</a> | 
                    <a href='#' onclick='updateGems(false)'>Remove</a>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="element-wrapper" id="badges-wrapper">
    <div class="projects-section-header">
        <p>Badges</p>
        <span><a href='#' onclick='addBadge()'>Add Badge</a></span>
    </div>

    <div class="project-boxes jsGridView" id="badges">
        <table style="width: 100%;">
        </table>
    </div>
</div>

<div class="element-wrapper" id="inventory-wrapper">
    <div class="projects-section-header">
        <p>Inventory</p>
        <span><a href='#' onclick='addInventory()'>Add Inventory</a></span>
    </div>

    <div class="project-boxes jsGridView" id="inventory">
        <!-- inventory here -->
        <div class="project-box-wrapper">
            <div class="project-box random-blue">
                <div class="project-box-header">
                    <span> Title </span>
                    <a href='#' onclick='removeInventory(10)'>Remove</a>
                </div>
                <div class="project-box-content-header">
                    <img class="inventory-img" src=" ../static/img/items/val.asset " alt="">
                </div>
                <div class="box-progress-wrapper">
                    <p class="box-progress-header"> Header </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="element-wrapper" id="events-wrapper">
    <div class="projects-section-header">
        <p>Events</p>
        <span><a href='#' onclick='addEvent()'>Add Event</a></span>
    </div>

    <div class="project-boxes jsGridView" id="events" style="max-height: 370px; overflow-y: auto;">
        <!-- Events here -->
        <div class="project-box-wrapper" id=" val.game ">
            <div class="project-box random-blue">
                <div class="project-box-header">
                    <span> moment(event_datetime).format("MMM Do YY | h:mm a") </span>
                    <a href='#' onclick='removeEvent(10)'>Remove</a>
                </div>
                <div class="project-box-content-header">
                    <p class="box-content-header"> val.game </p>
                    <p class="box-content-subheader"> val.platform </p>
                </div>
                <div class="project-box-footer">
                    <div class="participants" title="Host:  val.host.username ">
                        <img src=" val.host.avatar " alt="participant">
                    </div>
                    <div class="badge-storage">
                        <div class="badge-trans">
                            Starts in  moment(event_datetime).fromNow()
                        </div>
                        <div class="badge-trans">
                            <a href="">Join now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="posts-wrapper">
    <div class="projects-section-header">
        <p>Posts</p>
    </div>

    <div class="project-boxes jsListView" id="posts" style="max-height: 260px; overflow-y: auto;">
        <!-- Posts here -->
    </div>
</div>

<div id="badgeWindow">
    <div>
        <span>Add Badge</span>
    </div>
    <div style="overflow: hidden;">
        <div id='badgeForm' style="width: 390px; height: auto;"></div>   
    </div>
</div>

<div id="inventoryWindow">
    <div>
        <span>Add Inventory</span>
    </div>
    <div style="overflow: hidden;">
        <div id='inventoryForm' style="width: 390px; height: auto;"></div>   
    </div>
</div>

<div id="eventWindow">
    <div>
        <span>Add Event</span>
    </div>
    <div style="overflow: hidden;">
        <div id='eventForm' style="width: 390px; height: auto;"></div>   
    </div>
</div>

<?php $html = ob_get_clean(); ?>

<?php echo template('components/dashboard.php', [
    'title' => 'User Detail Panel',
    'tab' => 'user',
    'content' => $html,
    'scripts' => [
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js',
        'js/jqx-all.js',
        'js/admin/userDetail.js',
    ]
]) ?>
