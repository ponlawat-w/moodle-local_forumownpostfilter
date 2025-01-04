<?php

defined('MOODLE_INTERNAL') || die();

$callbacks = [
    [
        'hook' => core\hook\output\before_footer_html_generation::class,
        'callback' => [\local_forumownpostfilter\hookcallbacks::class, 'before_html_footer_generation'],
    ],
];
