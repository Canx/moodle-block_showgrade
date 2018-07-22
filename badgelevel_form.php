<?php

require_once("{$CFG->libdir}/formslib.php");

class badgelevel_form extends moodleform {

    function definition() {
	$courseid = $this->_customdata['courseid'];
	$blockid = $this->_customdata['blockid'];
	$freebadges = $this->_customdata['freebadges'];
	$freelevels = $this->_customdata['freelevels'];
	$badgelevels = $this->_customdata['badgelevels'];
	$this->action = empty($_GET['action']) ? "show" : $_GET['action'];

        $mform =& $this->_form;

        // show current level associations with badges.
	$mform->addElement('header','currentlevels', 'Current levels');
        foreach($badgelevels as $level => $badge) {
		// add badge to first in array
		$currentbadges = array_merge($badge,$freebadges);
		$group = array();
		$group[0] = $mform->createElement('select',
			                          'badgelevel' . $level,
			                          'Level ' . $level,
			                          $currentbadges,
		                                  null);
		$group[1] = $mform->createElement('submit','updatebutton' . $level, 'Update', ['formaction' => '/blocks/showgrade/badgelevel.php?action=update&level=' . $level]);
		$group[2] = $mform->createElement('submit','deletebutton' . $level, 'Delete', ['formaction' => '/blocks/showgrade/badgelevel.php?action=delete&level=' . $level]);
		$mform->addElement('group', 'levelgroup' . $level, 'Level ' . $level, $group, false);

	}

	// add new level association
	$mform->addElement('header','newlevel', 'New level');
	$mform->addElement('hidden','courseid', $courseid);
	$mform->setType('courseid', PARAM_INT);
	$mform->addElement('hidden','blockid', $blockid);
	$mform->setType('blockid', PARAM_INT);

	$group = array();
        $group[0] = $mform->createElement('select','level','Level', $freelevels, null);
	$group[1] = $mform->createElement('select','badge','Badge', $freebadges, null);
	$group[2] = $mform->createElement('submit','newbutton', 'Add', ['formaction' => '/blocks/showgrade/badgelevel.php?action=add']);
	$group[3] = $mform->createElement('submit','cancelbutton', 'Cancel', ['formaction' => '/blocks/showgrade/badgelevel.php?action=cancel']);
	$mform->addElement('group', 'newlevelgroup', null, $group, false);
	$mform->setType('level', PARAM_INT);
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
