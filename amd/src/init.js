import $ from 'jquery';

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
