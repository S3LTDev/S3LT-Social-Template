<?php ob_start(); ?>
<div class="projects-section-line">

    <div class="projects-status">

        <div class="item-status">
            <span class="status-number" id="all-events">0</span>
            <span class="status-type">Events</span>
        </div>

        <div></div>
        <div></div>

        <div class="item-status">
            <span class="status-type"> </span>
            <span class="status-type">
                <select name="platform-select" id="platform-select">
                    <option value="upcoming" selected>Upcoming</option>
                    <option value="inprogress">In progress</option>
                </select>
            </span>
        </div>

        <div class="item-status">
            <span class="status-type"> </span>
            <span class="status-type">
                <select name="game-select" id="game-select">
                    <option value="" selected>Select game</option>
                    <option value="example-game">example gamessssssssssss</option>
                    <option value="example-game">example game</option>
                    <option value="example-game">example game</option>
                    <option value="example-game">example game</option>
                    <option value="example-game">example game</option>
                    <option value="example-game">example game</option>
                    <option value="example-game">example game</option>
                </select>
            </span>
        </div>

        <div class="item-status">
            <span class="status-type"> </span>
            <span class="status-type">
                <select name="platform-select" id="platform-select">
                    <option value="" selected>Select platform</option>
                    <option value="cross-p">Cross Play</option>
                    <option value="pc">Pc</option>
                    <option value="mobile">Mobile</option>
                    <option value="play-5">Playstation 5</option>
                    <option value="xbox-x">Xbox x</option>
                    <option value="switch">Switch</option>
                    <option value="play-4">Playstation 4</option>
                    <option value="xbox-1">Xbox one</option>
                </select>
            </span>
        </div>

    </div>
</div>
<div class="project-boxes jsGridView dontresize">

</div>

</div>
<?php $html = ob_get_clean(); ?>

<?php echo template('components/dashboard.php', [
    'title' => 'Events',
    'tab' => 'event',
    'content' => $html,
    'scripts' => [
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js',
        'js/event.js'
    ]
]) ?>