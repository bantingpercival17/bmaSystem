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
        /* $('.form-view').append('<iframe src="' + $(this).data('document-url') + '" width="100%" height="600px">' +
            '</iframe>') */
        $('.form-view').append($("<iframe>")).attr('src', $(this).data('document-url'))
        console.log(file)
    }
});


function document_viewer(data) {
    $(".form-view").contents().find("body").html('');
    $('.form-view').contents().find('body').append($("<img/>").attr('class', 'image-frame')
        .attr("id", "frame-image")
        .attr("src", $(data).data('document-url'))
        .attr("title", "sometitle").attr('width', '100%'))
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

function rotateImg(data) {
    document.querySelector("#frame-image").style.transform = "rotate(" + data + "deg)";
}
