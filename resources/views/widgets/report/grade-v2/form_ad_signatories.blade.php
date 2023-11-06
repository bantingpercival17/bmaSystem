<div class="signatories">
    <br>
    <table class="table table-header ">
        <tbody>
            <tr>
                <td>
                    PREPARED BY:
                </td>
                <td>
                    VALIDATED BY:
                </td>
                <td>
                    APPROVED BY:
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td>
                    <u>
                        <b>{{ strtoupper($subject->staff->first_name . ' ' . $subject->staff->last_name) }}</b>
                    </u>
                </td>
                <td>
                    <u>
                        @if ($subject->finals_grade_submission)
                        <b>{{ strtoupper($subject->finals_grade_submission->approved_by) }}</b>
                        @endif

                    </u>
                </td>
                <td>
                    <u>
                        <b>
                            {{ strtoupper('Capt. Maximo PestaÑo') }}
                        </b>
                    </u>
                </td>
            </tr>
            <tr>
                <td><small>Subject Teacher</small> </td>
                <td><small>Department Head</small> </td>
                <td><small>Dean of Maritime Studies</small> </td>
            </tr>
        </tbody>
    </table>
</div>