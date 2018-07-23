<?php

class badgelevel_db {

    private static $table = "block_showgrade_level_badge";

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
       global $DB;

       $sql = "SELECT * FROM ({" . self::$table . "}" .
	       " AS lb INNER JOIN {badge} AS badge ON lb.badge_id = badge.id)" .
	       " WHERE lb.block_id = ?";
       $rs = $DB->get_records_sql($sql, array($this->blockid, $this->courseid));
    
       debugging($rs);
       $badgelevels = array();
       foreach ($rs as $record) {
	   $badgelevels[] = 1;
           //$badgelevels[] = array($rs["level"] => array($rs["badge.id"] => $rs->["badge.name"]));
       }

       return $badgelevels;
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
       global $DB;

       //$DB->insert_record($this->table,
       debugging("add: " . $level . "," . $badge); 
    }

}
