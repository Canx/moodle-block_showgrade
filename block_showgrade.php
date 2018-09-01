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

/**
 * Showgrade block
 *
 * @package    block_showgrade
 * @copyright  Ruben Cancho <canchete@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once('showgrade_helper.php');

class block_showgrade extends block_base {

    public function init() {
        $this->title = '';
    }

    public function specialization() {
        $this->helper = new showgrade_helper($this->config);

        // TODO: externalize to helper method.
        if (isset($this->config)) {
            // TODO apply null pattern.
            if (isset($this->config->categories)) {
                if (empty($this->config->title)) {
                    // TODO: problem when more than 1 category selected!
                    $this->title = current($this->helper->get_categories())->fullname;
                    if ($this->title == "?") {
                        $this->title = get_string('coursetotal', 'block_showgrade');
                    }
                } else {
                    $this->title = $this->config->title;
                }
            }
        } else {
            $this->title = get_string('defaulttitle', 'block_showgrade');
        }
    }

    public function applicable_formats() {
        return array('all' => false,
                     'site' => false,
                     'site-index' => false,
                     'course-view' => true,
                     'course-view-social' => false,
                     'mod' => true,
                     'mod-quiz' => false);
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function has_config() {
        return true;
    }

    public function cron() {
        mtrace( "Hey, my cron script is running" );
        return true;
    }

    public function get_content() {
        global $CFG, $OUTPUT, $COURSE, $USER, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }

        // Not sure why I'm doing this...
        if (empty($this->page)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();

        if ($this->config == null) {
            return $this->content;
        }

        // Only show level if current user is enrolled as student!
        $context = context_course::instance($COURSE->id);
        $isstudent = current(get_user_roles($context, $USER->id))->shortname == 'student' ? true : false;

        // issue badge to student if needed
        if ($isstudent) {
            $this->issue_badge($USER->id, $this->helper->get_level(), $COURSE->id, $this->instance->id);
        }

        // render widget
        $renderer = $PAGE->get_renderer('block_showgrade');
        $this->content->text = $renderer->render_widget($isstudent, $this->helper);
        return $this->content;
    }

    private function issue_badge($user, $level, $courseid, $blockid) {
        if ($this->helper->config->enablelevels == true) {
            showgrade_helper::trigger_levelup_event_if_needed($user, $level, $courseid, $blockid);
        }
    }

}
