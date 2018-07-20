<?php
 
require_once('../../config.php');
require_once('./badgelevel_form.php');

$courseid = required_param('courseid', PARAM_INT);

	
// Block instance id
$blockid = required_param('blockid', PARAM_INT);

// TODO: require that blockid is of type showgrade and is instanciated in current course

// $PAGE SETUP
$url = new moodle_url('/blocks/showgrade/badgelevel_view.php');

// Para que sirve???
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_url($url);

require_login();

// $PAGE->set_title
// $PAGE->set_heading

// Que diferencia hay con otros layouts???
$PAGE->set_pagelayout('standard'); 
// $PAGE->navbar->add

// $OUTPUT 
$badgelevel_form = new badgelevel_form(null, array('courseid'=>$courseid, 'blockid'=>$blockid ));

echo $OUTPUT->header();
//echo $output->render($badgelevel_form);

//$badgelevel_form->set_data(...);
$badgelevel_form->display();
echo $OUTPUT->footer();
