@extends('layouts.app-main')
@section('page-title', 'Void Transaction')
@section('page-content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Transactions</h4>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mt-4">
                <table id="datatable" class="table table-striped" data-toggle="data-table">
                    <thead>
                        <tr>
                            <th>OR NUMBER</th>
                            <th>AMOUNT</th>
                            <th>REMARKS</th>
                            <th>TRANSACTION DATE</th>
                            <th>VOID REASON</th>
                            <th>TRANSACT BY</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($_void_list) > 0)
                            @foreach ($_void_list as $item)
                                <tr>
                                    <td>{{ $item->payment->or_number }}</td>
                                    <td>{{ $item->payment->payment_amount }}</td>
                                    <td>{{ $item->payment->remarks }}</td>
                                    <td>{{ $item->payment->transaction_date }}</td>
                                    <td>{{ base64_decode($item->void_reason) }}</td>
                                    <td>{{ $item->payment->staff->user->name }}</td>
                                    <td>
                                        @if ($item->is_approved === 1)
                                            <span class="badge bg-primary">APPROVED</span>
                                        @elseif($item->is_approved === 0)
                                            <span class="badge bg-danger">DISAPPROVED</span>
                                        @else
                                            <form action="{{ route('accounting.void-transaction') }}" method="post"
                                                id="form-approved">
                                                @csrf
                                                <input type="hidden" name="void" value="{{ base64_encode($item->id) }}">
                                                <input type="hidden" name="status" value="{{ base64_encode(1) }}">
                                                <button class="btn btn-primary btn-sm btn-remove"
                                                    type="submit"  data-form="form-approved">APPROVED</button>
                                            </form>
                                            <form action="{{ route('accounting.void-transaction') }}" method="post"
                                                id="form-disapproved">
                                                @csrf
                                                <input type="hidden" name="void"
                                                    value="{{ base64_encode($item->id) }}">
                                                <input type="hidden" name="status" value="{{ base64_encode(0) }}">
                                                <button class="btn btn-danger btn-sm btn-remove"
                                                    data-form="form-disapproved" type="submit">DISAPPROVED</button>
                                            </form>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@section('js')
    <script>
        $('.btn-remove').click(function(event) {
            Swal.fire({
                title: 'Void Transaction ',
                text: "Do you want to accept this Void Request?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {
                    console.log(form)
                    document.getElementById(form).submit()
                    //$('#' + form).submit();
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
@endsection
