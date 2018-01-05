<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo STYLESHEETS_PATH . 'bootstrap.min.css'; ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo STYLESHEETS_PATH . 'style.css' ?>">
        <script src="<?php echo JAVASCRIPTS_PATH . 'signup.js'; ?>"></script>
        <script src="./login.js"></script>
        <title>User</title>
    </head>
    <body>
        <header>
            <?php echo navbar('login'); ?>
            <?php echo alert(true); ?>
        </header>
        
        <section id="profile">
            <div class="container-fluid bg-dark" id="profile-container">
                <div id="user-header" class="col-md-12 bg-primary">
                    
                </div>
                <div class="row flex-nowrap">
                    <div class="col col-md-2 offset-sm-1 font-weight-light text-left" id="bio-box">
                        <img id="avatar" class="rounded d-block mx-auto" src="https://i.pinimg.com/originals/09/b2/de/09b2deff3d7abfffaa12aed8ee14bbe0.png"/>    
                        <div id="username_tag" class="text-center">
                            <div class="row">
                                <div class="col-md-4 offset-3">
                                    <!--<a href="<?php echo USER_PROFILE_PATH; ?>">-->
                                        <p>@<?php echo $user_controller->user['Username']; ?></p>
                                        <!--</a>-->
                                </div>
                                <div class="col-md-2 offset-1">
                                    <?php 
                                        if(current_user()['User_Id'] == $user_controller->user['User_Id']) {
                                            echo '<a class="btn btn-link-primary" href="' . USER_EDIT_PATH .'&id=' . $user_controller->user['User_Id'] . '">Edit</a>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                            if(!(current_user()['User_Id'] == $user_controller->user['User_Id'])) {
                                if(!$user_controller->isSubscribedTo($user_controller->user['User_Id'])) {
                                    echo '
                                        <form style="display: inline;" method="POST">
                                          <input type="hidden" name="action" value="subscribe">
                                          <input type="hidden" name="Subscribe_To_Id" value="' . $user_controller->user['User_Id'] . '">
                                          <input type="submit" class="btn btn-link text-primary" value="Subscribe"></input>
                                        </form>';
                                } else {
                                    echo '
                                        <form style="display: inline;" method="POST">
                                          <input type="hidden" name="action" value="unsubscribe">
                                          <input type="hidden" name="Unsubscribe_To_Id" value="' . $user_controller->user['User_Id'] . '">
                                          <input type="submit" class="btn btn-link text-primary" value="Unsubscribe"></input>
                                        </form>';
                                }
                            }
                        ?>
                        <p><?php //echo '<a class="btn btn-link-primary" href="">Subscribe</a>'; ?></p>
                        <p><?php echo $user_controller->user['Bio']; ?></p>
                    </div>
                    <div class="col col-sm-8">
                        <div class="row">
                            <div class="col col-sm-8 offset-1" style="margin-top: 30px;">
                                <div class="bg-light rounded">
                                    <h3 class="text-dark text-center">Timeline</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-sm-8 offset-1">
                                <div class="card">
                                    <div class="card-body">
                                      <form style="display: inline;">
                                        <input type="hidden" name="page" value="profile">
                                        <input type="hidden" name="username" value="<?php echo $user_controller->user['Username']; ?>">
                                        <input type="submit" class="btn btn-link text-primary" value="Habits"></input>
                                      </form>
                                      <form style="display: inline;">
                                        <input type="hidden" name="page" value="profile">
                                        <input type="hidden" name="action" value="posts">
                                        <input type="hidden" name="username" value="<?php echo $user_controller->user['Username']; ?>">
                                        <input type="submit" class="btn btn-link text-primary" value="Posts"></input>
                                      </form>
                                      <!--<button class="btn btn-link text-primary">Friends</button>-->
                                      <!--<button class="btn btn-link text-primary">Likes</button>-->
                                    </div>
                                        <?php
                                            switch($_GET['action']) {
                                                case 'posts':
                                                    echo post_timeline($user_controller->posts, $user_controller->user);
                                                    break;
                                                default:
                                                    echo habit_timeline($user_controller->habits);
                                                    break;
                                            }
                                        ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>