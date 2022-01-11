$(function () {
    $('#annonce_type_0').attr('checked', 'checked');
    $('.mm-navbar__title').text('Navigation');

    $('.select-field').select2();


    $('[data-toggle="tooltip"]').tooltip();


    /* ==== recovery password === */
    $('form#form-recovery-passwd').on('submit', function (e) {
        e.preventDefault();
        const email = $('#email-recovery').val();
        if (email === '') {
            console.error('saisir email');

            return false;
        }
        $.ajax({
            url: Routing.generate('recovery_password_bdmk'),
            method: 'POST',
            data: {email: email},
            dataType: 'json'
        }).done(function (data) {
            if (data.state === "success") {
                $('#form-recovery-passwd').hide();
                $('#recovery-box-password').append('<div class="alert alert-success text-center">'+data.message+'</div>');
            }
        }).fail(function (jqXHR) {
            console.log(jqXHR.responseJSON.message);
            console.error('Erreur email');
        });
    });

    /*=== Front handle password register page ===**/
    $('.eye-icon-passwd').on('click', function(e) {
        $(this).toggleClass('visible-eye-password');
        if ($(this).hasClass('visible-eye-password')) {
            $('#exampleInputPassword4').attr('type', 'text');
        } else  {
            $('#exampleInputPassword4').attr('type', 'password');

        }
    });

    $('.eye-icon-passwd-register').on('click', function (e) {
        $(this).toggleClass('visible-eye-password');
        if ($(this).hasClass('visible-eye-password')) {
            $('#registration_particular_password_first').attr('type', 'text');
        } else  {
            $('#registration_particular_password_first').attr('type', 'password');

        }
    });

    $('.eye-icon2-passwd-register').on('click', function (e) {
        $(this).toggleClass('visible-eye-password');
        if ($(this).hasClass('visible-eye-password')) {
            $('#registration_particular_password_second').attr('type', 'text');
        } else  {
            $('#registration_particular_password_second').attr('type', 'password');

        }
    });

    $('.eye-icon-passwd-pro-register').on('click', function (e) {
        $(this).toggleClass('visible-eye-password');
        if ($(this).hasClass('visible-eye-password')) {
            $('#registration_password_first').attr('type', 'text');
        } else  {
            $('#registration_password_first').attr('type', 'password');

        }
    });

    $('.eye-icon2-passwd-pro-register').on('click', function (e) {
        $(this).toggleClass('visible-eye-password');
        if ($(this).hasClass('visible-eye-password')) {
            $('#registration_password_second').attr('type', 'text');
        } else  {
            $('#registration_password_second').attr('type', 'password');

        }
    });


    $('#registration_particular_password_first').on('keyup', function(e){
        $('.rgx-passwd').fadeIn('400');
    });

    $('#registration_password_first').on('keyup', function (e) {
        $('.rgx-passwd').fadeIn('400');
    })

    $('#registration_particular_password_second').on('keyup', function (e) {
        const pass1 = $('#registration_particular_password_first').val();
        const pass2 = $(this).val();
        if (pass1 !== pass2) {
            $('.actly-passwd').fadeIn('400');
        } else {
            $('.actly-passwd').fadeOut('400');
        }
    });

    $('#registration_password_second').on('keyup', function (e) {
        const pass1 = $('#registration_password_first').val();
        const pass2 = $(this).val();
        if (pass1 !== pass2) {
            $('.actly-passwd').fadeIn('400');
        } else {
            $('.actly-passwd').fadeOut('400');
        }
    });


    function owlCarouselHome() {
        $(".owl-carousel.at-one").owlCarousel({
            loop:true,
            margin: 10,
            responsiveClass:true,
            responsive:{
                0:{
                    items:2,
                    nav:false,
                    dots: true
                },
                768:{
                    items:3,
                    nav:true,
                    dots: false,
                },
                992:{
                    items:5,
                    nav:true,
                    dots: false
                }
            },
            autoplay: true,
            autoplayTimeout: 10000,
            navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>']
        });
    }

    $('#annonce_price').on('keyup', function () {
        let $value = $(this).val();
        $(this).val($value.replace(/\s+/g, ""));
    });

    const route_pack_info = 'admin_pack_info_bdmk';

    function getNoImageInfo() {
        $('.img-pack-infos').attr('src', '/front/images/no-image.jpg').attr('alt', 'aucune image');
        $('.desc-pack-infos').text('');
        $('.title-pck-infos').text('');
        $('.price-pack-infos').text('');
        $('.priceByDays-pack-infos').text('');
    }

    function getPackInfo(filename, title, description, price, priceByDays) {
        const src = '/front/pack/'+ filename;
        $('.img-pack-infos').attr('src', src).attr('alt', title);
        $('.desc-pack-infos').text(description);
        $('.title-pck-infos').text(title);
        $('.price-pack-infos').text(price + 'F CFA');
        $('.priceByDays-pack-infos').text(priceByDays);
    }

    $('#annonce_pack').on('change', function () {
        if ($('#annonce_pack').val() !== '') {
            let $id = parseInt($(this).val());
            $.ajax({
                url: Routing.generate(route_pack_info),
                method: 'POST',
                dataType: 'json',
                data: {id: $id}
            }).done(function (data) {
                getPackInfo(data.filename, data.title, data.description, data.price, data.priceByDays);
            }).fail(function (jqXHR) {
                getNoImageInfo();
            });
        } else {
            getNoImageInfo();
        }
    });

    if ($('#annonce_pack').val() !== undefined && $('#annonce_pack').val() !== '') {
        let $id = parseInt($('#annonce_pack').val());
        $.ajax({
            url: Routing.generate(route_pack_info),
            method: 'POST',
            dataType: 'json',
            data: {id: $id}
        }).done(function (data) {
            getPackInfo(data.filename, data.title, data.description, data.price, data.priceByDays);
        }).fail(function (jqXHR) {
            getNoImageInfo();
        });
    }

    owlCarouselHome();
});
