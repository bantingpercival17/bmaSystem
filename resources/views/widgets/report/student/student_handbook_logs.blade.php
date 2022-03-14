<style>
    * {
        margin: 0px;
        padding: 0px;
    }

</style>
@foreach ($_data_logs as $_data)
    <p>
        {{ $_data->created_at }} :
        {{ $_data->student->account ? $_data->student->account->student_number : '' }} :
        {{ $_data->student->last_name }}, {{ $_data->student->first_name }} :
        {{ $_data->course->course_name }} / {{ $_data->year_level }} :
        
    </p>
@endforeach
