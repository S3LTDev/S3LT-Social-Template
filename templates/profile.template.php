<?php ob_start(); ?>

<script>
    const userId = "<?php echo $userId; ?>"
</script>


<div class="projects-section-header" id="profile-title">
    <div class="item-status" style="width: 100%">
        <div style='display: inline; padding: 0 10px; margin-top: 10px;' id="display-mode">
            <span class="status-number" id="display-name" style='font-size: 40px; font-weight: bold;'>Display Name</span>
            <button class='icon-button' id="edit-display" style='margin-left: 15px'>
                <i data-feather="edit-2" style='width:20px; height:20px;'></i>
            </button>
        </div>
        <div class="input-group" id="edit-mode">
            <input class="input" style="display: inline;" id="form-displayname" type="text" placeholder="Display Name">
            <div class="input-group-append">
                <button class='icon-button' id="cancel-display">
                    <i data-feather="x" style="margin-top: 23px"></i>
                </button>
                <button class='icon-button' id="save-display">
                    <i data-feather="check" style="margin: 23px 0 0 10px"></i>
                </button>
            </div>
            <span class="border"></span>
        </div>
        <span class="status-type" id="user-name" style='font-size: 25px; font-weight: bold; color: var(--light-font)'>User Name</span>
    </div>
</div>

<br></br>
<div class="projects-status">

    <div class="item-status" id="error-wrapper" style="display: none;">
        <span class="status-number" id="status">User doesn't exist</span>
    </div>

    <div class="item-status" id="status-wrapper" style="display: none;">
        <span class="status-number" id="status"></span>
        <span class="status-type">Status</span>
    </div>

    <div class="item-status" id="gems-wrapper" style="display: none;">
        <span class="status-number" id="gems"></span>
        <span class="status-type">Gems</span>
    </div>

    <div class="item-status" id="badges-wrapper" style="display: none;">
        <span class="status-number" id="badges"></span>
        <span class="status-type">Badges</span>
    </div>

</div>

<div class="element-wrapper" id="inventory-wrapper" style="display: none;">
    <div class="projects-section-header">
        <p>Inventory</p>
    </div>

    <div class="project-boxes jsGridView" id="inventory">
        <!-- inventory here -->
    </div>
</div>

<div class="element-wrapper" id="events-wrapper" style="display: none;">
    <div class="projects-section-header">
        <p>Events</p>
    </div>

    <div class="project-boxes jsGridView" id="events" style="max-height: 370px; overflow-y: auto;">
        <!-- Events here -->
    </div>
</div>

<div id="posts-wrapper" style="display: none;">
    <div class="projects-section-header">
        <p>Posts</p>
    </div>

    <div class="project-boxes jsListView" id="posts" style="max-height: 260px; overflow-y: auto;">
        <!-- Posts here -->
    </div>
</div>
</div>
<?php $html = ob_get_clean(); ?>

<?php echo template('components/dashboard.php', [
    'title' => '',
    'tab' => 'Profile',
    'content' => $html,
    'scripts' => [
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js',
        'js/profile.js'
    ]
]) ?>