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
    $('.form-view').append($("<iframe>")).attr('src', '')
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
$(document).on('click', '.btn-photoviewer', function (evt) {
    const options = {
        index: $(this).index(),
        headerToolbar: ['minimize', 'maximize', 'close'],
        footerToolbar: ['prev', 'next', 'zoomIn', 'zoomOut', 'fullscreen', 'actualSize', 'rotateLeft', 'rotateRight'],
        modalWidth: 850,
        modalHeight: 600,
        callbacks: {
            beforeChange: function (context, index) {
                console.log(context, index);
            },
            changed: function (context, index) {
                console.log(context, index);
            }
        }
    };
    const items = [{
        src: $(this).data('link'),
        title: $(this).data('title')
    }];
    console.log($(this).data('link'))

    new PhotoViewer(items, options);
});
$('[data-gallery=photoviewer]').click(function (e) {

    e.preventDefault();
    console.log("Photo Viewer")
    var items = [],
        options = {
            index: $(this).index(),
        };

    $('[data-gallery=photoviewer]').each(function () {
        items.push({
            src: $(this).attr('href'),
            title: $(this).attr('data-title')
        });
    });

    new PhotoViewer(items, options);

});
$(document).on('click', 'btn-photoviewer-v2', function (evt) {
    entries.preventDefault()
    console.log("Photo Viewer")
    console.log($(this).data('link'))
});
