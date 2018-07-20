<?php
 
require_once('../../config.php');
require_once('./badgelevel_form.php');


	
require_login();

$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$return = optional_param('returnurl', 0, PARAM_LOCALURL);

// $PAGE SETUP
$url = new moodle_url('/blocks/showgrade/badgelevel.php', array('courseid' => $courseid, 'blockid' => $blockid));
$course_url = new moodle_url('/course/view.php', array('id' => $courseid));

// Para que sirve???
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_url($url);

// $PAGE->set_title
// $PAGE->set_heading

// Que diferencia hay con otros layouts???
$PAGE->set_pagelayout('standard'); 
// $PAGE->navbar->add


$badgelevel_form = new badgelevel_form(null, array('courseid'=>$courseid, 'blockid'=>$blockid ));


if ($badgelevel_form->is_cancelled()) {
    // return to course page
    redirect($course_url);
}


echo $OUTPUT->header();
if ($formdata = $badgelevel_form->get_data()) {
     //In this case you process validated data. $mform->get_data() returns data posted in form.
} else {
// this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
// or on the first display of the form.
    $badgelevel_form->display();
}

echo $OUTPUT->footer();
