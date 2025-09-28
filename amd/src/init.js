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
 * JavaScript modules
 *
 * @module      local/forumownpostfilter
 * @copyright   2025 Ponlawat Weerapanpisit <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

/**
 * Injects button to the page.
 * @param {number} cmid Course Module ID
 */
export const init = (cmid) => {
    $(() => {
        const $discussions = $(`[data-cmid=${cmid}]`);
        if (!$discussions.length) {
            return;
        }
        const $btn = $('<a class="btn btn-secondary">')
            .text(M.str.local_forumownpostfilter.showonlymyposts)
            .attr('href', '/local/forumownpostfilter/view.php?id=' + cmid);
        const $panel = $('<div class="local-forumownpostfilter-panel"></div>')
            .append($btn);
        $discussions.prepend($panel);
    });
};
