<?php

require_once("{$CFG->libdir}/formslib.php");

class badgelevel_form extends moodleform {

    function definition() {
        // TODO: show current level associations with badges. Non-editable. Only button to delete!
	
	// TODO: Add two selects, level and badge not currently used.
        $mform =& $this->_form;
	$mform->addElement('select','level','Level',array(1,2,3,4,5,6,7,8,9,10), null);
        //$mform->setDefault('course', strval($this->_customdata['courseid']));
	$mform->setType('level', PARAM_INT);
	$mform->addElement('select','badge','Badge',array('insignia1','insignia2'), null);
	$this->add_action_buttons();
    }

    function validation($data, $files) {
	// TODO: check if level-badge combination is possible. Doesn't exist yet!
        return array();
    }
}
