$(document).on('keyup', '.input-search', function (evt) {
    var _item = $(this).val(),
        _url = $(this).data('url') + '?search_student=' + _item,
        _container = "." + $(this).data('container'),
        _component = $(this).data('component'),
        _link = $(this).data('link')
    _layout = '';
    if (_item.length > 0) {
        $(_container).empty()
        $.get(_url, function (data) {
            if (data._students.length > 0) {
                $.each(data._students, function (key, value) {
                   
                    _label_content = _component == 'applicant-panel' ? value.first_name.trim().toUpperCase() + " " + value.last_name.trim().toUpperCase() : '';
                    _label_content = _component == 'or-number-panel' ? value.or_number : _label_content;
                    _label_content = _component == 'panel' ? value.last_name.trim().toUpperCase() + " " + value.first_name.trim().toUpperCase() + " | " + value.student_number : _label_content;
                    _layout = _component == 'applicant-panel' ? ' <a href="' + _link + value.id + '" class="btn btn-outline-success btn-block" style="text-decoration: none"> ' + _label_content + ' </a>' : '';
                    _layout = _component == 'or-number-panel' ? ' <a href="' + _link + value.id + '" class="btn btn-outline-success btn-block" style="text-decoration: none"> ' + _label_content + ' </a>' : _layout
                    _layout = _component == 'panel' ? ' <a href="' + _link + value.id + '" class="btn btn-outline-success btn-block" style="text-decoration: none"> ' + _label_content + ' </a>' : _layout;
                    _layout = _component == 'table' ? '<tr> <td>' + value.student_number + '</td> <td><a href="/onboard/student/' + value.id + '">' + _label_content + '</a></td> <td></td>' : _layout;
                    $(_container).append(_layout)
                })
            } else {
                _com_layout = _component == 'panel' ? ' <button  class="btn btn-outline-success btn-block" style="text-decoration: none"> STUDENT NOT FOUND </button>' : '';
                _com_layout = _component == 'table' ? '<tr><th colspan="3">Student Not Found</th></tr>' : _com_layout;
                $(_container).append(_com_layout)
            }

        })
    }
}) /* Search Function */
