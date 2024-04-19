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
<div class="project-boxes jsListView dontresize">
    <a href="">
        <div class="project-box-wrapper">
            <div class="project-box random-blue">
                <div class="project-box-header">
                    <span>Staff guides and information</span>
                </div>
                <div class="project-box-content-header">
                    <p class="box-content-header">Information</p>
                </div>
            </div>
        </div>
    </a>

    <a href="">
        <div class="project-box-wrapper">
            <div class="project-box random-blue">
                <div class="project-box-header">
                    <span>The simple rules and guidelines to follow</span>
                </div>
                <div class="project-box-content-header">
                    <p class="box-content-header">Rules</p>
                </div>
            </div>
        </div>
    </a>

    <a href="">
        <div class="project-box-wrapper">
            <div class="project-box random-blue">
                <div class="project-box-header">
                    <span>The latest news regarding mortal</span>
                </div>
                <div class="project-box-content-header">
                    <p class="box-content-header">News</p>
                </div>
            </div>
        </div>
    </a>

    <div class="project-box-wrapper">
        <hr>
    </div>

    <a href="" class="project-a">
        <div class="project-box-wrapper">
            <div class="project-box random-blue">
                <div class="project-box-content-header">
                    <!-- <p class="box-content-header">House of bravery</p> -->
                    <img class="box-content-subheader" src="<?php echo asset('img/bravery.svg')  ?>" alt="" srcset="">
                </div>
                <div class="box-progress-wrapper">
                    <p>
                        <h1>House of bravery</h1>
                        <h3>General topic</h3>
                    </p>
                </div>
            </div>
        </div>
    </a>

    <a href="" class="project-a">
        <div class="project-box-wrapper">
            <div class="project-box random-blue">
                <div class="project-box-content-header">
                    <!-- <p class="box-content-header">House of brilliance</p> -->
                    <img class="box-content-subheader" src="<?php echo asset('img/brilliance.svg') ?>" alt="" srcset="">
                </div>
                <div class="box-progress-wrapper">
                    <p>
                        <h1>House of brilliance</h1>
                        <h3>Group finder topic</h3>
                    </p>
                </div>
            </div>
        </div>
    </a>

    <a href="" class="project-a">
        <div class="project-box-wrapper">
            <div class="project-box random-blue">
                <div class="project-box-content-header">
                    <!-- <p class="box-content-header">House of balance</p> -->
                    <img class="box-content-subheader" src="<?php echo asset('img/balance.svg') ?>" alt="" srcset="">
                </div>
                <div class="box-progress-wrapper">
                    <p>
                        <h1>House of balance</h1>
                        <h3>Support topic</h3>
                    </p>
                </div>
            </div>
        </div>
    </a>


</div>
<?php $html = ob_get_clean(); ?>

<?php echo template('components/dashboard.php', [
    'title' => 'Admin Panel',
    'tab' => 'admin',
    'content' => $html
]) ?>
