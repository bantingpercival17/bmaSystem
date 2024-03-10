<div class="card mt">
    <div class="card-body m-2 p-2">
        <div class="form-group">
            <small class="text-muted">COURSE OUTCOME</small> <br>
            <label for="" class="fw-bolder text-primary">{{$subjectTopic->course_outcome->course_outcome}}</label>
        </div>
        <div class="row">
            <div class="col-md">
                <small class="fw-bolder">TERM</small><br>
                <label for="" class="text-primary h5">{{ strtoupper($subjectTopic->term) }}</label>
            </div>
            <div class="col-md">
                <small class="fw-bolder">THEORETICAL</small><br>
                <label for="" class="text-primary h5">{{ strtoupper($subjectTopic->theoretical) }}</label>
            </div>
            <div class="col-md">
                <small class="fw-bolder">DEMONSTRATION</small><br>
                <label for="" class="text-primary h5">{{ strtoupper($subjectTopic->demonstration) }}</label>
            </div>
        </div>

    </div>
</div>