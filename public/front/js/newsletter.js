$(function () {

    $('#form-newsletter').submit(function(e) {
        e.preventDefault();
        const email = $('#input-newsletter').val().trim();
        const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\]) |(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if (email === '' || !regex.test(email)) {
            $('#answer-newsletter').text('Veuillez saisir une adresse e-mail valide !').css('background', '#bb2323');

            return false;
        }



        $.ajax({
            url: Routing.generate('send_newsletter_bdmk'),
            method: 'POST',
            data: {email: email},
            dataType: 'json',
            beforeSend: function () {
                $('#answer-newsletter').text('').css('background', 'transparent');
            }
        }).done(function (data) {
            if (data.status === "success") {
                $('#input-newsletter').val('');
                $('#answer-newsletter').text(data.message).css('background', '#009E60');
            }
        }).fail(function (jqXHR) {
            $('#answer-newsletter').text(jqXHR.responseJSON.message).css('background', '#F77F00');
        }).always(function () {
            setTimeout(function () {
                $('#answer-newsletter').text('').css('background', 'transparent');
            }, 5000);
        });
    });
});
