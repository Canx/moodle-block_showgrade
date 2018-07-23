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

$db = new badgelevel_db($courseid, $blockid);

$form = new badgelevel_form($db);

switch ($form->action) {
    case 'update': 
	$form->update();
	redirect($url);
	break;
    case 'delete': 
	$form->delete();
	redirect($url);
	break;
    case 'add': 
	$form->add();
        redirect($url);
	break;
    case 'cancel': 
        redirect($course_url);
	break;
    default:
        echo $OUTPUT->header();
        $form->display();
        echo $OUTPUT->footer();
}
