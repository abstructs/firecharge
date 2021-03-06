<?php

// EFFECTS: returns the html for the habit timeline, shows the create form
//          if show_habit_create is true or the username param in the url is equal
//          to the current user's username
function habit_timeline($habits, $show_habit_create=false) {
    $timeline = "";
    $form = '<div id="timeline" class="row">
                  <div class="col-md-10 offset-1">
                    <form action="' . HABIT_PATH .'" method="POST">
                        <div class="form-group mx-auto">
                          <input class="form-control" name="action" type="hidden" value="create"/>
                          <input maxlength="55" name="name" class="form-control" type="text" name="Name" placeholder="Name" required/></br>
                          <textarea maxlength="250" name="description" class="form-control" name="Details" placeholder="Details"></textarea>
                        </div>
                        <input class="btn btn-dark" type="submit" value="Track Habit"/>
                    </form>
                    <div class="text-dark">';
    
    if(current_user()['Username'] == get('username')) {
        $timeline = $form;
        
        if(!$habits) {
            $timeline = no_habits_card(true) . $timeline;
        }
        else {
            foreach($habits as $habit) {
                $timeline = $timeline . habit_card($habit);
            }
        }
        
        $timeline = $timeline . '</div>
                          </div>
                        </div>';
    } else if($habits) {
        foreach($habits as $habit) {
            $timeline = $timeline . habit_card($habit);
        }
    } else {
        $timeline = no_habits_card();
    }
    
    return $timeline;
    
    //                 <div class="card-footer text-muted text-center">
    //                     <a href="#" class="">Load More</a>
    //                 </div>';
                    
    return $timeline;
}
?>