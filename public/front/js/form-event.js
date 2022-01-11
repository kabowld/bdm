$(function (){
    const $rubrique = $('#annonce_rubrique');
    //$('#annonce_category').attr('disabled', 'disabled');
    $('#state-add-form #annonce_state').attr('disabled', 'disabled');

    $rubrique.change(function() {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected sport value.
        var data = {};
        data[$rubrique.attr('name')] = $rubrique.val();
        // Submit data via AJAX to the form's action path.
        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            beforeSend: function () {
                $('#annonce_category').attr('disabled', 'disabled');
                $('#annonce_category').val('');
                $('#annonce_state').attr('disabled', 'disabled');
                $('#annonce_state').val('');
            },
            complete: function (html) {
                // Replace current position field ...
                $('#annonce_category').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html.responseText).find('#annonce_category')
                );
                $('#annonce_state').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html.responseText).find('#annonce_state')
                );
                $('#annonce_category').removeAttr('disabled');
                $('#annonce_state').removeAttr('disabled');
                // Position field now displays the appropriate positions.
            }
        });

        if ($('#annonce_rubrique').val() !== '') {
            $.ajax({
                url: Routing.generate('admin_rubrique_img_bdmk'),
                method: 'POST',
                dataType: 'json',
                data: {id: parseInt($rubrique.val())}
            }).done(function (data) {
                let $src = '/front/rubfiles/'+ data.filename;
                $('.img-rubrique').attr('src', $src);
            }).fail(function (jqXHR) {
                console.log(jqXHR);
                $('.img-rubrique').attr('src', $src);
                $('.img-rubrique').attr('alt', data.slug);
            });
        } else {
            $('.img-rubrique').attr('src', '');
            $('.img-rubrique').attr('alt', '');
        }
    });
});
