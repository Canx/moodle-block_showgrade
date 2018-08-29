<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/gradelib.php');
//require_once('classes/event/user_leveledup.php');

class showgrade_helper {

    public function __construct($config) {
        $this->config = $config;
        $this->categories = null;
        $this->grade = null;
    }

    public function get_categories() {
        if ($this->categories == null && $this->config->categories !== null) {
            foreach($this->config->categories as $category) {
                if ($this->categories == null) {
                    $this->categories = [];
                }
                $this->categories[$category] = grade_category::fetch(array('id' => $category));
            }
        }
        return $this->categories;
    }

    public function get_grade() {
        global $DB, $USER;
        if ($this->grade == null && $this->config->categories !== null) {
            $categories = $this->get_categories();

            $this->grade = 0;
            foreach($categories as $category) {
                $grade = 0;
                $grade = $DB->get_record('grade_grades',
                            array('itemid' => $category->get_grade_item()->id,
                                  'userid' => $USER->id));
                $this->grade += $grade->finalgrade;
            }
        }
        return $this->grade;
    }

    public function get_level() {
        return number_format(floor($this->get_grade() / $this->config->pointslevel), 0);
    }

    public function get_maxlevel() {
        return number_format(floor($this->get_maxpoints() / $this->config->pointslevel), 0);
    }

    public function get_points() {
        if ($this->get_grade() == null) {
            return "-";
        }
        return number_format($this->get_grade(), 0);
    }

    public function get_points_nextlevel() {
        return number_format($this->config->pointslevel * ($this->get_level() + 1) - $this->get_grade(), 0);
    }

    public function get_formatted_nextlevel() {
        if ($this->get_level() == $this->get_maxlevel()) {
            return "Maximum level reached!";
        }

        return $this->get_points_nextlevel() . ' ' . get_string('pointslevelup', 'block_showgrade');
    }

    public function percent($number) {
        return number_format($number, 2) * 100 . '%';
    }

    public function get_completed_percent() {
        return $this->percent($this->get_grade() / $this->get_maxpoints()) . ' ' . get_string('completed', 'block_showgrade');
    }

    public function get_maxpoints() {
        $max = 0;
        foreach ($this->get_categories() as $category) {
            $max += $category->get_grade_item()->grademax;
        }
        return number_format($max, 0);
    }

    public function get_max_level() {
        return number_format(floor($this->get_maxpoints() / $this->config->pointslevel), 0);
    }

    public static function trigger_levelup_event_if_needed($user, $level, $courseid, $blockid) {
        $params = array(
            'context' => context_block::instance($blockid),
            'userid' => $user,
            'courseid' => $courseid,
            'other' => array('level' => $level,
                             'blockid' => $blockid));

        // TODO: only call this if level increment!
        $lupevent = \block_showgrade\event\user_leveledup::create($params);
        $lupevent->trigger();
    }
}
