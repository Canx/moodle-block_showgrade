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
            if (isset($this->config->category)) {
                if (empty($this->config->title)) {
                    // TODO: change call to make it shorter.
                    $this->title = $this->helper->get_category()->fullname;
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

    public function get_content() {
        global $CFG, $OUTPUT, $COURSE, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        // Not sure why I'm doing this...
        if (empty($this->page)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text = '';

        if ($this->config == null) {
            return $this->content;
        }

        // Only show level if current user is enrolled as student!
        $context = context_course::instance($COURSE->id);
        $isstudent = current(get_user_roles($context, $USER->id))->shortname == 'student' ? true : false;

        if ($isstudent) {
            $this->content->text = $this->content_student();

        } else {
            $this->content->text = $this->content_admin();
            $this->content->footer = $this->content_footer_admin();
        }

        return $this->content;
    }

    public function content_admin() {
        $html = '';
        $html .= '<h1>Showgrade block</h1>';
        $html .= "<p>Maximum points:{$this->helper->get_maxpoints()}</p>";

        if (property_exists($this->config, 'enablelevels')) {
            if ($this->config->enablelevels == true) {
                $html .= "<p>Points per level:{$this->helper->config->pointslevel}</p>";
                $html .= "<p>Maximum level:{$this->helper->get_max_level()}</p>";

            }
        }
        return $html;
    }

    public function content_footer_admin() {
        global $COURSE;
        if (property_exists($this->helper->config, 'enablelevels')) {
            if ($this->helper->config->enablelevels == true) {
                $url = new moodle_url('/local/badgelevel/index.php',
                   array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
                return html_writer::link($url, get_string('config_badges', 'block_showgrade'));
            }
        }
    }

    public function content_student() {
        global $USER, $COURSE;
        $html = '';

        // TODO: this should not be done here!
        $this->issue_badge($USER->id, $this->helper->get_level(), $COURSE->id, $this->instance->id);

        // TODO: improve visuals
        $html .= $this->get_html_level('h4');
        $html .= $this->get_html_pointsnextlevel('p');
        $html .= $this->get_html_points('p');
        $html .= $this->get_html_completed('p');

        return $html;
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

    private function get_html_level($tag) {
        $html = '';

        if (property_exists($this->helper->config, 'enablelevels')) {
            if ($this->helper->config->enablelevels == true) {
                $content = get_string('level', 'block_showgrade') . ' ' . $this->helper->get_level();
                $content .= ' / ' . $this->helper->get_maxlevel();
                $html = html_writer::tag($tag, $content);
            }
        }

        return $html;
    }

    private function get_html_pointsnextlevel($tag) {
        $html = '';

        if ($this->helper->config->enablelevels == true) {
            $html = html_writer::tag($tag, $this->helper->get_formatted_nextlevel());
        }

        return $html;
    }

    private function get_html_points($tag) {
        $html = '';

        $content = "Points: " . $this->helper->get_points();

        if (property_exists($this->helper->config, 'enablemaxpoints')) {
            if ($this->helper->config->enablemaxpoints == true) {
                $content .= '/' . $this->helper->get_maxpoints();
            }
        }
        $html = html_writer::tag($tag, $content);

        return $html;
    }

    private function get_html_completed($tag) {
        $html = '';

        if (property_exists($this->helper->config, 'enablecompletion')) {
            if ($this->helper->config->enablecompletion == true) {
                $html = html_writer::tag($tag, $this->helper->get_completed_percent());
            }
        }

        return $html;
    }

    private function issue_badge($user, $level, $courseid, $blockid) {
        if ($this->helper->config->enablelevels == true) {
            showgrade_helper::trigger_levelup_event_if_needed($user, $level, $courseid, $blockid);
        }
    }

}
