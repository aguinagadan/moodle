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
 * Edwiser RemUI Announcements
 * @package    theme_remui
 * @copyright  (c) 2018 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
// include Moodle config
if (!@include_once(__DIR__.'/../../config.php')) {
    include_once('/var/www/remui.local/html/v38/config.php');
}

global $DB, $OUTPUT, $CFG, $USER;

$response = \theme_remui\utility::get_remui_announcemnets();

// get update info
$updateinfo = array();
if (isset($response['updateinfo']) && !empty($response['updateinfo'])) {
    $updateinfo = $response['updateinfo'];
}

// get remui installed status
$pm = \core_plugin_manager::instance();
$currentremui = $pm->get_plugin_info('theme_remui')->release;
$currentmoodle = substr($CFG->release, 0, 3);
?>
<style>
  input.star { display: none; }
  label.star {
    float: right;
    padding: 0 10px;
    margin: 0;
    font-size: 22px;
    color: #999;
    transition: all .2s;
  }

  input.star:checked ~ label.star:before {
    content: '\f005';
    color: #Fb4;
    /* transition: all .2s; */
  }
  input.star-1:checked ~ label.star:before { color: #F62; }
  input.star-2:checked ~ label.star:before { color: #F92; }
  /* label.star:hover { transform: scale(1.1); } */
  label.star:before {
    content: '\f006';
    font-family: Font Awesome;
  }
</style>

<!-- if open -->
<?php if (!empty($updateinfo) && isset($updateinfo[$currentmoodle])) {?>
<div class="row update-info">
<div class="col-12 col-md-12 my-35">
<?php
    $lcontroller = new \theme_remui\controller\license_controller();
    $getlidatafromdb = $lcontroller->getDataFromDb();
if ('available' == $getlidatafromdb) {
    if (!empty($updateinfo) && isset($updateinfo[$currentmoodle])) {
        if ($updateinfo[$currentmoodle]['version'] > $currentremui) {
            echo "<p class='lead text-center alert alert-info'>".get_string('newupdatemessage', 'theme_remui')."</p>
            <h2 class='font-size-48 text-center'>v{$updateinfo[$currentmoodle]['version']}</h2>
            <p class='text-center font-size-14'>".get_string('currentversionmessage', 'theme_remui')."<cite title='Source Title' class='h5 font-weight-700'>v{$currentremui}</cite>&nbsp;&nbsp; <a href='{$updateinfo[$currentmoodle]['downloadlink']}' class='badge badge-info badge-pill'>".get_string('downloadupdate', 'theme_remui')."</a></p>";
        } else {
            echo "<p class='lead text-center alert alert-success'>".get_string('latestversionmessage', 'theme_remui')."</p>
            <h2 class='font-size-48 text-center'>v{$currentremui}</h2>";
        }
    }
} else {
    echo "<p class='lead text-center'>".get_string('licensenotactive', 'theme_remui')."</p>";
}
    ?>
</div>
</div>
<!-- announcements section -->
<?php
echo "<div>";
echo "<div class='col-12'>";

if (isset($response['announcements']) && !empty($response['announcements'])) {
    echo "<h2 class='h3 mb-25 row'>".get_string('recentnews', 'theme_remui')."</h2>";

    echo '<div class="row">';
    foreach ($response['announcements'] as $anc) {
        if (!empty($anc['image'])) {
            echo "<div class='card' style='background: #f1f4f5;'>
                <img class='card-img-top w-full' src='{$anc['image']}'>
                    <div class='card-block'>";
            if (!empty($anc['link'])) {
                echo "<h4 class='card-title'><a href='{$anc['link']}' target='_blank' class='grey-800'>{$anc['title']}</a></h4>";
            } else {
                echo "<h4 class='card-title grey-800'>{$anc['title']}</h4>";
            }
            if (!empty($anc['excerpt'])) {
                echo "<p class='card-text'>{$anc['excerpt']}</p>";
            }
            echo "</div>
                  </div>";
        } else {
            echo "<div class='card card-block card-inverse card-{$anc['type']} text-center p-15'>
                  <blockquote class='blockquote cover-quote card-blockquote'>";
            if (!empty($anc['excerpt'])) {
                echo "<p>{$anc['excerpt']}</p>";
            }

            if (!empty($anc['link'])) {
                echo "<footer>
                        <small>
                          Read more
                          <cite title='Here'><a href='{$anc['link']}' target='_blank' class='text-white font-size-16 font-weight-600'>Here</a></cite>
                        </small>
                      </footer>";
            }
            echo "</blockquote>
                  </div>";
        }
    }
    echo '</div>';
}

echo "</div>
</div>";
?>
<!-- if close -->
<?php }?>
<!-- updates info section -->
<div class="row update-info">
  <div class="col-12 col-md-12 ">
        <div class="timeline-content animation-slide-right">
            <div class="card card-shadow">
            <div class="card-header cover bg-light">
              <div class="cover-background py-30 px-10" style="background-image: url('../../../global/photos/placeholder.png');line-height: 3rem;">
                <blockquote class="blockquote cover-quote  card-blockquote" style="margin-top: 15px;">

                Hey there! <br>
                Thank you for using Edwiser RemUI! Loved the theme?<br> Share your reviews on <a class="comment-author orange-600 bold" href="https://edwiser.org/download_rating_review/remui/">Edwiser RemUI Site</a> <span class="bold">/</span>
                <a class="comment-author orange-600 bold" href="https://www.facebook.com/pg/EdwiserLMS/reviews/">Facebook</a> 
                <br>
                It’d mean a lot to us!<br><br>
                <p class="tab">We have a Facebook group of like-minded Moodlers where we have Productive discussions, Knowledge-sharing, and a lot more. It’s all happening in one place! </p>

                Join <a class="comment-author orange-600 bold" href="https://www.facebook.com/groups/MoodleTipsNTricks/">Moodle Tips and Tricks now!</a>
                </blockquote>
              </div>
            </div>
            </div>
        </div>
    </div>
</div>
