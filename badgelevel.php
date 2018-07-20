<?php
 
require_once('../../config.php');
require_once('./badgelevel_form.php');

function get_freebadges() {
   return array(1=>'insignia1',2=>'insignia2');
}

function get_freelevels() {
   return array(1=>'Level 1',2=>'Level 2',3=>'Level 3',4=>'Level 4');
}

function get_badgelevels() {
   // TODO: think how we want the association to be structured
}
	
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

$params = array('courseid'=>$courseid,
        	'blockid'=>$blockid,
		'freebadges'=> get_freebadges(),
		'freelevels' => get_freelevels(),
		'badgelevels' => get_badgelevels()
		);

$badgelevel_form = new badgelevel_form(null, $params);


if ($badgelevel_form->is_cancelled()) {
    // return to course page
    redirect($course_url);
}


echo $OUTPUT->header();
if ($formdata = $badgelevel_form->get_data()) {
    // TODO: not only creating new associations, also deleting associations can be possible!
    debugging(var_dump($formdata));
} else {
// this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
// or on the first display of the form.
    $badgelevel_form->display();
}

echo $OUTPUT->footer();
