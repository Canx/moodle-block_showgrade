<?php

require_once("{$CFG->libdir}/formslib.php");
require_once('./badgelevel_db.php');

class badgelevel_form extends moodleform {

    function __construct($db) {
        parent::__construct(null, array('db' => $db));
    }
   
    function definition() {
	$this->db = $this->_customdata['db'];
	$courseid = $this->db->courseid;
	$blockid = $this->db->blockid;
	$freebadges = $this->db->get_freebadges();
	$freelevels = $this->db->get_freelevels(10);
	$badgelevels = $this->db->get_badgelevels();
	$this->action = empty($_GET['action']) ? "show" : $_GET['action'];
	$this->level = empty($_GET['level']) ? null : $_GET['level'];

        $mform =& $this->_form;

	// save courseid and blockid params
        $mform->addElement('hidden','courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden','blockid', $blockid);
        $mform->setType('blockid', PARAM_INT);

        // show current level associations with badges.
	$mform->addElement('header','currentlevels', 'Current badges');
        foreach($badgelevels as $level => $badge) {
		// add badge to first in array
		$currentbadges = $badge + $freebadges;
		$group = array();
		$group[0] = $mform->createElement('select',
			                          'badge' . $level,
			                          'Level ' . $level,
			                          $currentbadges,
		                                  null);
		$group[1] = $mform->createElement('submit','updatebutton' . $level, 'Update', ['formaction' => '/blocks/showgrade/badgelevel.php?action=update&level=' . $level]);
		$group[2] = $mform->createElement('submit','deletebutton' . $level, 'Delete', ['formaction' => '/blocks/showgrade/badgelevel.php?action=delete&level=' . $level]);
		$mform->addElement('group', 'level' . $level, 'Level ' . $level, $group, false);

	}

        
	// add new level association only if available badges and levels
	if ($freebadges && $freelevels) {
	    $mform->addElement('header','newlevelheader', 'Link badge to level');
	    $group = array();
            $group[0] = $mform->createElement('select','newlevel','Level', $freelevels, null);
	    $group[1] = $mform->createElement('select','newbadge','Badge', $freebadges, null);
	    $group[2] = $mform->createElement('submit','newbutton', 'Add', ['formaction' => '/blocks/showgrade/badgelevel.php?action=add']);
	    $group[3] = $mform->createElement('submit','cancelbutton', 'Cancel', ['formaction' => '/blocks/showgrade/badgelevel.php?action=cancel']);
	    $mform->addElement('group', 'newlevelgroup', null, $group, false);
	    $mform->setType('level', PARAM_INT);
	}

	// TODO: Add link to add badges
	$mform->addElement('header','badgeheader', 'Badges');
	$mform->addElement('static','badgelink', '', '<a href="/badges/index.php?type=2&id=' . $courseid . '">Add badge</a>');
    }

    public function update() {
	$level    = $this->level;
	$formdata = get_object_vars($this->get_data());
	$badge = $formdata['level' . $level]['badge' . $level];
        $this->db->update($level, $badge);
    }

    public function delete() {
	$level = $this->level;
        $this->db->delete($level);
    }

    public function add() {
	$level    = $this->level;
	$formdata = get_object_vars($this->get_data());
	$level = $formdata['newlevelgroup']['newlevel'];
	$badge = $formdata['newlevelgroup']['newbadge'];
        $this->db->add($level, $badge);
    }
}

    //function validation($data, $files) {
	// TODO: check if level-badge combination is possible. Doesn't exist yet!
    //º    return array();
    //}
