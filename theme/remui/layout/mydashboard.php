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

use moodle_url;
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
			['class' => $classes . ' level-badge', 'style' => 'height: 75px;']
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
	if($pc == 0) {
		$xpBar = 'd-xp-bar-0';
	} else {
		$xpBar = 'd-xp-bar';
	}

	$html .= html_writer::tag('div', '', ['style' => "width: {$pc}%;", 'class' => 'xp-bar '.$xpBar]);
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
	$progressHTML = '
			<div class="d-progress d-total" data-value='.$value.'>
				<span class="d-progress-left">
					<span class="d-progress-bar border-primary-green"></span>
				</span>
				<span class="d-progress-right">
					<span class="d-progress-bar border-primary-green"></span>
				</span>
				<div class="w-100 h-100 rounded-circle d-flex align-items-center justify-content-center"></div>
			</div>';

	return $progressHTML;
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

function progressBarHTML($course) {
	global $USER;
	$div = '<div style="height: 15px; background-color: white;"></div>';

	$percentage = progress::get_course_progress_percentage($course, $USER->id);

	if($percentage === 0) {
		$div = '<div class="progress progress-square mb-0">
									<div class="progress-bar bg-red-600-cc" style="height: 100%; width: 100%; background-color: #FF644C !important;" role="progressbar">
											<span>' . $percentage . '%' . '</span>
									</div>
							</div>';
	} elseif($percentage > 0) {
		$percentage = round($percentage);
		$div = '<div class="progress progress-square mb-0">
									<div class="progress-bar bg-green-600-cc" style="width: ' . $percentage . '%; height: 100%;" role="progressbar">
											<span>' . $percentage . '%' . '</span>
									</div>
							</div>';
	}
	return $div;
}

function getPendingCoursesHtml($courses) {
	global $USER;
	$coursesHtml = '';
	$totalPending = 0;

	foreach($courses as $key=>$c) {
		$course = get_course($c->id);

		$percentage = progress::get_course_progress_percentage($course, $USER->id);
		if($percentage == 100) {
			continue;
		}

		$content = '<div class="cc-courses-info">
										<div class="cc-category-box dd-category-box-secundary">
										<h3 class="cc-h3-courses-info cc-ultimos-image" style="background: url('. \theme_remui\utility::get_course_image($c) .');"></h3>
										<div class="cc-courses-div-container cc-ultimos-desc"> '. progressBarHTML($c) .'
											<div class="text-left" style="font-size: 12px; color: #A3AFB7; padding: 2% 4% 0 7%;">'. getCategoryById($c->id)->name .'</div>
											<div class="dd-courses-course-name">'. $c->fullname .'</div>
											<a class="cc-courses-button" type="button" href="'. new moodle_url("/course/view.php",array("id" => $c->id)). '">Acceder al curso</a>
				</div>
				</div>
			</div>';

		$totalPending++;
		$coursesHtml.= '<div class="slide">'. $content .'</div>';
	}
	$coursesHtml.= '<input id="totalPending" type="hidden" value="'.$totalPending.'">';
	return $coursesHtml;
}

function getCoursesHtml($course) {
	$html = '';

	if(!empty($course)) {
		$categoryId = $course->category;
		$courseObj = get_course($course->id);
		$coursePercentage = !empty($course->percentage) ? $course->percentage : 0;
		$daysLeft = getDaysLeft($course->startdate, $course->enddate);
		$daysLeftPercentage = getDaysLeftPercentage($course->startdate, $course->enddate);
		$usersCompletedPercentage = ($course->studentscompleted/getEnrolledUsers($course))*100;

		$html.= '<div class="column d-course-row" style="height: 149px; width: 610px; background-color: white; box-shadow: 2px 2px 4px #00000029; border-radius: 4px; margin: 0 0 1% 1%; padding: 1%;">
							<div class="row" style="position: relative; height: 100%;">
								<div class="col-sm" style="position: relative; max-width: 40% !important; text-align: left; height: 100%;">
									<img class="dd-image-card" src="'. \theme_remui\utility::get_course_image($courseObj) .'">
								</div>
								<div class="col-sm pl-0 pr-0" style="width: 50%;left: 1%;position: relative;text-align: left;">
									<div class="text-left" style="font-size: 12px; color: #A3AFB7">'. getCategoryById($categoryId)->name .'</div>
									<div class="text-left dd-line-height-name" style="font-size: 22px; font-weight: 525; color: #526069; overflow: hidden; height: 40px;"><a style="text-decoration: none !important; color: #526069 !important;" type="button" href="'. new moodle_url("/course/view.php",array("id" => $course->id)). '">'. $course->fullname .'</a></div>
									<div class="row dd-rounded-progress-box" style="width: 100%; height: auto; padding-top: 6%;">
										<div class="col-sm" style="width: 50%; height: 100%;">
											<div class="row">
												 <div class="col-sm" style="max-width: 30% !important;">';
														$html.= getProgressBarDetail($coursePercentage);
								$html.= '</div>
													<div class="col-sm dd-line-height" style="font-size: 13px; color: #A3AFB7">
														Progreso: '. round($coursePercentage) .' %';
									$html.= '</div>
											</div>
										</div>';

					if(isset($course->enddate) && !empty($course->enddate)) {
						$html.= '<div class="col-sm" style="width: 50%; height: 100%;">
											<div class="row">
												 <div class="col-sm" style="max-width: 30% !important;">';
														$html.= getProgressBarDetail($daysLeftPercentage);
								$html.= '</div>
													<div class="col-sm dd-line-height pr-0" style="font-size: 13px; color: #A3AFB7">
														Cierra en '. $daysLeft .' días';
								$html.= '</div>
											</div>
										</div>';
						} else {
						$html.= '<div class="col-sm" style="width: 50%">
											<div class="row">
												 <div class="col-sm" style="width: 50%">
											-
											</div>';
						}
					$html.= '</div>
								</div>
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

//new Julio - samuel
$pendingCoursesHtml = getPendingCoursesHtml($courses);


function getSeguimientoHtml() {
	$seguimientoHtml = '';
	$seguimientoHtml .= '<div class="p-5" style="background-color: white; width: 90%; margin-left: 5%;">
			<div class="row">
				<div class="col" style="text-align: left;">Seguimiento de finalización de cursos</div>
				<div class="col-md-auto">carta</div>
				<div class="col-md-auto">download</div>
			</div>
			<div class="row">
				<div class="col" style="text-align: left;">Puedes dar clic en cualquier item para desplegar detalles</div>
			</div>
			<div class="row">
				<div class="col" style="text-align: left;">bread</div>
			</div>
			<div class="row">
				<div class="col" style="text-align: left;">content</div>
			</div>
</div>';

	return $seguimientoHtml;
}

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
	'courseshtml' => $coursesHtml,
	'pendingCoursesHtml' => $pendingCoursesHtml,
	'seguimientoHtml' => getSeguimientoHtml()
];

$templatecontext = array_merge($templatecontext, $templatecontextDashboard);

echo $OUTPUT->render_from_template('theme_remui/mydashboard', $templatecontext);

