<?php

class badgelevel_db {

    private static $table = "block_showgrade_level_badge";

    function __construct(int $courseid, int $blockid) {
        $this->courseid = $courseid;
	$this->blockid = $blockid;
    }

    function get_freebadges() {
        global $DB;

	$sql = "SELECT * FROM {badge} WHERE id NOT IN" .
	       " (SELECT badge_id FROM {" . self::$table . "} WHERE block_id = ?) AND courseid = ?";
        $rs = $DB->get_records_sql($sql, array($this->blockid, $this->courseid)); 

	$badges = array();
	foreach($rs as $record) {
            $badges[$record->id] = $record->name;
	}

	return $badges;
    }
    
    function get_freelevels($max_level) {
       $levels = array();
       for($level=1; $level <= $max_level; $level++) {
           $levels[$level] = 'Level ' . $level;
       }

       // TODO: Remove existing levels!

       return $levels;
    }
    
    function get_badgelevels() {
       global $DB;

       $sql = "SELECT * FROM ({" . self::$table . "}" .
	       " AS lb INNER JOIN {badge} AS badge ON lb.badge_id = badge.id)" .
	       " WHERE lb.block_id = ? AND badge.courseid = ? ORDER BY level";
       $rs = $DB->get_records_sql($sql, array($this->blockid, $this->courseid));
    
       $badgelevels = array();
       foreach ($rs as $record) {
	   $badgelevels[$record->level] = [ $record->badge_id => $record->name ];
       }

       return $badgelevels;
    }
    
    // Update level-badge association
    function update($level, $badge) {
       global $DB;

       $sql = "UPDATE {" . self::$table . "}" .
	      " SET badge_id = ?" .
	      " WHERE level = ?";

       $DB->execute($sql, array($badge, $level));	
    }
    
    // Delete level-badge association
    function delete($level) {
       global $DB;

       $DB->delete_records(self::$table, array('level' => $level));
    }
    
    // Add level-badge association
    function add($level, $badge) {
       global $DB;

       $record = new StdClass();
       $record->level = $level;
       $record->badge_id = $badge;
       $record->block_id = $this->blockid;
       
       // deleting previous records if exist!
       $DB->delete_records(self::$table, array('level' => $level));
       $DB->insert_record(self::$table, $record);
    }

}
