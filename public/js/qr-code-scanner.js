setInterval(() => {
    let currentDate = new Date();
    $('.real-time').text(currentDate)
    /*   table_data(); */
}, 500);

/* Creating a new scanner object. */
var scanner = new Instascan.Scanner({
    video: document.getElementById('preview'),
    scanPeriod: 5,
    mirror: true
});

/* Checking if there is a camera available. */
Instascan.Camera.getCameras().then(function (cameras) {
    if (cameras.length > 0) {
        scanner.start(cameras[0]);
        $('[name="options"]').on('change', function () {
            if ($(this).val() == 1) {
                if (cameras[0] != "") {
                    scanner.start(cameras[0]);
                } else {
                    alert('No Front camera found!');
                }
            } else if ($(this).val() == 2) {
                if (cameras[1] != "") {
                    scanner.start(cameras[1]);
                } else {
                    alert('No Back camera found!');
                }
            }
        });
    } else {
        console.error('No cameras found.');
        alert('No cameras found.');
    }
}).catch(function (e) {
    console.error(e);
});

/**
 * If the data is true, start the camera, otherwise stop the camera
 * @param data - The value of the checkbox.
 * @returns the result of the ternary operator.
 */
function statusCamera(data) {
    return data ? scanner.start() : scanner.stop();
}

/**
 * This function takes in a title, message, and icon, and displays a notification to the user
 * @param title - The title of the notification
 * @param message - The message you want to display.
 * @param icon - success, error, warning, info, question
 */
function alertNotification(title, message, icon) {
    let timerInterval
    Swal.fire({
        title: title,
        text: message,
        icon: icon,
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false,
    })
}
scanner.addListener('scan', function (content) {
    if (user) {
        if (user == 'employee') {
            scannerQrcodeEmployee(content)
        }

        if (user == 'student') {
            scannerQrcodeStudent(content)
        }
    } else {
        alertNotification('Select User!', "Please select a User", 'warning')
        audio_error.play()
        user_select.play()
    }
});

function scannerQrcodeStudent(_data) {
    //audioCadetTimeIn.play()
    $.get('/executive/attendance-checket/scan-code/' + user + '/' + _data, function (res) {
        //console.log(res);
        var res = res._data;
        if (res.respond == 200) {
            var file_name = res.details.link
            var audio_custom = new Audio(file_name);
            audio_success.play()
            audio_custom.play()
            alertNotification(res.time_status, res.message, 'success')
            display_data('student', res)
            //tableDisplayData()
        }
        if (res.respond == 404) {
            var file_name = res.details.link
            var audio_custom = new Audio(file_name);
            audio_error.play()
            audio_custom.play()
            alertNotification('Error!', res.message, 'error')
        }
    }).fail(function () {
        var audio_custom = new Audio("{{ asset('assets/audio/invalid_qr_code_1.mp3') }}");
        audio_error.play()
        audio_custom.play()
        alertNotification('Error!', 'Invalid QR Code', 'error')
        clear_details()
    })
}

function scannerQrcodeEmployee(_data) {
    //table_data()
    url = '/executive/attendance-checket/scan-code/' + user + '/' + _data;
    $.get(url, function (data) {
        console.log(data._data)
        if (data._data.respond == 200) {
            //toastr.success(data._data.message, data._data.data.time_status)
            audio_success.play()
            display_data('employee', data._data);
            //employee_details(data._data)
            var file_name = data._data.data.link
            var audio_custom = new Audio(file_name);
            audio_custom.play()
            alertNotification(data._data.data.time_status, data._data.message, 'success')
        }
        if (data._data.respond == 404) {
            audio_error.play()
            //toastr.error(data._data.message, 'Error!')
            var file_name = data._data.data.link
            var audio_custom = new Audio(file_name);
            audio_custom.play()
            alertNotification('Error!', data._data.message, 'error')
            clear_details()
        }
    }).fail(function () {
        Swal.fire({
            title: 'Error!',
            text: 'Invalid QR Code',
            icon: 'error',
        })
        // toastr.error('Invalid QR Code.', 'Error!')
        audio_error.play()

        audio_custom.play()
        clear_details()
    })

}

