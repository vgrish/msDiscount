function reloadTimer() {
    $.each($(".msd_remains"), function() {
        if (!$(this).attr('data-remain')) {
            var remains = $(this).text() - 1;
        } else {
            var remains = $(this).attr('data-remain') - 1;
        }
        $(this).attr('data-remain', remains);
        var days = Math.floor(remains/86400);
        var hours = Math.floor((remains - days * 86400) / 3600);
        var minutes = Math.floor((remains - days * 86400 - hours * 3600) / 60);
        var seconds = remains - days * 86400 - hours * 3600 - minutes * 60;
        $(this).text(days + ' days ' + hours + ' hours ' + minutes + ' minutes ' + seconds + ' seconds');
    });
    window.setTimeout("reloadTimer()",1000);
}
reloadTimer();