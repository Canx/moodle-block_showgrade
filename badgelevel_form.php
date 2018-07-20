<?php

require_once("{$CFG->libdir}/formslib.php");

class badgelevel_form extends moodleform {

    function definition() {
	$courseid = $this->_customdata['courseid'];
	$blockid = $this->_customdata['blockid'];
	$freebadges = $this->_customdata['freebadges'];
	$freelevels = $this->_customdata['freelevels'];
	$badgelevels = $this->_customdata['badgelevels'];

        // TODO: show current level associations with badges. Non-editable. Only button to delete!

	// TODO: Add two selects, level and badge not currently used.
        $mform =& $this->_form;

	$mform->addElement('hidden','courseid', $courseid);
	$mform->setType('courseid', PARAM_INT);
	$mform->addElement('hidden','blockid', $blockid);
	$mform->setType('blockid', PARAM_INT);

	$mform->addElement('select','level','Level', $freelevels, null);
	$mform->setType('level', PARAM_INT);
	$mform->addElement('select','badge','Badge', $freebadges, null);
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
