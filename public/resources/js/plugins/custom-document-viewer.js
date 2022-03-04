let view
let canvas

function initDraw() {
    view.init()
    view.draw()
}


function start() {
    canvas = document.getElementById('canvas')
    let img = document.getElementById("iframe");
    view = new Viewer(canvas, img)
    view.draw()
}


$(document).on('click', '.btn-form-document', function (evt) {
    var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'webp'];
    var file = $(this).data('document-url');
    file = file.replace(/^.*\./, '');
    if (fileExtension.includes(file)) {
        document_viewer(this)
        console.log(file)
    } else {
        $('.form-view').attr('src', $(this).data('document-url'))
        console.log(file)
    }
});


function document_viewer(data) {
    $('.form-view').attr('src', $(data).data('document-url'))
    /* $(".form-view").contents().find('body').html('');
    $(".form-view").contents().find('body').append(
        '<div class="btn-group" role="group" aria-label="Basic example">' +
        '<button type="button" class="btn btn-primary">Left</button>' +
        '<button type="button" class="btn btn-primary">Middle</button>' +
        '<button type="button" class="btn btn-primary">Right</button>' +
        '</div>' +
        '<img src="' + $(data).data('document-url') + '" alt="document-file" class="image-frame" width="100%">'
    ); */
}
