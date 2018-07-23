<?php

class badgelevel_db {

    function __construct(int $courseid, int $blockid) {
        $this->courseid = $courseid;
	$this->blockid = $blockid;
    }

    function get_freebadges() {
       return array(1=>'Insignia de platino',2=>'Insignia de madera');
    }
    
    function get_freelevels() {
       return array(1=>'Level 1',2=>'Level 2',3=>'Level 3',4=>'Level 4');
    }
    
    function get_badgelevels() {
       // table mdl_block_showgrade_level_badge: id, block_id, badge_id, level
       // table mdlm_badge: id, name, description
       // table mdlm_block: id, name
    
        return [1 => [18 => "Insignia de bronce"],
    	    5 => [19 => "Insignia de plata"],
    	    10 => [20 => "Insignia de oro"]
    	];
    }
    
    // Update level-badge association
    function update($level, $badge) {
       debugging("update: " . $level . "," . $badge); 
    }
    
    // Delete level-badge association
    function delete($level) {
       debugging("delete: " . $level);
    }
    
    // Add level-badge association
    function add($level, $badge) {
       debugging("add: " . $level . "," . $badge); 
    }

}
