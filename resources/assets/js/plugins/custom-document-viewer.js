$(document).on('click', '.btn-form-document', function (evt) {
    var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'webp'];
    var file = $(this).data('document-url');
    file = file.replace(/^.*\./, '');
    if (fileExtension.includes(file)) {
      /*   $(".form-view").contents().find("body").html('');
        $('.form-view').contents().find('body').append($("<img/>").attr('class', 'image-frame').attr("src",
            $(this).data('document-url')).attr("title",
            "sometitle").attr('width', '100%')) */
        console.log(file)
    } else {
      /*   $('.form-view').attr('src', $(this).data('document-url')) */
        console.log(file)
    }

});


