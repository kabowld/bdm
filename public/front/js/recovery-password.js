$(function () {
    $('form#form-recovery-passwd').on('submit', function (e) {
        e.preventDefault();

        const email = $('#email-recovery').val().trim();
        const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\]) |(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if (email === '' || !regex.test(email)) {
            $('.error-message-email').text('Veuillez saisir une adresse e-mail valide !');

            return false;
        }

        $.ajax({
            url: Routing.generate('recovery_password_bdmk'),
            method: 'POST',
            data: {email: email},
            dataType: 'json',
            beforeSend: function () {
                $('.error-message-email').text('');
            }
        }).done(function (data) {
            if (data.status === "success") {
                $('#form-recovery-passwd').hide();
                $('.libelle_recovery').hide();
                $('#recovery-box-password').append('<div class="alert alert-success text-center">'+data.message+'</div>');
            }
        }).fail(function (jqXHR) {
            $('.error-message-email').text(jqXHR.responseJSON.message);
        });
    });
});
