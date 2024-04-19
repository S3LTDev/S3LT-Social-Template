<?php ob_start(); ?>
<script>
    const userId = "<?php echo $userId; ?>"
</script>

<div class="projects-section-line">

    <div class="projects-status">

        <div class="item-status">
            <span class="status-number" id="all-items">0</span>
            <span class="status-type">Items</span>
        </div>

        <div class="item-status">
            <span class="status-number" id="user-gems">0</span>
            <span class="status-type">Your gems</span>
        </div>

        <div></div>

        <div class="item-status">
            <span class="status-type"> </span>
            <span class="status-type">
                <select name="rarity-select" id="rarity-select">
                    <option value="4" selected>Limited Time</option>
                    <option value="1" selected>Rare</option>
                    <option value="3" selected>Uncommon</option>
                    <option value="2" selected>Common</option>
                    <option value="0" selected>All</option>

                </select>
            </span>
        </div>

        <div class="item-status">
            <span class="status-type"> </span>
            <span class="status-type">
                <input name="name-select" id="name-select" placeholder="Item name">
            </span>
        </div>

    </div>
</div>
<div class="project-boxes jsGridView dontresize">
    <!-- Items here -->
</div>
<?php $html = ob_get_clean(); ?>

<?php echo template('components/dashboard.php', [
    'title' => 'Shop',
    'tab' => 'shop',
    'content' => $html,
    'scripts' => [
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js',
        'js/shop.js'
    ]
]) ?>