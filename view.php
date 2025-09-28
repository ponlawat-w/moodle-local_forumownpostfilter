<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Display discussions created by the current user, using discussion_list template as from /mod/forum/view.php.
 *
 * @package     local_forumownpostfilter
 * @copyright   2025 Ponlawat WEERAPANPISIT <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\exception\moodle_exception;
use core\output\html_writer;

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/discussionlistvault.php');

$cmid = required_param('id', PARAM_INT);
$module = get_coursemodule_from_id('forum', $cmid, 0, false, MUST_EXIST);

$managerfactory = mod_forum\local\container::get_manager_factory();
$forumvaultfactory = mod_forum\local\container::get_vault_factory();
$forum = $forumvaultfactory->get_forum_vault()->get_from_course_module_id($cmid);
$capibilitymanager = $managerfactory->get_capability_manager($forum);

/** @var \moodle_page $PAGE */ $PAGE;
/** @var \core\output\core_renderer $OUTPUT */ $OUTPUT;

$PAGE->set_url('/local/forumownpostfilter/view.php', ['cmid' => $cmid]);

$course = $forum->get_course_record();
$cm = cm_info::create($module);

require_course_login($course, true, $cm);

if (!$capibilitymanager->can_view_discussions($USER)) {
    throw new moodle_exception('error/nopermissiontoviewforum', 'forum');
}

$vaultfactory = mod_forum\local\container::get_vault_factory();
$discussionvault = new \local_forumownpostfilter\discussionlistvault(
    $DB,
    mod_forum\local\container::get_entity_factory(),
    mod_forum\local\container::get_legacy_data_mapper_factory()->get_legacy_data_mapper_for_vault('discussion')
);

$discussions = $discussionvault->get_from_forum_id(
    $forum->get_id(),
    $capibilitymanager->can_view_hidden_posts($USER),
    $USER->id,
    null,
    1_000,
    0
);
$forumexporter = mod_forum\local\container::get_exporter_factory()->get_forum_exporter($USER, $forum, null);
$templatecontext = [
    'forum' => (array)$forumexporter->export($PAGE->get_renderer('mod_forum')),
    'contextid' => $forum->get_context()->id,
    'cmid' => $cm->id,
    'name' => format_string($forum->get_name()),
    'courseid' => $course->id,
    'coursename' => format_string($course->shortname),
    'totaldiscussioncount' => count($discussions),
    'userid' => $USER->id,
    'visiblediscussioncount' => count($discussions),
];
if (count($discussions)) {
    $templatecontext = array_merge(
        $templatecontext,
        mod_forum\local\container::get_builder_factory()
            ->get_exported_discussion_summaries_builder()
            ->build($USER, $forum, $discussions)
    );
}
foreach ($templatecontext['forum']['urls'] as $key => $url) {
    $templatecontext['forum']['urls'][$key] = str_replace('/mod/forum/view.php', '/local/forumownpostfilter/view.php', $url);
}

$PAGE->set_context($forum->get_context());
$PAGE->set_title($forum->get_name());
$PAGE->add_body_class('forumtype-' . $forum->get_type());
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo html_writer::link(
    new \core\url('/mod/forum/view.php', ['id' => $cm->id]),
    get_string('back'),
    ['class' => 'btn btn-secondary']
);
echo $OUTPUT->render_from_template('mod_forum/discussion_list', $templatecontext);
echo $OUTPUT->footer();
