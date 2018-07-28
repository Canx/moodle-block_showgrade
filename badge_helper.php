<?php
global $CFG;

require_once($CFG->libdir . '/badgeslib.php');

class badge_helper {

    // TODO: redundant in badgelevel_db
    private static $table = "block_showgrade_level_badge";

    public static function check_and_issue_badge($user, $level, $course, $block) {
	global $DB;

	$sql = "SELECT id FROM" .
	       " (SELECT b.id FROM {badge} b" .
	       " INNER JOIN {" . self::$table . "} AS lb" .
	       " ON b.id = lb.badge_id WHERE lb.level <= ? AND lb.block_id = ? AND b.courseid = ?) AS b " .
	       "WHERE id NOT IN" .
	       " (SELECT badgeid FROM {badge_issued} WHERE userid = ?)";

	$rs = $DB->get_records_sql($sql, array($level, $block, $course, $user));

	foreach($rs as $record) {
            $badge = new badge($record->id);
	    $badge->issue($user);
	}

	$rs->close();
    }

}
