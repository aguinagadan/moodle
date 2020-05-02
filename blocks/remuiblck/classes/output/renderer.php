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

namespace block_remuiblck\output;

defined('MOODLE_INTERNAL') or die;

/**
 * @package block_remuiblck
 * @author  2019 WisdmLabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \plugin_renderer_base {

    use mainsection_renderer;
    use courseprogress_renderer;
    use userstats_renderer;
    use enrolledusers_renderer;
    use quizattempts_renderer;
    use courseanlytics_renderer;
    use latestmembers_renderer;
    use addnotes_renderer;
    use recentfeedback_renderer;
    use recentforums_renderer;
    use managecourses_renderer;
    use coursereport_renderer;
    use scheduletask_renderer;
}
