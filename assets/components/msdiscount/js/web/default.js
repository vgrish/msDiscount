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
        if (hours < 10) hours = '0' + hours;
        if (minutes < 10) minutes = '0' + minutes;
        if (seconds < 10) seconds = '0' + seconds;
        $(this).html('<span class="days">' + days + '</span><span class="hours">' + hours + '</span><span class="minutes">' + minutes + '</span><span class="seconds">' + seconds + '</span>');
    });
    window.setTimeout("reloadTimer()",1000);
}
reloadTimer();