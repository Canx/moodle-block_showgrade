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

class block_showgrade_renderer extends plugin_renderer_base {

    public function render_widget($isstudent, $helper) {
        $this->helper = $helper;

        if ($isstudent) { 
           $html = $this->content_student(); 
        } else { 
           $html = $this->content_admin(); 
        }

        return $html;
    }

    private function content_admin() {
        $html = "<p>Maximum points:{$this->helper->get_maxpoints()}</p>";

        if (property_exists($this->helper->config, 'enablelevels')) {
            if ($this->helper->config->enablelevels == true) {
                $html .= "<p>Points per level:{$this->helper->config->pointslevel}</p>";
                $html .= "<p>Maximum level:{$this->helper->get_max_level()}</p>";

            }
        }
        return $html;
    }
    
    public function content_student() {
        global $USER, $COURSE;
        $html = '';

        // TODO: improve visuals
        $html .= $this->get_html_level('h4');
        $html .= $this->get_html_pointsnextlevel('p');
        $html .= $this->get_html_points('p');
        $html .= $this->get_html_completed('p');

        return $html;
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


}
