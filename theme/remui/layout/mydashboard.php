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
use core_course_renderer;
use coursecat_helper;

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

function getProgressBarDetailSeguimientoHtml($value, $courseId=null) {
	$returnHTML = '<div course-id="'. $courseId .'" class="element-progress-bar col-sm" style="max-width: 3.3%; color: #526069;">'. round($value,0) .'%</div>';
	$returnHTML .= '<div course-id="'. $courseId .'" class="element-progress-bar col-sm-7">'. getProgressBarDetailSeguimiento($value) .'</div>';

	return $returnHTML;
}

function getProgressBarDetailSeguimiento($value) {
	return '<div class="block_xp-level-progress progress-non-zero d-progress-bar-level-course" style="width: 95%;">
<div class="xp-bar-wrapper d-progress-bar-level-course" role="progressbar" aria-valuenow="'. $value .'" aria-valuemin="0" aria-valuemax="100" style="width: ' . $value .'%; margin: 0 !important;">
<div class="xp-bar d-xp-bar-course"></div>
</div>
</div>';
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
										<div class="dd-category-box-secundary">
										<div class="dd-h3-courses-info" style="background: url('. \theme_remui\utility::get_course_image($c) .');"></div>
										<div class="cc-courses-detail-container dd-ultimos-desc"> '. progressBarHTML($c) .'
											<div class="text-left" style="font-size: 12px; color: #A3AFB7; padding: 2% 4% 0 7%; height: 35px;">'. getCategoryById($c->id)->name .'</div>
											<div class="dd-courses-course-name">'. $c->fullname .'</div>
											<a class="dd-courses-button" type="button" href="'. new moodle_url("/course/view.php",array("id" => $c->id)). '">Acceder al curso</a>
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

function getEnrolledUsersDetail($courseId) {
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
		array(CONTEXT_COURSE, 5, $courseId)
	);
	return $enrolledusers;
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

function categoryHasCourses($catId) {
	$courses = core_course_category::get($catId)->get_courses();
	if(count($courses) > 0) {
		return true;
	}
	return false;
}

function getPersonalPorArea($courseId, $tipoPersonal, $area, $enrolledUsersArray) {
	$returnHTML = '';
	$personal = array();
	$course = get_course($courseId);

	foreach($enrolledUsersArray as $key=>$eu) {
		$personal[$key]['id'] = $eu->id;
		$personal[$key]['nombre'] = $eu->firstname . ' ' . $eu->lastname;
		$personal[$key]['tipo_personal'] = $eu->profile_field_personal;
		$personal[$key]['area'] = $eu->profile_field_area_funcional;
	}

	$personal = array_unique($personal, SORT_REGULAR);

	foreach($personal as $key=>$persona) {
		if($persona['tipo_personal'] != $tipoPersonal || $persona['area'] != $area) {
			continue;
		}
		$returnHTML.= '<div zona-name="'. $tipoPersonal . $area .'" course-id="'. $courseId .'" class="ss-container ss-main-container-personal row hidden ss-m-b-05">
											<div data-id="'. $persona['id'] . '" data-val="'.strtoupper($persona['nombre']).'" class="col-sm personal-clickable" style="cursor: pointer; font-size: 18px;" data-toggle="modal" data-target="#myModal">'. $persona['nombre'] .'</div>
											<div data-id="'. $persona['id'] . '" data-val="'.strtoupper($persona['nombre']).'" class="col-xs" data-toggle="modal" data-target="#myModal" style="cursor: pointer;">
													<img src="../theme/remui/pix/ic_email_24px.png">
											</div>';
		$progress = round(progress::get_course_progress_percentage($course, $persona['id']));
		$returnHTML.= getProgressBarDetailSeguimientoHtml($progress);
		$returnHTML.= '</div>';
	}

	return $returnHTML;
}

function getPersonal($courseId, $tipoPersonal, $division, $zona, $enrolledUsersArray) {
	$returnHTML = '';
	$personal = array();
	$course = get_course($courseId);

	foreach($enrolledUsersArray as $key=>$eu) {
		$personal[$key]['id'] = $eu->id;
		$personal[$key]['nombre'] = $eu->firstname . ' ' . $eu->lastname;
		$personal[$key]['tipo_personal'] = $eu->profile_field_personal;
		$personal[$key]['division'] = $eu->profile_field_division;
		$personal[$key]['zona'] = $eu->profile_field_zona;
	}

	$personal = array_unique($personal, SORT_REGULAR);

	foreach($personal as $key=>$persona) {
		if($persona['tipo_personal'] != $tipoPersonal || $persona['division'] != $division || $persona['zona'] != $zona) {
			continue;
		}
		$returnHTML.= '<div zona-name="'. $tipoPersonal . $division . $zona .'" course-id="'. $courseId .'" class="ss-container ss-main-container-personal row hidden ss-m-b-05">
											<input class="personaIds" type="hidden" value="'.$persona['id'].'">
											<div data-id="'. $persona['id'] . '" data-val="'.strtoupper($persona['nombre']).'" class="col-sm personal-clickable" style="cursor: pointer; font-size: 18px;" data-toggle="modal" data-target="#myModal">'. $persona['nombre'] .'</div>
											<div data-id="'. $persona['id'] . '" data-val="'.strtoupper($persona['nombre']).'" class="col-xs logo-mail-clickable" data-toggle="modal" data-target="#myModal" style="cursor: pointer;">
													<img src="../theme/remui/pix/ic_email_24px.png">
											</div>';
		$progress = round(progress::get_course_progress_percentage($course, $persona['id']));
		$returnHTML.= getProgressBarDetailSeguimientoHtml($progress);
		$returnHTML.= '</div>';
	}

	return $returnHTML;
}

function getTipoPersonalPorArea($courseId, $area, $enrolledUsersArray) {
	$returnHTML = '';
	$tipoPersonal = array();

	foreach($enrolledUsersArray as $key=>$eu) {
		$tipoPersonal[$key]['tipo_personal'] = $eu->profile_field_personal;
		$tipoPersonal[$key]['area'] = $eu->profile_field_area_funcional;
	}

	$tipoPersonal = array_unique($tipoPersonal, SORT_REGULAR);

	foreach($tipoPersonal as $tipoPers) {
		if($tipoPers['area'] != $area) {
			continue;
		}
		$personaIds = getTipoPersonalPorAreaProgress($courseId, $area, $enrolledUsersArray)['ids'];
		$returnHTML .= '<div zona-name="'. $area .'" course-id="'. $courseId .'" data-open="ss-main-container-personal" class="ss-container ss-main-container-tipo-personal row hidden ss-m-b-05">';
		$returnHTML .= '<input class="personaIds" type="hidden" value="'. $personaIds .'">';
		$returnHTML .= '<div zona-name="'. $tipoPers['tipo_personal'] . $area . '" course-id="'. $courseId .'" data-open="ss-main-container-personal" class="col-sm element-clickable" style="cursor: pointer;">'. $tipoPers['tipo_personal'] .'</div>';
		$progress = getTipoPersonalPorAreaProgress($courseId, $area, $enrolledUsersArray)['progress'];
		$returnHTML.= getProgressBarDetailSeguimientoHtml($progress);
		$returnHTML .= '</div>';
		$arrayTipoPersonal[] = $tipoPers['tipo_personal'];
	}

	foreach($arrayTipoPersonal as $tipoPers) {
		$returnHTML .= getPersonalPorArea($courseId, $tipoPers, $area, $enrolledUsersArray);
	}

	return $returnHTML;
}

function getTipoPersonalPorDivision($courseId, $division, $zona, $enrolledUsersArray) {
	$returnHTML = '';

	foreach($enrolledUsersArray as $key=>$eu) {
		$tipoPersonal[$key]['tipo_personal'] = $eu->profile_field_personal;
		$tipoPersonal[$key]['division'] = $eu->profile_field_division;
		$tipoPersonal[$key]['zona'] = $eu->profile_field_zona;
	}

	$tipoPersonal = array_unique($tipoPersonal, SORT_REGULAR);

	foreach($tipoPersonal as $tipoPers) {
		if($tipoPers['division'] != $division || $tipoPers['zona'] != $zona) {
			continue;
		}
		$personaIds = getTipoPersonalPorDivisionProgress($courseId, $division, $zona, $enrolledUsersArray)['ids'];
		$returnHTML .= '<div zona-name="'. $division . $zona . '" course-id="'. $courseId .'" data-open="ss-main-container-personal" class="ss-container ss-main-container-tipo-personal row hidden ss-m-b-05">';
		$returnHTML .= '<input class="personaIds" type="hidden" value="'. $personaIds .'">';
		$returnHTML .= '<div zona-name="'. $tipoPers['tipo_personal'] . $division . $zona . '" course-id="'. $courseId .'" data-open="ss-main-container-personal" class="col-sm element-clickable" style="cursor: pointer;">'. $tipoPers['tipo_personal'] .'</div>';

		$progress = getTipoPersonalPorDivisionProgress($courseId, $division, $zona, $enrolledUsersArray)['progress'];

		$returnHTML.= getProgressBarDetailSeguimientoHtml($progress);
		$returnHTML .= '</div>';
		$arrayTipoPersonal[] = $tipoPers['tipo_personal'];
	}

	foreach($arrayTipoPersonal as $tipoPers) {
		$returnHTML .= getPersonal($courseId, $tipoPers, $division, $zona, $enrolledUsersArray);
	}

	return $returnHTML;
}

function getDivisionesPorZona($courseId, $zona, $enrolledUsersArray) {
	$returnHTML = '';

	foreach($enrolledUsersArray as $key=>$eu) {
		$divisiones[$key]['division'] = $eu->profile_field_division;
		$divisiones[$key]['zona'] = $eu->profile_field_zona;
	}

	$divisiones = array_unique($divisiones, SORT_REGULAR);

	foreach($divisiones as $division) {
		if($division['zona'] != $zona) {
			continue;
		}
		$personaIds = getDivisionesPorZonaProgress($courseId, $zona, $enrolledUsersArray)['ids'];
		$returnHTML .= '<div zona-name="'. $zona .'" course-id="'. $courseId .'" data-open="ss-main-container-tipo-personal" class="ss-container ss-main-container-division row hidden ss-m-b-05">';
		$returnHTML .= '<input class="personaIds" type="hidden" value="'. $personaIds .'">';
		$returnHTML .= '<div zona-name="'. $division['division'] . $zona .'" course-id="'. $courseId .'" data-open="ss-main-container-tipo-personal" class="col-sm element-clickable" style="cursor: pointer;">'. $division['division'] .'</div>';
		$progress = getDivisionesPorZonaProgress($courseId, $zona, $enrolledUsersArray)['progress'];

		$returnHTML.= getProgressBarDetailSeguimientoHtml($progress);
		$returnHTML .= '</div>';
		$arrayDivisiones[] = $division['division'];
	}

	foreach($arrayDivisiones as $division) {
		$returnHTML .= getTipoPersonalPorDivision($courseId, $division, $zona, $enrolledUsersArray);
	}

	return $returnHTML;
}

function getZonas($courseId) {
	$returnHTML = '';

	$enrolledUsersArray = getUserAllDataByCourseId($courseId);

	foreach($enrolledUsersArray as $eu) {
		$zonasAll[] = $eu->profile_field_zona;
	}

	//This data is static
	$zonas = array( 'Norte',
									'Centro',
									'Sur',
									'Corporativo');

	foreach($zonas as $zona) {
		if(!in_array($zona, $zonasAll)) {
			continue;
		}
		$personaIds = getZonasProgressById($courseId, $zona)['ids'];
		$returnHTML .= '<div zona-name="zona-default-zonas" course-id="'. $courseId .'" data-open="ss-main-container-division" class="ss-container ss-main-container-zonas-detail row hidden ss-m-b-05">';
		$returnHTML .= '<input class="personaIds" type="hidden" value="'. $personaIds .'">';
		$returnHTML .= '<div zona-name="'. $zona .'" course-id="'. $courseId .'" data-open="ss-main-container-division" class="col-sm element-clickable" style="cursor: pointer;">' . $zona . '</div>';
		$progress = getZonasProgressById($courseId, $zona)['progress'];
		$returnHTML .= getProgressBarDetailSeguimientoHtml($progress);

		$returnHTML .= '</div>';
	}

	foreach($zonas as $zona) {
		$returnHTML .= getDivisionesPorZona($courseId, $zona, $enrolledUsersArray);
	}

	return $returnHTML;
}

function getAreasFuncionales($courseId) {
	$returnHTML = '';
	$areasAll = array();

	$enrolledUsersArray = getUserAllDataByCourseId($courseId);
	foreach($enrolledUsersArray as $eu) {
		$areasAll[] = $eu->profile_field_area_funcional;
	}

	foreach($areasAll as $area) {
		$personaIds = getAreaProgress($courseId, $area, $enrolledUsersArray)['ids'];
		$returnHTML.= '<div zona-name="zona-default-areas" course-id="'. $courseId .'" data-open="ss-main-container-tipo-personal" class="ss-container ss-main-container-areas-detail row hidden ss-m-b-05">';
		$returnHTML .= '<input class="personaIds" type="hidden" value="'. $personaIds .'">';
		$returnHTML .= '<div zona-name="'. $area .'" course-id="'. $courseId .'" data-open="ss-main-container-tipo-personal" class="col-sm element-clickable" style="cursor: pointer;">'. $area .'</div>';
		$progress = getAreaProgress($courseId, $area, $enrolledUsersArray)['progress'];
		$returnHTML.= getProgressBarDetailSeguimientoHtml($progress, $courseId);
		$returnHTML.= '</div>';
	}

	foreach($areasAll as $area) {
		$returnHTML .= getTipoPersonalPorArea($courseId, $area, $enrolledUsersArray);
	}

	return $returnHTML;
}

function getSSSeguimientoDetails($courseId) {
	$returnHTML = '';
	$dataOpen = '';
	$zona = '';
	$personaIds = '';
	$seguimientoDetails = array('Seguimiento por zonas', 'Seguimiento por área funcional');

	foreach($seguimientoDetails as $seguimientoDetail) {
		if($seguimientoDetail == 'Seguimiento por zonas') {
			$dataOpen = 'ss-main-container-zonas-detail';
			$zona = 'zona-default-zonas';
			$personaIds = getSeguimientoDetailsZonaProgress($courseId)['ids'];
			$progress = getSeguimientoDetailsZonaProgress($courseId)['progress'];
		} elseif($seguimientoDetail == 'Seguimiento por área funcional') {
			$dataOpen = 'ss-main-container-areas-detail';
			$zona = 'zona-default-areas';
			$personaIds = getSeguimientoDetailsAreaProgress($courseId)['ids'];
			$progress = getSeguimientoDetailsAreaProgress($courseId)['progress'];
		}

		$returnHTML .=	'<div zona-name="zona-default" course-id="'. $courseId .'" data-open="'. $dataOpen .'" class="ss-container ss-main-container-seguimiento-detail row hidden ss-m-b-05">';
		$returnHTML .= '<input class="personaIds" type="hidden" value="'. $personaIds .'">';
		$returnHTML .= '<div zona-name="'. $zona .'" course-id="'. $courseId .'" data-open="'. $dataOpen .'"  class="col-sm element-clickable" style="cursor: pointer;">'. $seguimientoDetail .'</div>';

		$returnHTML.= getProgressBarDetailSeguimientoHtml($progress, $courseId);
		$returnHTML.= '</div>';
	}

	return 	$returnHTML;
}

function getUserAllDataByCourseId($courseId) {
	$users = getEnrolledUsersDetail($courseId);

	foreach($users as $key=>$user) {
		profile_load_data($user);
	}

	return $users;
}

function getSSCoursesById($id) {
	$returnHTML = '';
	$courses = core_course_category::get($id)->get_courses();

	if(!empty ($courses)) {
		foreach($courses as $course) {
			$returnHTML .= '<div data-id="'. $id .'" class="ss-container ss-main-container-course row hidden ss-m-b-05">';
			$returnHTML .= '<div zona-name="zona-default" course-id="'. $course->id .'" data-open="ss-main-container-seguimiento-detail" data-id="'. $course->id .'" class="col-sm element-clickable" style="cursor: pointer;">'.$course->fullname.'</div>';

			$progress = getCursosProgress($course->id)['progress'];

			$returnHTML .= '<div class="col-sm" style="max-width: 3.3%; color: #526069;">'. round($progress,0) .'%</div>';
			$returnHTML .= '<div class="col-sm-7">'. getProgressBarDetailSeguimiento($progress) .'</div>';
			$returnHTML .= '</div>';
		}

		foreach($courses as $course) {
			$returnHTML .= getSSSeguimientoDetails($course->id);
			$returnHTML .= getAreasFuncionales($course->id);
			$returnHTML .= getZonas($course->id);
		}
	}

	return $returnHTML;
}

function getSSCategories() {
	global $DB;
	$returnHTML = '';
	$categories = $DB->get_records('course_categories');

	foreach($categories as $cat) {
		if(categoryHasCourses($cat->id)) {
			$extraStyle = ' style="cursor: pointer; font-size: 18px;"';
			$extraClass = 'cat-clickable';
			$value = getCategoryProgressById($cat->id);
		} else {
			$extraStyle = '';
			$extraClass = '';
			$value = 0;
		}

		$returnHTML .= '<div class="ss-container ss-main-container row ss-m-b-05">';
		$returnHTML .= '<div data-id="'. $cat->id .'" class="col-sm '. $extraClass .'"'.$extraStyle.' style="font-size: 18px;">'.$cat->name.'</div>';
		$returnHTML .= '<div class="col-sm" style="max-width: 3.3%; color: #526069;">'. round($value,0) .'%</div>';
		$returnHTML .= '<div class="col-sm-7">'. getProgressBarDetailSeguimiento($value) .'</div>';
		$returnHTML .= '</div>';
	}

	foreach($categories as $cat) {
		$returnHTML .= getSSCoursesById($cat->id);
	}

	$returnHTML.= '<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">

			<!-- Modal mensaje personal -->
			<div class="modal-content">
				<div class="modal-header" style="padding: 2% 3% 0 3%; border-bottom: 0;">
					<h4 class="ss-message-title-1 modal-title"></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-header" style="padding: 0 0 0 3%; border-bottom: 0;">
					<h5 class="modal-title">Llegará a su correo electrónico</h5>
				</div>
				<div class="modal-body">
					<textarea id="message-text" class="form-control" style="height: 140px;"></textarea>
				</div>
				<div class="modal-footer" style="padding: 0 3% 3% 0; border-top: 0;">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button id="myModalBtn" type="button" class="btn btn-info" data-dismiss="modal" style="color: white;">Enviar</button>
				</div>
			</div>
		</div>
	</div>';

	$returnHTML.= '<div class="modal fade" id="myModalGeneral" role="dialog">
		<div class="modal-dialog">
		
			<!-- Modal mensaje general -->
			<div class="modal-content">
				<div class="modal-header" style="padding: 2% 3% 0 3%; border-bottom: 0;">
					<h4 class="ss-message-title-1 modal-title">MENSAJE PARA EMPLEADOS</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-header" style="padding: 0 0 0 3%; border-bottom: 0;">
					<h5 class="modal-title">Llegará al correo de los usuarios que aún no terminan el curso.</h5>
				</div>
				<div class="modal-body">
					<textarea id="message-text-all" class="form-control" style="height: 140px;"></textarea>
				</div>
				<div class="modal-footer" style="padding: 0 3% 3% 0; border-top: 0;">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button id="myModalGeneralBtn" type="button" class="btn btn-info" data-dismiss="modal" style="color: white;">Enviar</button>
				</div>
			</div>
		</div>
	</div>';

	return $returnHTML;
}


function getPersonalPorAreaProgress($courseId, $tipoPersonal, $area, $enrolledUsersArray) {
	$ids = '';
	$returnArr = array();
	$personal = array();
	$course = get_course($courseId);

	foreach($enrolledUsersArray as $key=>$eu) {
		$personal[$key]['id'] = $eu->id;
		$personal[$key]['tipo_personal'] = $eu->profile_field_personal;
		$personal[$key]['area'] = $eu->profile_field_area_funcional;
	}

	$personal = array_unique($personal, SORT_REGULAR);

	$count = 0;
	$progress = 0;

	foreach($personal as $key=>$persona) {
		if($persona['tipo_personal'] != $tipoPersonal || $persona['area'] != $area) {
			continue;
		}
		$count++;
		$progress += round(progress::get_course_progress_percentage($course, $persona['id']));
		$ids .= $persona['id'] . ',';
	}

	if($count == 0) {
		return 0;
	}

	$returnArr['progress'] = $progress/$count;
	$ids = rtrim($ids, ',');
	$ids = implode(',', array_unique(explode(',', $ids)));
	$returnArr['ids'] = $ids;

	return $returnArr;
}

function getPersonalProgress($courseId, $tipoPersonal, $division, $zona, $enrolledUsersArray) {
	$ids = '';
	$returnArr = array();
	$personal = array();
	$course = get_course($courseId);

	foreach($enrolledUsersArray as $key=>$eu) {
		$personal[$key]['id'] = $eu->id;
		$personal[$key]['tipo_personal'] = $eu->profile_field_personal;
		$personal[$key]['division'] = $eu->profile_field_division;
		$personal[$key]['zona'] = $eu->profile_field_zona;
	}

	$personal = array_unique($personal, SORT_REGULAR);

	$count = 0;
	$progress = 0;

	foreach($personal as $key=>$persona) {
		if($persona['tipo_personal'] != $tipoPersonal || $persona['division'] != $division || $persona['zona'] != $zona) {
			continue;
		}
		$count++;
		$progress += round(progress::get_course_progress_percentage($course, $persona['id']));
		$ids .= $persona['id'] . ',';
	}

	if($count == 0) {
		return 0;
	}

	$returnArr['progress'] = $progress/$count;
	$ids = rtrim($ids, ',');
	$ids = implode(',', array_unique(explode(',', $ids)));
	$returnArr['ids'] = $ids;

	return $returnArr;
}

function getTipoPersonalPorAreaProgress($courseId, $area, $enrolledUsersArray) {
	$returnArr = array();
	foreach($enrolledUsersArray as $key=>$eu) {
		$tipoPersonal[$key]['tipo_personal'] = $eu->profile_field_personal;
		$tipoPersonal[$key]['area'] = $eu->profile_field_area_funcional;
	}

	$tipoPersonal = array_unique($tipoPersonal, SORT_REGULAR);

	$count = 0;
	$progress = 0;
	$ids = '';

	foreach($tipoPersonal as $tipoPers) {
		if ($tipoPers['area'] != $area) {
			continue;
		}
		$count++;
		$progress += getPersonalPorAreaProgress($courseId, $tipoPers['tipo_personal'], $area, $enrolledUsersArray)['progress'];
		$ids .= getPersonalPorAreaProgress($courseId, $tipoPers['tipo_personal'], $area, $enrolledUsersArray)['ids'] . ',';
	}

	if($count == 0) {
		return 0;
	}

	$returnArr['progress'] = $progress/$count;
	$ids = rtrim($ids, ',');
	$ids = implode(',', array_unique(explode(',', $ids)));
	$returnArr['ids'] = $ids;

	return $returnArr;
}

function getAreaProgress($courseId, $area, $enrolledUsersArray) {
	$returnArr = array();
	foreach($enrolledUsersArray as $key=>$eu) {
		$tipoPersonal[$key]['tipo_personal'] = $eu->profile_field_personal;
		$tipoPersonal[$key]['area'] = $eu->profile_field_area_funcional;
	}

	$count = 0;
	$progress = 0;
	$ids = '';

	foreach($tipoPersonal as $tipoPers) {
		if ($tipoPers['area'] != $area) {
			continue;
		}
		$count++;
		$progress += getTipoPersonalPorAreaProgress($courseId, $area, $enrolledUsersArray)['progress'];
		$ids .= getTipoPersonalPorAreaProgress($courseId, $area, $enrolledUsersArray)['ids'];
	}

	if($count == 0) {
		return 0;
	}

	$returnArr['progress'] = $progress/$count;
	$ids = rtrim($ids, ',');
	$ids = implode(',', array_unique(explode(',', $ids)));
	$returnArr['ids'] = $ids;

	return $returnArr;
}

function getTipoPersonalPorDivisionProgress($courseId, $division, $zona, $enrolledUsersArray) {
	$returnArr = array();

	foreach($enrolledUsersArray as $key=>$eu) {
		$tipoPersonal[$key]['tipo_personal'] = $eu->profile_field_personal;
		$tipoPersonal[$key]['division'] = $eu->profile_field_division;
		$tipoPersonal[$key]['zona'] = $eu->profile_field_zona;
	}

	$tipoPersonal = array_unique($tipoPersonal, SORT_REGULAR);

	$count = 0;
	$progress = 0;
	$ids = '';

	foreach($tipoPersonal as $tipoPers) {
		if ($tipoPers['division'] != $division || $tipoPers['zona'] != $zona) {
			continue;
		}
		$count++;
		$progress += getPersonalProgress($courseId, $tipoPers['tipo_personal'], $division, $zona, $enrolledUsersArray)['progress'];
		$ids .= getPersonalProgress($courseId, $tipoPers['tipo_personal'], $division, $zona, $enrolledUsersArray)['ids'] . ',';
	}

	if($count == 0) {
		return 0;
	}

	$ids = rtrim($ids, ',');
	$ids = implode(',',array_unique(explode(',', $ids)));

	$returnArr['progress'] = $progress/$count;
	$returnArr['ids'] = $ids;

	return $returnArr;
}

function getDivisionesPorZonaProgress($courseId, $zona, $enrolledUsersArray) {
	$returnArr = array();
	foreach($enrolledUsersArray as $key=>$eu) {
		$divisiones[$key]['division'] = $eu->profile_field_division;
		$divisiones[$key]['zona'] = $eu->profile_field_zona;
	}

	$divisiones = array_unique($divisiones, SORT_REGULAR);

	$count = 0;
	$progress = 0;
	$ids = '';

	foreach($divisiones as $division) {
		if ($division['zona'] != $zona) {
			continue;
		}
		$count++;
		$progress += getTipoPersonalPorDivisionProgress($courseId, $division['division'], $zona, $enrolledUsersArray)['progress'];
		$ids .= getTipoPersonalPorDivisionProgress($courseId, $division['division'], $zona, $enrolledUsersArray)['ids'] . ',';
	}

	if($count == 0) {
		return 0;
	}

	$ids = rtrim($ids, ',');
	$ids = implode(',',array_unique(explode(',', $ids)));

	$returnArr['progress'] = $progress/$count;
	$returnArr['ids'] = $ids;

	return $returnArr;
}

function getZonasProgressById($courseId, $zonaExt) {
	$returnArr = array();
	$enrolledUsersArray = getUserAllDataByCourseId($courseId);

	foreach($enrolledUsersArray as $eu) {
		$zonasAll[] = $eu->profile_field_zona;
	}

	$zonas = array( 'Norte',
		'Centro',
		'Sur',
		'Corporativo');

	$count = 0;
	$progress = 0;
	$ids = '';

	foreach($zonas as $zona) {
		if($zonaExt != $zona) {
			continue;
		}
		$count++;
		$progress += getDivisionesPorZonaProgress($courseId, $zona, $enrolledUsersArray)['progress'];
		$ids .= getDivisionesPorZonaProgress($courseId, $zona, $enrolledUsersArray)['ids'] . ',';
	}

	if($count == 0) {
		return 0;
	}

	$ids = rtrim($ids, ',');
	$ids = implode(',',array_unique(explode(',', $ids)));

	$returnArr['progress'] = $progress/$count;
	$returnArr['ids'] = $ids;

	return $returnArr;
}

function getSeguimientoDetailsZonaProgress($courseId) {
	$returnArr = array();
	$enrolledUsersArray = getUserAllDataByCourseId($courseId);

	foreach($enrolledUsersArray as $eu) {
		$zonas[] = $eu->profile_field_zona;
	}

	$progress = 0;
	$ids = '';

	foreach($zonas as $zona) {
		$progress += getZonasProgressById($courseId, $zona)['progress'];
		$ids .= getZonasProgressById($courseId, $zona)['ids'] . ',';
	}

	if(count($zonas) == 0) {
		return 0;
	}
	$ids = rtrim($ids, ',');
	$ids = implode(',',array_unique(explode(',', $ids)));

	$returnArr['progress'] = $progress/count($zonas);
	$returnArr['ids'] = $ids;

	return $returnArr;
}

function getSeguimientoDetailsAreaProgress($courseId) {
	$returnArr = array();
	$enrolledUsersArray = getUserAllDataByCourseId($courseId);

	foreach($enrolledUsersArray as $key=>$eu) {
		$areas[] = $eu->profile_field_area_funcional;
	}

	$progress = 0;
	$ids = '';

	foreach($areas as $area) {
		$progress += getAreaProgress($courseId, $area, $enrolledUsersArray)['progress'];
		$ids .= getAreaProgress($courseId, $area, $enrolledUsersArray)['ids'] . ',';
	}

	if(count($areas) == 0) {
		return 0;
	}

	$ids = rtrim($ids, ',');
	$ids = implode(',',array_unique(explode(',', $ids)));

	$returnArr['progress'] = $progress/count($areas);
	$returnArr['ids'] = $ids;

	return $returnArr;
}

function getCursosProgress($courseId) {
	$returnArr = array();
	$seguimientoDetails = array('zonas','areas');

	foreach($seguimientoDetails as $seguimientoDetail) {
		if($seguimientoDetail == 'zonas') {
			$progress += getSeguimientoDetailsZonaProgress($courseId)['progress'];
		} else {
			$progress += getSeguimientoDetailsAreaProgress($courseId)['progress'];
		}
	}

	$progress = $progress/count($seguimientoDetails);

	$returnArr['progress'] = $progress;

	return $returnArr;
}

function getCategoryProgressById($idCat) {
	$courses = core_course_category::get($idCat)->get_courses();

	$progress = 0;

	foreach($courses as $key=>$course) {
		$progress += getCursosProgress($course->id)['progress'];
	}

	$progress = $progress/count($courses);

	return $progress;
}

function getSeguimientoHtml() {
	$seguimientoHtml = '';
	$seguimientoHtml .= '<div style="background-color: white; width: 90%; margin-left: 5%; padding: 1% 1% 1% 2% !important; box-shadow: 2px 2px 4px #00000029;
border-radius: 4px;">
			<div class="row">
				<div class="col-sm" style="text-align: left; font-weight: bold; color: #154A7D; font-size: 24px;">Seguimiento de finalización de cursos</div>
				<div class="col-sm-1 pl-0">
					<div class="row">
						<div class="col-sm carta-general" data-toggle="modal">
							<img style="opacity: 0.5; cursor: auto;" src="../theme/remui/pix/ic_email_24px.png">
						</div> 
						<div id="modalXLS" class="col-sm" style="cursor: pointer;">
							<img src="../theme/remui/pix/ic_get_app_24px.png">
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col" style="text-align: left; color: #154A7D; font-size: 15px;">Puedes dar clic en cualquier item para desplegar detalles</div>
			</div>
			<div class="row">
				<div class="ss-breadcrumb col-sm row" style="text-align: left; margin: 2% 0 1% 0; color: #154A7D; font-size: 18px;"><div class="main-clickable" style="cursor: pointer;">CATEGORÍAS</div></div>
			</div>
			<div class="row">
				<div id="seguimiento-content" class="col" style="text-align: left; color: #526069;">'. getSSCategories().'</div>
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

