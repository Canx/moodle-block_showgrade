<?php
 
require_once('../../config.php');
require_once('./badgelevel_form.php');


	
require_login();

$courseid = optional_param('courseid', 0, PARAM_INT);
$blockid = optional_param('blockid', 0, PARAM_INT);

// $PAGE SETUP
$url = new moodle_url('/blocks/showgrade/badgelevel_view.php');

// Para que sirve???
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_url($url);


// $PAGE->set_title
// $PAGE->set_heading

// Que diferencia hay con otros layouts???
$PAGE->set_pagelayout('standard'); 
// $PAGE->navbar->add


$badgelevel_form = new badgelevel_form(null, array('courseid'=>$courseid, 'blockid'=>$blockid ));

echo $OUTPUT->header();

if ($badgelevel_form->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($formdata = $badgelevel_form->get_data()) {
     //In this case you process validated data. $mform->get_data() returns data posted in form.
} else {
// this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
// or on the first display of the form.
    $badgelevel_form->display();
}

echo $OUTPUT->footer();