function display_data(data, _data) {
    if (data == 'employee') {
        table_data()
        $('.text-status').text(_data.data.time_status);
        $('.text-time').text(_data.data.time)
        $('.text-name').text(_data.data.name);
        $('.text-department').text(_data.data.department + ' OFFICE')
        $('.image').attr('src', "/assets/img/staff/" + _data.data.image)
        console.log(timeConvert(_data.data.attendance_details.time_in));
        var time_in = _data.data.attendance_details.time_in != null ? (_data.data.attendance_details.time_in) : '- - : - -';
        $('.employee-time-in').text(time_in)
        var time_out = _data.data.attendance_details.time_out != null ? (_data.data.attendance_details.time_out) : '- - : - -';
        $('.employee-time-out').text(time_out)
        //console.log(_data);
    } else {
        tableDisplayData()
        $('.student-name').text(_data.details.student_name);
        $('.student-course').text(_data.details.student_course);
        $('.student-level').text(_data.details.student_section);
        $('.card-img').attr('src', _data.details.image)
        var time_in = _data.details.student_attendance.time_in != null ? (_data.details.student_attendance.time_in) : '- - : - -';
        $('.student-time-in').text(time_in)
        var time_out = _data.details.student_attendance.time_out != null ? (_data.details.student_attendance.time_out) : '- - : - -';
        $('.student-time-out').text(time_out)
    }

}

function imageError() {
    $('.image').attr('src', "/assets/img/student-picture/midship-man.jpg")
}

function clear_details() {
    $('.text-status').text("TIME");
    $('.text-time').text('TIME IN / TIME OUT')
    $('.text-name').text("NAME OF EMPLOYEE");
    $('.text-department').text('OFFICE DEPARTMENT')
    $('.image').attr('src', "http://bma.edu.ph:70/assets/img/staff/avatar.png")
}

function timeConvert(date) {
    /*  myDate = date.split("-");
     var newDate = new Date(myDate[2], myDate[1] - 1, myDate[0]);
     return newDate.getTime(); */
    var date = new Date(date);
    //return datum / 1000;
    //return date.getTime();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var minutes = date.getSeconds();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes + ':' + minutes + ' ' + ampm;
    return strTime;

}

function table_data() {
    $.get('/executive/fetch-attendance', function (respond) {
        $('.table-body-100').empty()
        if (respond._data.length == 0) {
            $('.table-body-100').append(
                "<tr>" +
                "<td colspan='3'> <b> No Data </b> </td>" +
                "</tr>"
            );
        } else {
            respond._data.forEach(data => {
                var time_out = data.time_out != null ? data.time_out : '-';
                $('.table-body-100').append(
                    "<tr>" +
                    "<td>" + data.first_name + " " + data.last_name + "</td>" +
                    "<td>" + (data.time_in) + "</td>" +
                    "<td>" + (time_out) + "</td>" +
                    /*  "<td>" + timeConvert(data.time_in) + "</td>" +
                     "<td>" + timeConvert(time_out) + "</td>" + */

                    "</tr>"
                );
            });
        }
    });
}

function tableDisplayData() {
    $.get('/executive/fetch-attendance?_user=' + user, function (respond) {
        $('.table-body-100').empty()
        //console.log(respond._data)
        if (respond._data.length == 0) {
            $('.table-body-100').append(
                "<tr>" +
                "<td colspan='3'> <b> NO DATA </b> </td>" +
                "</tr>"
            );
        } else {
            respond._data.forEach(data => {
                var time_out = data.time_out != null ? data.time_out : '- - : - -';
                var name = data.student.first_name + " " + data.student.last_name

                $('.table-body-100').append(
                    "<tr>" +
                    "<td>" + name + "</td>" +
                    "<td>" + data.student.current_section.section.section_name + "</td>" +
                    "<td>" + data.time_in + "</td>" +
                    "<td>" + time_out + "</td>" +
                    "</tr>"
                );
            });
        }
    });
}
