$(function () {
    function systemAllowUpload() {
        let inputFiles = [
            'annonce_pictureOneFile',
            'annonce_pictureTwoFile',
            'annonce_pictureThreeFile',
            'annonce_pictureFourFile',
            'annonce_pictureFiveFile',
            'annonce_pictureSixFile',
            'annonce_pictureSevenFile',
            'annonce_pictureHeightFile'
        ];

        const total = 8;
        let files = $('.img-uploaded-annonce').length;

        if (files > 0) {
            let items = total - files;
            for (let i = 0; i < items; i++) {
                inputFiles.shift();
            }

            inputFiles.forEach(element => {
                $('#'+element).attr('disabled', 'disabled');
            });
        }
    }

   $('.del-uploaded-img').on('click', function () {
       const $box = $(this);
       const $id = $(this).data('id');
       $.ajax({
           url: Routing.generate('admin_annonce_del_filepicture'),
           method: 'POST',
           dataType: 'json',
           data: {id: $id}
       }).done(function (data){
           if (data.status === 'success') {
               $box.parent().remove();
               let inputs = $('input:file[disabled="disabled"]');
               $(inputs[0]).removeAttr('disabled');
           }
       });
   });

    systemAllowUpload();
});
