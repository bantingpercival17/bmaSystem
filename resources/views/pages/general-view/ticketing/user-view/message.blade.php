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
                                        <small>{{ $_ticket->ticket->ticket_number }}</small> | <small
                                            class="m-0 text-primary ">{{ $_ticket->ticket_issue->issue_name }}</small>

                                    </div>
                                </div>
                            </div>
                            <span>
                                {{ $_ticket->ticket->updated_at->diffForHumans() }}
                            </span>

                        </div>

                        <div class="chat-messages p-4">
                            <div class="chat-message-left">
                                <div class="d-flex">
                                    <img src="https://ui-avatars.com/api/?name={{ $_ticket->ticket->name }}" alt="header"
                                        class="img-fluid avatar avatar-40 rounded">
                                    <div class="ms-3">
                                        <small class="mb-1 fw-bolder text-primary">
                                            {{ strtoupper($_ticket->ticket->name) }}
                                        </small>
                                        <div class="toast fade show bg-secondary text-white border-0 mb-1">
                                            <div class="toast-body">
                                                {{ $_ticket->ticket_message }}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <small class="mb-1">
                                                {{ $_ticket->ticket->created_at->diffForHumans() }}
                                            </small>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (count($messages) > 0)
                                @foreach ($messages as $item)
                                    @if ($item->sender_column == 'staff_id')
                                        <div class="chat-message-right">
                                            <div class="d-flex">
                                                <div class="ms-3">
                                                    <div class="d-flex flex-wrap ">
                                                        <small class="mb-1 fw-bolder text-primary">
                                                            {{ strtoupper($item->staff->job_description) }}
                                                        </small>
                                                    </div>
                                                    <div class="toast fade show bg-secondary text-white border-0">
                                                        <div class="toast-body">
                                                            {{ $item->message }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-wrap float-end">

                                                        <small class="mb-1 text-end">
                                                            {{ $item->created_at->diffForHumans() }}
                                                        </small>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($item->sender_column == 'ticket_id')
                                        <div class="chat-message-left">
                                            <div class="d-flex">
                                                <img src="https://ui-avatars.com/api/?name={{ $item->ticket->name }}"
                                                    alt="header" class="img-fluid avatar avatar-40 rounded">
                                                <div class="ms-3">
                                                    <small class="mb-1 fw-bolder text-primary">
                                                        {{ strtoupper($item->ticket->name) }}
                                                    </small>
                                                    <div class="toast fade show bg-secondary text-white border-0 mb-1">
                                                        <div class="toast-body">
                                                            {{ $item->message }}
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
                        </div>
                        <div class="card-footer">
                            <form action="" class="comment-text d-flex align-items-center mt-3" id="chat-inputs">
                                <input type="text" id="message-input" class="form-control rounded-pill"
                                    placeholder="Compose message!">
                                {!! csrf_field() !!}
                                <input type="hidden" class="ticket" value="{{ $_ticket->id }}">
                                <input type="hidden" class="staff" value="{{ Auth::user()->staff->id }}">
                                <div class="comment-attagement d-flex">
                                    <a class="me-4 text-body">
                                        <svg width="20" height="20" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5M17,9.5C17,10.3 16.3,11 15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8C16.3,8 17,8.7 17,9.5M12,17.23C10.25,17.23 8.71,16.5 7.81,15.42L9.23,14C9.68,14.72 10.75,15.23 12,15.23C13.25,15.23 14.32,14.72 14.77,14L16.19,15.42C15.29,16.5 13.75,17.23 12,17.23Z" />
                                        </svg>
                                    </a>
                                    <a href="#" class="me-2" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Resolved Concern!">
                                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M8.43994 12.0002L10.8139 14.3732L15.5599 9.6272" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </div>
                            </form>
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
                                    class="d-flex border-bottom p-2  {{ request()->input('_ticket')? (request()->input('_ticket') == base64_encode($data->id)? 'bg-primary rounded': ''): '' }} ">
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
                                                class="{{ request()->input('_ticket')? (request()->input('_ticket') == base64_encode($data->id)? 'text-white': 'text-secondary'): 'text-secondary' }} ">

                                                <label class="m-0 ">{{ $data->ticket->name }}</label> <br>
                                                <small class="mb-0">{{-- {{ $data->ticket->created_at->diffForHumans() }} --}}
                                                    {{ $data->ticket->ticket_number }}</small>
                                            </div>

                                        </a>
                                    </div>
                                    {{-- <div class="notification ms-5 float-end">
                                        @if ($data->is_ongoing == 0)
                                            <span class="badge text-end bg-primary  float-end">NEW</span>
                                        @endif

                                    </div> --}}
                                </li>
                            @endforeach
                        @else
                            <li class="d-flex border-bottom">
                                <div>
                                    <h5 class="mb-2">No Concern</h5>
                                </div>
                            </li>
                        @endif
                    </ul>
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
