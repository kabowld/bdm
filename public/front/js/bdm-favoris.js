$(function () {
   $('.fav-ann').on('click', function (e) {
       e.preventDefault();
       const $link = $(this);
       const $annonceId = $(this).data('id');
       const $action = $(this).data('action');
       let $url = $action === 'enable'? Routing.generate('admin_enable_favoris_bdmk'): Routing.generate('admin_enable_favoris_bdmk');
       $.ajax({
           url: $url,
           method: 'POST',
           dataType: 'json',
           data: {action: $action, annonceId: $annonceId}
       }).done(function (data) {
           if (data.status === 'success') {
               if ($action === 'enable') {

                   $link.attr('data-action', 'disable')
                   $('.fav-ann[data-id="'+$annonceId+'"] .black-heart').removeClass('fa-heart-o').addClass('fa-heart');
               }else {

                   $link.attr('data-action', 'enable');
                   $('.fav-ann[data-id="'+$annonceId+'"] .black-heart').removeClass('fa-heart-o').addClass('fa-heart-o');
               }

           }
       });

   });

    $('.rm-fav').on('click', function (e) {
        e.preventDefault();
        const $annonceId = $(this).data('id');
        const $action = $(this).data('action');
        $.ajax({
            url: Routing.generate('admin_disable_favoris_bdmk'),
            method: 'POST',
            dataType: 'json',
            data: {action: $action, annonceId: $annonceId}
        }).done(function (data) {
            if (data.status === 'success') {
                $('#favx-ann-'+$annonceId).remove();
            }
        });

    });
});