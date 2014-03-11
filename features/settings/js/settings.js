$(document).ready(function() {
    $('#update').click(function() {
        if ($(this)[0].value == 'Update!') {
            $(this)[0].value = 'Updating...';
            $(this)[0].setAttribute('disabled', 'disabled');
            $('#update-result').html(
                '<img src="themes/admin/img/loading.gif" align="middle" ' +
                    'style="margin-top:-10px; height:24px" />' +
                '<span style="padding-left:10px;">Please wait while your system updates...</span>'
            );

            setTimeout(function() {
                theamus.ajax.run({
                   url: 'settings/update/',
                   result : 'update-result'
                });
            }, 1000);
        } else {
            $(this)[0].value = 'Checking...';
            theamus.ajax.run({
               url: 'settings/check-update/',
               result : 'update-result'
            });
        }
    });
});

function backToCheck() {
    $('#update')[0].value = 'Check for Updates';
}

function prepareUpdate() {
    $('#update')[0].value = 'Update!';
}

function finishUpdate() {
    $('#update')[0].value = 'Done!';
    reload(1000);
}