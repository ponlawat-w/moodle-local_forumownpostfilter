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
 * Vault class for current user's discussion list.
 *
 * @package     local_forumownpostfilter
 * @copyright   2025 Ponlawat WEERAPANPISIT <ponlawat_w@outlook.co.th>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_forumownpostfilter;

/**
 * Current user's discussion list vault.
 */
class discussionlistvault extends \mod_forum\local\vaults\discussion_list {
    /**
     * Build the SQL to be used in get_records_sql (by extending the SQL conditions retrieved from base class).
     *
     * @param string|null $wheresql Where conditions for the SQL
     * @param string|null $sortsql Order by conditions for the SQL
     * @param int|null $userid The ID of the user we are performing this query for
     *
     * @return string
     */
    protected function generate_get_records_sql(?string $wheresql = null, ?string $sortsql = null, ?int $userid = null): string {
        global $USER;
        if (!is_numeric($USER->id)) {
            throw new \core\exception\moodle_exception('Invalid parameter', 'local_forumownpostfilter');
        }
        $sql = parent::generate_get_records_sql($wheresql, $sortsql, $userid);
        return str_replace(
            '{forum_discussions}',
            "(SELECT * FROM {forum_discussions} d WHERE userid = {$USER->id} OR EXISTS ("
                . "SELECT id FROM {forum_posts} p WHERE p.discussion = d.id AND userid = {$USER->id})"
                . ')',
            $sql
        );
    }
}
