<?php

namespace local_forumownpostfilter;

class discussionlistvault extends \mod_forum\local\vaults\discussion_list {
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
