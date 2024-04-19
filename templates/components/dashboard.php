<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name=og:image content=>
    <title> Vixxy <?php echo $title ?></title>
    <link rel=icon type=image/x-icon href="static/img/favicon.png">
    <link rel="stylesheet" href="<?php echo asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('css/jqx.base.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('css/toastr.css') ?>">
</head>

<div class="page-loader">
    VIXXY
</div>

<body>
    <div class="app-container" style="display: none;">
        <div class="app-header">
            <div class="app-header-left">
                <img src="<?php echo asset('img/logo.svg') ?>" class="app-icon">
                <div class="dropdown" id="search-dropdown" style="float:left; width: 100%;">
                    <div class="search-wrapper">
                        <input class="search-input" type="text" placeholder="Search">
                        <i data-feather="search"></i>
                    </div>
                    <div class="search-dropdown-content participants">
                    </div>
                </div>
            </div>
            <div class="app-header-right">
                <button class="mobile-nav" title="Home" aria-label="Home">
                    <a href="<?php echo path('homePage') ?>"><i data-feather="grid"></i></a>
                </button>
                <?php
                    if (isset($_SESSION['user'])) {
                ?>
                    <button class="mobile-nav" title="Events" aria-label="Events">
                        <a href="<?php echo path('eventPage') ?>"><i data-feather="calendar"></i></a>
                    </button>
                    <button class="mobile-nav" title="Shop" aria-label="Shop">
                        <a href="<?php echo path('shopPage') ?>"><i data-feather="shopping-cart"></i></a>
                    </button>
                <?php
                    }
                ?>
                <button class="mode-switch" title="Themes" aria-label="Themes">
                    <i data-feather="moon"></i>
                </button>
                <button class="notification-btn" title="Notifications" aria-label="Notifications">
                        <i data-feather="bell"></i>
                </button>
                <?php
                    if (isset($_SESSION['user'])) {
                ?>
                    <div class="dropdown" style="float:right;">
                        <button onclick="dropdown()" class="profile-btn" title="Your profile" aria-label="Your profile"> 
                            <img src="https://cdn.discordapp.com/avatars/<?php echo $_SESSION['user']->id; ?>/<?php echo $_SESSION['user']->avatar; ?>" alt="profile pic" />
                            <span><?php echo $_SESSION['user']->username; ?></span>
                        </button>
                        <div class="dropdown-content">
                            <a href="<?php echo path('userPage'); ?>" style="display: inline-flex !important; width:100%">
                                <i data-feather="user"></i> 
                                <span style='margin-left: 15px'>Profile</span>
                            </a>
                            <a href="<?php echo path('discordLogout'); ?>"  style="display: inline-flex !important; width:100%">
                                <i data-feather="log-out"></i> 
                                <span style='margin-left: 15px'>Log Out</span>
                            </a>
                        </div>
                    </div>
                <?php
                    } else {
                ?> 
                    <a href="<?php echo path('discordLogin'); ?>">
                        <button class="profile-btn" title="Your profile" aria-label="Your profile"> 
                            <span>Login</span>
                        </button>
                    </a>
                <?php
                    }
                ?> 
            </div>
        </div>
        <div class="app-content">
            <div class="app-sidebar">
                <a href="<?php echo path('homePage') ?>" class="app-sidebar-link <?php if ($tab == 'home') {echo 'active';} ?>" title="Home" aria-label="Go to forms">
                    <i data-feather="grid"></i>
                </a>
                <?php
                if (isset($_SESSION['user'])) {
                    ?>
                <a href="<?php echo path('eventPage') ?>" class="app-sidebar-link <?php if ($tab == 'event') {echo 'active';} ?>" title="Events" aria-label="Go to events">
                    <i data-feather="calendar"></i>
                </a>
                <a href="<?php echo path('shopPage') ?>" class="app-sidebar-link <?php if ($tab == 'shop') {echo 'active';} ?>" title="Shop" aria-label="Go to events">
                    <i data-feather="shopping-cart"></i>
                </a>
                <?php
                }
                    if (isset($_SESSION['admin']) && $_SESSION['admin']) {
                        ?>
                        <hr>
                        <a href="<?php echo path('adminPage'); ?>" class="app-sidebar-link <?php if ($tab == 'admin') {echo 'active';} ?>" title="Admin" aria-label="Go to admin">
                            <i data-feather="shield"></i>
                        </a>
                        <a href="<?php echo path('userListPage'); ?>" class="app-sidebar-link <?php if ($tab == 'user') {echo 'active';} ?>" title="Users" aria-label="Users">
                            <i data-feather="users"></i>
                        </a>
                        <?php
                    }
                ?>
            </div>
            <div class="projects-section">
                <div class="projects-section-header" id="page-title">
                    <p><?php echo $title ?></p>
                </div>

                <?php echo $content ?>

            </div>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="<?php echo asset('js/index.js') ?>"></script>
<script src="<?php echo asset('js/utils.js') ?>"></script>
<script src="<?php echo asset('js/toastr.js') ?>"></script>

<?php
if (isset($scripts)) {
    foreach ($scripts as $script) {
        if (strpos($script, 'http') !== false) {
            echo '<script src="' . $script . '"></script>';
        } else {
            echo '<script src="' . asset($script) . '"></script>';
        }
    }
}
?>

</html>