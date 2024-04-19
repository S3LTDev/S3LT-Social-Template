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
                        <span>The one stop shop for all information and news</span>
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

                    <div class="project-box-wrapper">
                        <hr>
                    </div>

                    <a href="/chat/lounge" class="project-a">
                        <div class="project-box-wrapper">
                            <div class="project-box random-blue">
                                <div class="project-box-content-header">
                                    <!-- <p class="box-content-header">Vixxy Chatting Lounge</p> -->
                                    <img class="box-content-subheader" src="<?php echo asset('img/Verification.svg')  ?>" alt="" srcset="">
                                </div>
                                <div class="box-progress-wrapper">
                                    <p>
                                        <h1>Vixxy Chatting Lounge</h1>
                                        <h3>Come and talk your heart out</h3>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="/chat/alpha" class="project-a">
                        <div class="project-box-wrapper">
                            <div class="project-box random-blue">
                                <div class="project-box-content-header">
                                    <!-- <p class="box-content-header">The Alpha Testers Talk</p> -->
                                    <img class="box-content-subheader" src="<?php echo asset('img/alpha.svg') ?>" alt="" srcset="">
                                </div>
                                <div class="box-progress-wrapper">
                                    <p>
                                        <h1>The Alpha Testers Talk</h1>
                                        <h3>Meet the OGS of vixxy</h3>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="/chat/moderator" class="project-a">
                        <div class="project-box-wrapper">
                            <div class="project-box random-blue">
                                <div class="project-box-content-header">
                                    <!-- <p class="box-content-header">Exlusive Chat For Moderators</p> -->
                                    <img class="box-content-subheader" src="<?php echo asset('img/Moderator.svg') ?>" alt="" srcset="">
                                </div>
                                <div class="box-progress-wrapper">
                                    <p>
                                        <h1>Exlusive Chat For Moderators</h1>
                                        <h3>Take a break from moderating</h3>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>


                </div>
<?php $html = ob_get_clean(); ?>

<?php echo template('components/dashboard.php', [
    'title' => 'Home',
    'tab' => 'home',
    'content' => $html
]) ?>
