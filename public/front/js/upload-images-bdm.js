$(function(){
    function previewBeforeUpload(id){
        let check = document.querySelector('#'+id);
        if (!check) {
            return false;
        }
        document.querySelector('#'+id).addEventListener('change', function(e){
            if(e.target.files.length === 0){
                return;
            }
            const allowed_ext = ['jpg','png'];
            let file_extension = e.target.value.split('.').pop().toLowerCase();
            const found = allowed_ext.find(el => el === file_extension);
            if (found === undefined) {
                alert('Veuillez télécharger des fichiers de type .jpg ou .png')
                e.target.value = '';

                return false;
            }

            let file = e.target.files[0];
            let url = URL.createObjectURL(file);
            document.querySelector("#"+id+"-preview div").innerText = file.name;
            document.querySelector("#"+id+"-preview img").src = url;
            $("#"+id+"-preview .delete-img").css('display', 'block');
        });
    }

    previewBeforeUpload('annonce_pictureOneFile');
    previewBeforeUpload('annonce_pictureTwoFile');
    previewBeforeUpload('annonce_pictureThreeFile');
    previewBeforeUpload('annonce_pictureFourFile');
    previewBeforeUpload('annonce_pictureFiveFile');
    previewBeforeUpload('annonce_pictureSixFile');
    previewBeforeUpload('annonce_pictureSevenFile');
    previewBeforeUpload('annonce_pictureHeightFile');
    previewBeforeUpload('profil_particular_avatarFile');

    $('.main-annonce label').on('click', 'span.delete-img', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $('#'+id).val('');
        $("#"+id+"-preview div").html('<span>+</span>');
        $("#"+id+"-preview img").attr('src', 'https://bit.ly/3ubuq5o');
        $(this).css('display', 'none')
    });

    $('.main-profil label').on('click', 'span.delete-img', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $('#'+id).val('');
        $("#"+id+"-preview div").html('<span><i class="fa fa-upload"></i></span>');
        $("#"+id+"-preview img").attr('src', '/front/images/avatar.png');
        $(this).css('display', 'none')
    });


});
