function showAjaxLoading(message, fullid, status) {
    let id = fullid.split('_')[1];
    let type = fullid.split('_')[2];
    $('#showajax_feed_' + id + '_' + type).empty();
    if (status) {
        $('#showajax_feed_' + id + '_' + type).append(`
            <img src="/assets/images/loader.gif" width="30px" style="width: 60px" alt="" srcset="">
        `);
    }

}
function showAjaxSuccess(message, fullid) {
    let id = fullid.split('_')[1];
    let type = fullid.split('_')[2];
    $('#showajax_feed_' + id + '_' + type).empty();

    $('#showajax_feed_' + id + '_' + type).append(`
    <span class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-all me-2"></i>
        ${message}
    </span>
    `);

    setTimeout(() => {
        $('#showajax_feed_' + id + '_' + type).empty();
    }, 3000);
}

function showAjaxError(message, fullid) {
    let id = fullid.split('_')[1];
    let type = fullid.split('_')[2];
    $('#showajax_feed_' + id + '_' + type).empty();

    $('#showajax_feed_' + id + '_' + type).append(`
    <span class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-block-helper me-2"></i>
        ${message}
    </span>
    `);

    setTimeout(() => {
        $('#showajax_feed_' + id + '_' + type).empty();
    }, 3000);
}
