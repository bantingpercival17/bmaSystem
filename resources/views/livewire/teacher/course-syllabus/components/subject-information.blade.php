<div class="card mt-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <small>COURSE CODE</small> <br>
                <label for="" class="fw-bolder text-primary">{{ $course_syllabus->subject->subject_code }}</label>
            </div>
            <div class="col-md">
                <small>COURSE DESCRIPTIVE TITLE</small> <br>
                <label for="" class="fw-bolder text-primary">{{ $course_syllabus->subject->subject_name }}</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <small>COURSE CREDITS</small> <br>
                <label for="" class="fw-bolder text-primary">{{ $course_syllabus->subject->units }}
                    UNIT/S</label>
            </div>
            <div class="col-md">
                <small>LECTURE HOURS</small> <br>
                <label for="" class="fw-bolder text-primary">{{ $course_syllabus->subject->lecture_hours }}
                    HOUR/S</label>
            </div>
            <div class="col-md">
                <small>LABORATORY HOURS</small> <br>
                <label for="" class="fw-bolder text-primary">{{ $course_syllabus->subject->laboratory_hours }}
                    HOUR/S</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <small>COURSE DESCRIPTION</small> <br>
                <label for="" class="fw-bolder">{{ $course_syllabus->course_description }}</label>
            </div>
        </div>
    </div>
</div>