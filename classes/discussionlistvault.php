<?php

namespace local_forumownpostfilter;

class discussionlistvault extends \mod_forum\local\vaults\discussion_list {
    protected function generate_get_records_sql(?string $wheresql = null, ?string $sortsql = null, ?int $userid = null): string {
        global $USER;
        $sql = parent::generate_get_records_sql($wheresql, $sortsql, $userid);
        return str_replace(
            '{forum_discussions}',
            "(SELECT * FROM {forum_discussions} WHERE userid = {$USER->id})",
            $sql
        );
    }
}
