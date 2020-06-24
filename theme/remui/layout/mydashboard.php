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
 * A two column layout for the remui theme.
 *
 * @package   theme_remui
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once('common.php');

use block_xp\local\xp\level_with_name;
use block_xp\local\xp\level_with_badge;
use core_completion\progress;

global $USER;

function getLevelPropertyValue($level, $property) {
	$returnedValue = '';

	switch($property) {
		case 'name':
			$name = $level instanceof level_with_name ? $level->get_name() : null;
			if (empty($name)) {
				$name = get_string('levelx', 'block_xp', $level->get_level());
			}
		$returnedValue = $name;
		break;
	}
	return $returnedValue;
}

function getLevelBadge($level) {
	$levelnum = $level->get_level();
	$classes = 'block_xp-level level-' . $levelnum;
	$label = get_string('levelx', 'block_xp', $levelnum);
	$classes .= ' d-badge';

	$html = '';
	if ($level instanceof level_with_badge && ($badgeurl = $level->get_badge_url()) !== null) {
		$html .= html_writer::tag(
			'div',
			html_writer::empty_tag('img', ['src' => $badgeurl,
				'alt' => $label, 'class'=> 'd-badge-img']),
			['class' => $classes . ' level-badge']
		);
	} else {
		$html .= html_writer::tag('div', $levelnum, ['class' => $classes, 'aria-label' => $label]);
	}
	return $html;
}

/**
 * Format an amount of XP.
 *
 * @param int $amount The XP.
 * @return string
 */
function xp($amount) {
	$xp = (int) $amount;
	if ($xp > 999) {
		$thousandssep = get_string('thousandssep', 'langconfig');
		$xp = number_format($xp, 0, '.', $thousandssep);
	}
	$o = '';
	$o .= html_writer::start_div('block_xp-xp');
	$o .= html_writer::div($xp, 'pts');
	$o .= html_writer::div('xp', 'sign sign-sup');
	$o .= html_writer::end_div();
	return $o;
}

/**
 * Returns the progress bar rendered.
 *
 * @param state $state The renderable object.
 * @param bool $showpercentagetogo Show the percentage to go.
 * @return string HTML produced.
 */
function getProgressBar($state) {
	$classes = ['block_xp-level-progress', 'd-level-progress'];
	$pc = $state->get_ratio_in_level() * 100;
	if ($pc != 0) {
		$classes[] = 'progress-non-zero';
	}

	$html = '';

	$html .= html_writer::start_tag('div', ['class' => implode(' ', $classes)]);

	$html .= html_writer::start_tag('div', ['class' => 'xp-bar-wrapper d-progress-bar-level', 'role' => 'progressbar',
		'aria-valuenow' => round($pc, 1), 'aria-valuemin' => 0, 'aria-valuemax' => 100]);
	$html .= html_writer::tag('div', '', ['style' => "width: {$pc}%;", 'class' => 'xp-bar d-xp-bar']);
	$html .= html_writer::end_tag('div');
	$html .= html_writer::end_tag('div');
	return $html;
}

function convertDateToSpanish($timestamp) {
	setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');
	return strftime("%d de %B de %Y", $timestamp);
}

function getCategoryById($catId) {
	global $DB;
	return $DB->get_record('course_categories',array('id'=>$catId));
}

function getProgressBarDetail($value) {
	return '<div class="block_xp-level-progress progress-non-zero" style="padding-top:0.75%;">
						<div class="xp-bar-wrapper d-progress-bar-level-course" role="progressbar" aria-valuenow="'. $value .'" aria-valuemin="0" aria-valuemax="100">
							<div style="width: ' . $value .'%;" class="xp-bar d-xp-bar-course"></div>
						</div>
					</div>';
}

function getDaysLeftPercentage($startDate, $endDate) {
	$totalDays = intval(floor(($endDate-$startDate)/86400));
	$passedDays = intval(floor((strtotime(date('c')) - $startDate)/86400));
	return ($passedDays/$totalDays) * 100;
}

function getDaysLeft($startDate, $endDate) {
	$totalDays = intval(floor(($endDate-$startDate)/86400));
	$passedDays = intval(floor((strtotime(date('c')) - $startDate)/86400));
	return $totalDays - $passedDays;
}

function getCoursesHtml($course) {
	$html = '';

	if(!empty($course)) {
		$categoryId = $course->category;
		$courseObj = get_course($course->id);
		$coursePercentage = $course->percentage;
		$daysLeft = getDaysLeft($course->startdate, $course->enddate);
		$daysLeftPercentage = getDaysLeftPercentage($course->startdate, $course->enddate);
		$usersCompletedPercentage = ($course->studentscompleted/getEnrolledUsers($course))*100;

		$html.= '<div class="d-course-row row" style="margin-left: 0; padding-bottom: 5%">
							<div><img height="84" width="149" src="'. \theme_remui\utility::get_course_image($courseObj) .'" style="border-radius: 4px;"></div>
							<div class="d-course-detail-row-2">
									<div class="text-left" style="font-size: 13px; color: #A3AFB7">'. getCategoryById($categoryId)->name .'</div>
									<div class="text-left" style="font-size: 17px;height: 50%;font-weight: 500;display: table-cell;padding-bottom: 5%;">'. $course->fullname .'</div>
									<div class="text-left" style="font-size: 13px; color: #A3AFB7">Lanzamiento: '. convertDateToSpanish($course->startdate) .'</div>
							</div>
							<div class="d-course-detail-row-3">
									<div class="row d-course-detail-row-3-complete">
											<div class="d-course-detail-row-3-label">Completaste el '.$coursePercentage.'% del curso</div>';
				 $html.= getProgressBarDetail($coursePercentage);
				 $html.= '</div>';

				 if(isset($course->enddate) && !empty($course->enddate)) {
					 $html.=	'<div class="row d-course-detail-row-3-complete">
											<div class="d-course-detail-row-3-label">El curso cierra en '. $daysLeft .' días</div>';
					 $html.= getProgressBarDetail($daysLeftPercentage);
					 $html.= '</div>';
				 } else {
					 $html.= '<div class="row d-course-detail-row-3-complete">
											<div class="d-course-detail-row-3-label">-</div>';
					 $html.= '</div>';
				 }

				 $html.= '<div class="row d-course-detail-row-3-complete">
											<div class="d-course-detail-row-3-label">'. $course->studentscompleted .' han completado el curso</div>';
				 $html.= getProgressBarDetail($usersCompletedPercentage);
				 $html.= '</div>
							</div>
					</div>';
	} else {
		$html = '<div>No existen cursos</div>';
	}

	return $html;
}

