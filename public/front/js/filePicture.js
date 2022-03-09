$(function (){
   $('.del-uploaded-img').on('click', function () {
       const $box = $(this);
       const $id = $(this).data('id');
       console.log($id)
       $.ajax({
           url: Routing.generate('admin_annonce_del_filepicture'),
           method: 'POST',
           dataType: 'json',
           data: {id: $id}
       }).done(function (data){
           if (data.status === 'success') {
               $box.parent().remove()
           }
       }).fail(function(){

       })
   });
});
