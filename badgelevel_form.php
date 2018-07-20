<?php

require_once("{$CFG->libdir}/formslib.php");

class badgelevel_form extends moodleform {

    public static function get_badges(int $courseid) {
        return array(1=>'insignia1',2=>'insignia2');
    }

    public static function get_levels(int $blockid) {
        return array(1=>'Level 1',2=>'Level 2',3=>'Level 3',4=>'Level 4');
    }

    function definition() {
	$courseid = $this->_customdata['courseid'];
	$blockid = $this->_customdata['blockid'];

        // TODO: show current level associations with badges. Non-editable. Only button to delete!
	$badges = badgelevel_form::get_badges($courseid);
	$levels = badgelevel_form::get_levels($blockid);

	// TODO: Add two selects, level and badge not currently used.
        $mform =& $this->_form;

	$mform->addElement('hidden','courseid', $courseid);
	$mform->setType('courseid', PARAM_INT);
	$mform->addElement('hidden','blockid', $blockid);
	$mform->setType('blockid', PARAM_INT);

	$mform->addElement('select','level','Level', $levels, null);
	$mform->setType('level', PARAM_INT);
	$mform->addElement('select','badge','Badge', $badges, null);
	$this->add_action_buttons();
    }

    public function definition_after_data() {
        $mform = $this->_form;
        if ($mform->isSubmitted()) {
            $level = $mform->getElement('level')->getValue();
	    $badge = $mform->getElement('badge')->getValue();
	    $course = $mform->getElement('courseid')->getValue();
	    $block = $mform->getElement('blockid')->getValue();
	    debugging($level[0] . ":" . $badge[0] . ":" . $course . ":" . $block);
           // Do whatever checking you need
        }
}

    function validation($data, $files) {
	// TODO: check if level-badge combination is possible. Doesn't exist yet!
        return array();
    }

}