function getEnrolledUsers($course) {
	global $DB;
	$stats = array();
	$enrolledusers = $DB->get_records_sql(
		"SELECT u.*
               FROM {course} c
               JOIN {context} ctx ON c.id = ctx.instanceid AND ctx.contextlevel = ?
               JOIN {enrol} e ON c.id = e.courseid
               JOIN {user_enrolments} ue ON e.id = ue.enrolid
               JOIN {user} u ON ue.userid = u.id
               JOIN {role_assignments} ra ON ctx.id = ra.contextid AND u.id = ra.userid AND ra.roleid = ?
              WHERE c.id = ?",
		array(CONTEXT_COURSE, 5, $course->id)
	);
	return count($enrolledusers);
}

function getCourseStats($course) {
	global $DB;
	$stats = array();
	$enrolledusers = $DB->get_records_sql(
		"SELECT u.*
               FROM {course} c
               JOIN {context} ctx ON c.id = ctx.instanceid AND ctx.contextlevel = ?
               JOIN {enrol} e ON c.id = e.courseid
               JOIN {user_enrolments} ue ON e.id = ue.enrolid
               JOIN {user} u ON ue.userid = u.id
               JOIN {role_assignments} ra ON ctx.id = ra.contextid AND u.id = ra.userid AND ra.roleid = ?
              WHERE c.id = ?",
		array(CONTEXT_COURSE, 5, $course->id)
	);
	$stats['enrolledusers'] = count($enrolledusers);

	$completion = new \completion_info($course);
	if ($completion->is_enabled()) {
		$inprogress = 0;
		$studentcompleted = 0;
		$yettostart = 0;
		$modules = $completion->get_activities();
		foreach ($enrolledusers as $user) {
			$activitiesprogress = 0;
			foreach ($modules as $module) {
				$moduledata = $completion->get_data($module, false, $user->id);
				$activitiesprogress += $moduledata->completionstate == COMPLETION_INCOMPLETE ? 0 : 1;
			}
			if ($activitiesprogress == 0) {
				$yettostart++;
			} else if ($activitiesprogress == count($modules)) {
				$studentcompleted++;
			} else {
				$inprogress++;
			}
		}
		$stats['nocompletion'] = false;
		$stats['studentcompleted'] = $studentcompleted;
		$stats['inprogress'] = $inprogress;
		$stats['yettostart'] = $yettostart;
	} else {
		$stats['nocompletion'] = true;
	}
	return $studentcompleted;
}

$xpRenderer = \block_xp\di::get('renderer');
$world = \block_xp\di::get('course_world_factory')->get_world($this->page->course->id);
$state = $world->get_store()->get_state($USER->id);
$widget = new \block_xp\output\xp_widget($state, [], null, []);
$level = $widget->state->get_level();

//Get data
$levelName = getLevelPropertyValue($level, 'name');
$xp = $widget->state->get_xp();
$levelBadge = getLevelBadge($level);
$progressBar = getProgressBar($widget->state);

$courses = enrol_get_all_users_courses($USER->id, true);

require_once("{$CFG->libdir}/completionlib.php");
$course = new stdClass();
$completedCourses = 0;
$coursesHtml = '';

foreach($courses as $key=>$c) {
	$iscomplete = 0;
	$course = get_course($c->id);
	$cinfo = new completion_info($course);
	$percentage = progress::get_course_progress_percentage($course, $USER->id);
	$studentsCompleted = getCourseStats($c);

	if($percentage == 100) {
		$completedCourses++;
		$iscomplete = 1;
	}

	$course->percentage = $percentage;
	$course->iscomplete = $iscomplete;
	$course->studentscompleted = $studentsCompleted;

	$courses[$key]->percentage = $percentage;
	$courses[$key]->iscomplete = $iscomplete;
	$courses[$key]->studentscompleted = $studentsCompleted;

	$coursesHtml .= getCoursesHtml($course);
}

$totalCourses = count($courses);
$pendingCourses = $totalCourses - $completedCourses;

$templatecontextDashboard = [
	//samuel - pendiente al cambiar a produccion
	'URL' => $CFG->wwwroot . '/pluginfile.php/1/theme_remui/staticimage/1592534572/catalogo-cursos.titulo.png',
	'username' => $USER->firstname . ' ' . $USER->lastname,
	'levelname' => $levelName,
	'points' => $xp,
	'levelbadge' => $levelBadge,
	'progressbar' => $progressBar,
	'totalcourses' => $totalCourses,
	'completedcourses' => $completedCourses,
	'pendingcourses' => $pendingCourses,
	'courseshtml' => $coursesHtml
];

$templatecontext = array_merge($templatecontext, $templatecontextDashboard);

echo $OUTPUT->render_from_template('theme_remui/mydashboard', $templatecontext);

