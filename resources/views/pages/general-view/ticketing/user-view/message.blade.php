@extends('layouts.app-main')
@php
$_title = 'Ticket Concern';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item ">
        <a href="/">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Dashboard
        </a>

    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_title }}
    </li>
@endsection
@section('css')
    <style>
        .chat-messages {
            display: flex;
            flex-direction: column;
            height: 300px;
            overflow-y: scroll;
        }

        .chat-message-left,
        .chat-message-right {
            display: flex;
            flex-shrink: 0;
        }

        .chat-message-left {
            margin-right: auto;
        }

        .chat-message-right {
            flex-direction: row-reverse;
            margin-left: auto;

        }

        .py-3 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        .px-4 {
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
        }

    </style>
@endsection
@section('page-content')
    <div class="card m-0">
        <div class="row">
            <div class="col-md-8 pe-0">
                @if (request()->input('_ticket'))
                    <div class="card m-0">
                        <div class="card-header d-flex align-items-center justify-content-between p-3">
                            <div class="header-title">
                                <div class="d-flex flex-wrap">
                                    <div class="media-support-user-img me-3">
                                        <img src="https://ui-avatars.com/api/?name={{ $_ticket->ticket->name }}"
                                            alt="header" class="img-fluid avatar avatar-40">
                                    </div>
                                    <div class="media-support-info">
                                        <h6 class="m-0 fw-bolder">{{ $_ticket->ticket->name }}</h6>
                                        <small>{{ $_ticket->ticket->ticket_number }}</small> |
                                        <small>{{ $_ticket->ticket->email }}</small>
                                        <br>

                                    </div>
                                </div>
                            </div>
                            <span>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('ticket.concern-unseen') }}?_concern={{ base64_encode($_ticket->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="tooltip"
                                        title="" data-bs-original-title="Mark as unread!">
                                        <svg width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.76045 14.3667C9.18545 13.7927 8.83545 13.0127 8.83545 12.1377C8.83545 10.3847 10.2474 8.97168 11.9994 8.97168C12.8664 8.97168 13.6644 9.32268 14.2294 9.89668"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M15.1049 12.6987C14.8729 13.9887 13.8569 15.0067 12.5679 15.2407"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path
                                                d="M6.65451 17.4722C5.06751 16.2262 3.72351 14.4062 2.74951 12.1372C3.73351 9.85823 5.08651 8.02823 6.68351 6.77223C8.27051 5.51623 10.1015 4.83423 11.9995 4.83423C13.9085 4.83423 15.7385 5.52623 17.3355 6.79123"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path
                                                d="M19.4473 8.99072C20.1353 9.90472 20.7403 10.9597 21.2493 12.1367C19.2823 16.6937 15.8063 19.4387 11.9993 19.4387C11.1363 19.4387 10.2853 19.2987 9.46729 19.0257"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M19.8868 4.24951L4.11279 20.0235" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('ticket.concern-remove') }}?_concern={{ base64_encode($_ticket->id) }}"
                                        class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Report as Spam">
                                        <svg width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path
                                                d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </div>
                            </span>

                        </div>

                        <div class="chat-messages p-4">
                            @foreach ($_ticket_concerns as $key => $concern)
                                @if ($key == 0)
                                    <div class="chat-message-left mb-2">
                                        <div class="d-flex">
                                            <img src="https://ui-avatars.com/api/?name={{ $concern->ticket->name }}"
                                                alt="header" class="img-fluid avatar avatar-40 rounded">
                                            <div class="ms-3">
                                                <small class="mb-1 fw-bolder text-primary">
                                                    {{ strtoupper($concern->ticket->name) }}
                                                </small>
                                                <div class="toast fade show bg-secondary text-white border-0 mb-1">
                                                    <div class="toast-body">
                                                        {{ $concern->ticket_message }}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <small class="mb-1">
                                                        {{ $concern->ticket->created_at->diffForHumans() }}
                                                    </small>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span
                                        class="mt-2 badge border border-info text-info mt-5 mb-5 rounded-pill">{{ $concern->ticket_message }}</span>
                                @endif
                                @if (count($concern->chat_message) > 0)
                                    @foreach ($concern->chat_message as $item)
                                        @if ($item->sender_column == 'staff_id')
                                            <div class="chat-message-right mb-2">
                                                <div class="d-flex">
                                                    <div class="ms-3">
                                                        <div class="row">

                                                            <small class="col-md mb-1 text-start">
                                                                {{ $item->created_at->diffForHumans() }}
                                                            </small>
                                                            <small class="col-md mb-1 fw-bolder text-primary text-end">
                                                                {{ $item->staff_id == Auth::user()->id ? 'YOU' : strtoupper($item->staff->first_name) }}
                                                            </small>
                                                        </div>
                                                        <div class="toast fade show bg-secondary text-white border-0">
                                                            <div class="toast-body">
                                                                @php
                                                                    echo $item->message;
                                                                @endphp
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($item->sender_column == 'ticket_id')
                                            <div class="chat-message-left">
                                                <div class="d-flex">
                                                    <img src="https://ui-avatars.com/api/?name={{ $item->concern->ticket->name }}"
                                                        alt="header" class="img-fluid avatar avatar-40 rounded">
                                                    <div class="ms-3">
                                                        <small class="mb-1 fw-bolder text-primary">
                                                            {{ strtoupper($item->concern->ticket->name) }}
                                                        </small>
                                                        <div class="toast fade show bg-secondary text-white border-0 mb-1">
                                                            <div class="toast-body">
                                                                @php
                                                                    echo $item->message;
                                                                @endphp
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <small class="mb-1">
                                                                {{ $item->created_at->diffForHumans() }}
                                                            </small>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                        </div>
                        <div class="card-footer">
                            <form action="" class="comment-text d-flex align-items-center mt-3 mb-3" id="chat-inputs">
                                <input type="text" id="message-input" class="form-control rounded-pill"
                                    placeholder="Compose message!">
                                {!! csrf_field() !!}
                                <input type="hidden" class="ticket" value="{{ $_ticket->id }}">
                                <input type="hidden" class="staff" value="{{ Auth::user()->staff->id }}">
                                <div class="comment-attagement d-flex">
                                    <button type="submit" class="btn btn-outline-primary rounded-pill btn-sm"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Send Message!">
                                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.8325 8.17463L10.109 13.9592L3.59944 9.88767C2.66675 9.30414 2.86077 7.88744 3.91572 7.57893L19.3712 3.05277C20.3373 2.76963 21.2326 3.67283 20.9456 4.642L16.3731 20.0868C16.0598 21.1432 14.6512 21.332 14.0732 20.3953L10.106 13.9602"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                            <div class="comment-text d-flex align-items-center mt-3 mb-3">
                                <div class="comment-attagement d-flex mt-3">
                                    <a href="{{ route('ticket.concern-solve') }}?_concern={{ base64_encode($_ticket->id) }}"
                                        class="btn btn-outline-secondary rounded-pill btn-sm me-2" data-bs-toggle="tooltip"
                                        title="" data-bs-original-title="Mark as solved">
                                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M8.43994 12.0002L10.8139 14.3732L15.5599 9.6272" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                    <a class="btn btn-outline-secondary rounded-pill btn-sm me-2" data-bs-toggle="modal"
                                        data-bs-target=".view-modal" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Attach Image">
                                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.8397 20.1642V6.54639" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M20.9173 16.0681L16.8395 20.1648L12.7617 16.0681" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M6.91102 3.83276V17.4505" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                            <path d="M2.8335 7.92894L6.91127 3.83228L10.9891 7.92894" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card-header p-3">
                        <div class="d-flex flex-wrap">
                            <div class="media-support-user-img me-3">
                                <img src="https://ui-avatars.com/api/?name=User Name" alt="header"
                                    class="img-fluid avatar avatar-40">
                            </div>
                            <div class="media-support-info mt-2">
                                <h6 class="mb-0">Select User</h6>

                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height: 100%">
                    </div>
                @endif
            </div>
            <div class="col-md-4 ps-0">
                <div class="card-header p-3">
                    <h6 class="card-title fw-bolder">TICKET CONCERN</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-inline ">
                        @if (count($_issues) > 0)
                            @foreach ($_issues as $data)
                                <li
                                    class="d-flex border-bottom p-2  {{ request()->input('_ticket') ? (request()->input('_ticket') == base64_encode($data->id) ? 'bg-primary rounded' : '') : '' }} ">
                                    <div class="news-icon me-3">
                                        <img src="https://ui-avatars.com/api/?name={{ $data->ticket->name }}"
                                            alt="header" class="img-fluid avatar avatar-50 rounded">
                                    </div>
                                    <div class="w-100">
                                        <a href="{{ route('ticket.view') }}?_ticket={{ base64_encode($data->id) }}">
                                            @if ($data->is_ongoing == 0)
                                                <span class="badge text-end bg-primary  float-end">NEW</span>
                                            @endif

                                            <div
                                                class="{{ request()->input('_ticket') ? (request()->input('_ticket') == base64_encode($data->id) ? 'text-white' : 'text-secondary') : 'text-secondary' }} ">

                                                <label class="m-0 ">{{ $data->ticket->name }}</label> <br>
                                                <small
                                                    class="float-end">{{ $data->ticket->created_at->diffForHumans() }}</small>
                                                <small class="mb-0">
                                                    {{ $data->ticket->ticket_number }}</small>

                                            </div>

                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="d-flex border-bottom  p-2">
                                <div>
                                    <h5 class="mb-2 text-center">No Concern</h5>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Transfer Concern</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="" class="fw-bolder">ISSUE NAME</label>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="fw-bolder">DEPARTMENT</label>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="fw-bolder">ACTION</label>
                        </div>
                    </div>
                    @foreach ($_ticket_issue as $item)
                        <div class="row mt-3">
                            <div class="col-md-4">
                                {{ $item->issue_name }}
                            </div>
                            <div class="col-md-4">
                                {{ $item->department->name }}
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('ticket.transfer-concern') . '?_ticket=' . request()->input('_ticket') . '&_transfer=' . base64_encode($item->id) }}"
                                    class="btn btn-outline-primary btn-sm">TRANSFER</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(".chat-messages").animate({
            scrollTop: 20000000
        }, "slow");

        $('#chat-inputs').on('submit', function(evt) {
            evt.preventDefault();
            var message = $('#message-input').val()
            if (message.trim().length == 0) {
                $('#message-input').focus()
            } else {
                var data = {
                    'ticket': $('.ticket').val(),
                    'staff': $('.staff').val(),
                    '_token': $('input[name="_token"]').val(),
                    'message': message
                };
                send_data(data)
                $('#message-input').val("")
            }
        })

        function send_data(data) {
            var html = append_chat(data)
            $.post("{{ route('ticket.chat-store') }}", data, function(respond) {
                if (respond.data.respond == 200) {
                    $('.chat-messages').append(html)
                    $('.chat-messages').animate({
                        scrollTop: $('.chat-messages').prop("scrollHeight")
                    }, 1000)
                }
                if (respond.data.respond == 404) {
                    //$('.chat-messages').append(html)
                    console.log(respond.data.message)
                }
            })
        }

        function append_chat(data) {
            return `<div class="chat-message-right">
                    <div class="d-flex">
                        <div class="ms-3">
                            <div class="d-flex flex-wrap ">
                                <small class="mb-1 fw-bolder text-primary">
                                    YOU
                                </small>
                            </div>
                            <div class="toast fade show bg-secondary text-white border-0">
                                <div class="toast-body">
                                   ${data.message}
                                </div>
                            </div>
                            <div class="d-flex flex-wrap float-end">

                                <small class="mb-1 text-end">
                                    now
                                </small>

                            </div>
                        </div>
                    </div>
                </div>`;
        }
    </script>
@endsection
