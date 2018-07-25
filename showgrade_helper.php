<?php

require_once($CFG->libdir . '/gradelib.php');

class showgrade_helper {

    public function __construct($config) {
	$this->config = $config;
        $this->category = null;
	$this->grade = null;
    }

    public function get_category() {
        if ($this->category == null && $this->config->category !== null) {
            $this->category = grade_category::fetch(array('id'=> $this->config->category));
        }
        return $this->category;
    }

    function get_grade() {
        global $DB, $USER;
        if ($this->grade == null && $this->config->category !== null) {
            $category = $this->get_category();
            if ($category) { 
                $this->grade = $DB->get_record('grade_grades',
                        array('itemid'=> $category->get_grade_item()->id,
                              'userid'=> $USER->id));
            } else {
                $this->grade = null;
            }
        }
        return $this->grade;
    }

    function get_finalgrade() {
        if ($this->get_grade() != null) {
            return $this->get_grade()->finalgrade;
        }
        else {
            return 0;
        }
    }

    function get_level() {
        return number_format(floor($this->get_finalgrade() / $this->config->pointslevel), 0);
    }

    function get_formatted_level() {
        return get_string('level', 'block_showgrade') . ' ' . $this->get_level();
    }

    function get_formatted_grade() {
        if ($this->get_grade() == null) {
            return "-";
        }
        return number_format($this->get_finalgrade(), 0) . ' points';
    }

    function get_points_nextlevel() {
        return number_format($this->config->pointslevel * ($this->get_level() + 1) - $this->get_finalgrade(), 0);
    }

    function get_formatted_nextlevel() {
        return $this->get_points_nextlevel() . ' ' . get_string('pointslevelup', 'block_showgrade');
    }

    function percent($number){
        return number_format($number, 2) * 100 . '%';
    }

    function get_completed_percent() {
        return $this->percent($this->get_finalgrade() / $this->get_maxpoints()) . ' ' . get_string('completed', 'block_showgrade');
    }

    function get_maxpoints() {
	return $this->category->get_grade_item()->grademax;
    }

    function get_formatted_maxpoints() {
        return get_string('of','block_showgrade')
                . ' ' . number_format($this->get_maxpoints(),0)
                . ' ' . get_string('possiblepoints', 'block_showgrade');
    }

    function get_max_level() {
        return number_format(floor($this->get_maxpoints() / $this->config->pointslevel), 0);
    }

}
