$(document).ready(function() {

    var userID;
    // save button click event (for change password)
    $('#save-change').on('click', function() {
        $(".is-invalid").removeClass("is-invalid");

        $.post('/account/updatepass', {
            userID: userID,
            password: $('#change-password').val(),
            newpass: $('#change-newpass').val(),
            newpass2: $('#change-newpass2').val(),
        }, function(response) {
            let u = JSON.parse(response);
            console.log(u);
            if (u.status == 'invalid') {
                for (const [key, value] of Object.entries(u.errors)) {
                    $(`#change-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
            } else if (u.status == 'error') {
                Swal.fire(
                    '更改密码失败!',
                    u.msg,
                    'error'
                );
            } else if (u.status == 'success') {
                Swal.fire(
                    '更改密码成功!',
                    '',
                    'success'
                );
                setTimeout(function () {
                window.location.href = window.location.origin + "/";
                }, 1800);
            }
        });
    });
});