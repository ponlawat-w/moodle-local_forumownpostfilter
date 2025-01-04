<?php
namespace local_forumownpostfilter;

defined('MOODLE_INTERNAL') || die();

class hookcallbacks {
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
            $PAGE->requires->string_for_js('showonlymyposts', 'local_forumownpostfilter');
            $PAGE->requires->js_call_amd('local_forumownpostfilter/init', 'init', [$cmid]);
        }
    }
}
