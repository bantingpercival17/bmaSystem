@section('subject-class-form')
    <form action="/administrator/subjects/class" method="post">
        @csrf
        <input type="hidden" name="_subject" value="{{ $_subject->id }}">
        <input type="hidden" name="_curriculum" value="{{ $curriculum->id }}">
        <input type="hidden" name="_academic" value="{{ $_academic->id }}">
        <tr>
            <td>
                {{ $_subject }}
                <select name="_teacher" class="form-control">
                    @foreach ($_teacher as $teacher)
                        <option value="{{ $teacher->staff->id }}">
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="_section" class="form-control">
                    @if ($_course_view->section([$_academic->id, $_level]))
                        @foreach ($_course_view->section([$_academic->id, $_level])->get() as $_section)
                            <option value="{{ $_section->id }}">
                                {{ $_section->section_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </td>
            <td>
                <button class="btn btn-info" type="submit"><i class="fa fa-plus"></i></button>
            </td>
        </tr>

    </form>
@endsection
