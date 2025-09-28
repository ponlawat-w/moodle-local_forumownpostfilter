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
 * Hook callbacks.
 *
 * @package     local_forumownpostfilter
 * @copyright   2025 Ponlawat WEERAPANPISIT <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_forumownpostfilter;

/**
 * Hook callbacks class.
 */
class hookcallbacks {
    /**
     * Call injector JS module in forum view page.
     */
    public static function before_html_footer_generation() {
        global $PAGE;
        /** @var \moodle_page $PAGE */
        $PAGE;
        if (isloggedin() && $PAGE->url->get_path() == '/mod/forum/view.php') {
            $cmid = optional_param('id', 0, PARAM_INT);
            $forumid = optional_param('f', 0, PARAM_INT);
            if (!$cmid && $forumid) {
                $cmid = get_coursemodule_from_instance('forum', $forumid)->id;
            }
            $PAGE->requires->js_call_amd('local_forumownpostfilter/init', 'init', [$cmid]);
        }
    }
}
