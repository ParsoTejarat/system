@extends('panel.layouts.master')
@section('title', 'مشاهده تیکت')
@section('styles')
    <!-- lightbox -->
    <link rel="stylesheet" href="/vendors/lightbox/magnific-popup.css" type="text/css">
    <style>
        /* حذف گلوله در لیست‌ها */
        ul li {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        body {
            overflow: hidden !important;
            margin-top: 20px;
        }

        /* پس زمینه چت با مات شدن */
        .chat-body-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url({{ asset('/assets/images/background.jpg') }});
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            filter: blur(5px); /* میزان تاری */
            z-index: -1; /* پایین‌تر از محتوا */
        }

        .chat-body-messages {
            position: relative;
            padding: 10px;
            background: transparent; /* حذف پس‌زمینه تکراری */
            overflow-y: auto;
            height: 70vh;
        }

        .message-items {
            position: relative;
            z-index: 1;
        }

        /* دکمه‌های بدون پس‌زمینه */
        .btn.btn-outline-light {
            background-color: transparent !important;
            border: none;
            color: #fff;
        }

        /* فونت پیام‌ها */
        .message-text {
            font-size: 14px !important;
            line-height: 1.6;
            color: #fff;
        }

        /* رنگ آیکن‌ها */
        .fa-check {
            color: #00ff2d !important; /* تیک تکی */
        }

        .fa-check-double {
            color: #34b7f1 !important; /* تیک دوتایی */
        }

        /* پیام دریافتی */
        .message-item {
            background: linear-gradient(
                135deg,
                rgba(34, 112, 127, 0.8),
                rgba(60, 180, 150, 0.8)
            ) !important;
            backdrop-filter: blur(6.9px);
            border-radius: 5px !important;
            padding: 10px;
            display: inline-block; /* محدود کردن به اندازه محتوا */
            max-width: 70%; /* حداکثر عرض پیام */
            margin-bottom: 10px;
        }

        .message-time {
            font-size: 0.65rem !important;
            color: #ffd600 !important;
            margin-left: 30px;
        }

        /* پیام ارسالی */
        .outgoing-message {
            background: linear-gradient(
                135deg,
                rgba(34, 112, 127, 0.4),
                rgba(60, 180, 150, 0.4)
            ) !important;
            backdrop-filter: blur(6.9px);
        }

        /* استایل تصویر */
        img {
            max-width: 200px !important;
        }

        .message-content {
            padding: 0px 8px;
        }

        .chat-app {
            height: 85vh;
        }

        .chat-body-messages {
            height: 70vh !important;
            overflow-y: auto !important;
        }

        /* نمایش پیام‌ها به صورت ستونی */
        .message-items {
            min-height: min-content;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* تراز کردن پیام‌ها به سمت چپ یا راست */
        .message-item:not(.outgoing-message) {
            align-self: flex-start;
        }

        .outgoing-message {
            align-self: flex-end;
        }

        #chatForm input {
            border: none;
            margin: 5px;
        }

        /* استایل آیکون لودینگ */
        .fa-spinner {
            display: inline-block;
            color: white;
        }

        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }

        .company_name {
            font-size: .8rem;
            margin-top: 0.75rem !important;
        }

        @keyframes fa-spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

    </style>

@endsection

@section('content')
    <div class="card mt-3 chat-app-wrapper">
        <div class="row d-flex chat-app">
            <div class="col-xl-12 mt-1 col-md-12 chat-body">
                <div class="card-body py-2 px-3 border-bottom border-light">
                    <div class="d-flex py-1">
                        <img src="/assets/images/users/avatar.png" class="me-2 rounded-circle" height="36"
                             alt="Brandon Smith">
                        <div class="flex-1">
                            <h5 class="mt-0 mb-0 font-15">
                                <a href="javascript:void(0)" class="text-reset">
                                    @if(auth()->id() == $ticket->sender->company_user_id)
                                        {{ $ticket->receiver->name.' '.$ticket->receiver->family }} - {{$ticket->receiver->role_name}}
                                        <div class="company_name">{{ getCompany($ticket->receiver->company_name) }}</div>
                                    @else
                                        {{ $ticket->sender->name.' '.$ticket->sender->family }} - {{$ticket->receiver->role_name}}
                                        <div class="company_name">{{ getCompany($ticket->sender->company_name) }}</div>
                                    @endif
                                </a>
                            </h5>
                        </div>
                        <div id="tooltip-container">
                            <div>
                                @if($ticket->status == 'closed')
                                    <span class="badge bg-success me-2">بسته شده</span>
                                @else
                                    <span class="badge bg-warning me-2">درحال بررسی</span>
                                @endif
                                <button type="button" data-bs-toggle="dropdown"
                                        class="btn btn-sm btn-primary btn-floating" aria-expanded="true">
                                    <i class="fa fa-cog"></i>
                                </button>
                                <div class="dropdown">
                                    <ul class="dropdown-menu">
                                        <li>
                                            @if($ticket->status == 'closed')
                                                <a class="dropdown-item"
                                                   href="{{ route('ticket.changeStatus', $ticket->id) }}">درحال
                                                    بررسی</a>
                                            @else
                                                <a class="dropdown-item"
                                                   href="{{ route('ticket.changeStatus', $ticket->id) }}">بسته
                                                    شده</a>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chat-body-wrapper"></div>
                <div class="chat-body-messages">
                    <div class="message-items">
                        @foreach($ticket->messages as $message)
                            @if($message->user->company_user_id == auth()->id())
                                <div id="message-{{ $message->id }}"
                                     class="message-item {{ $message->file ? 'message-item-media' : '' }}">
                                    <div class="message-content">
                                        @if($message->text)
                                            <div class="message-text">{{ $message->text }}</div>
                                        @endif
                                        @includeWhen($message->file, 'panel.partials.file-message')
                                        <div
                                            class="message-meta row @if($message->file) justify-content-between m-2 @else justify-content-between @endif px-1">
                                            <span class="message-time">
                                                {{ verta($message->created_at)->format('H:i - Y/m/d') }}
                                            </span>
                                            @if($message->read_at)
                                                <i class="status-read fa fa-check-double"></i>
                                            @else
                                                <i class="status-sent fa fa-check"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div id="message-{{ $message->id }}"
                                     class="message-item outgoing-message {{ $message->file ? 'message-item-media' : '' }}">
                                    @if($message->text)
                                        <div
                                            class="message-text @if($message->file) p-2 @endif">{{ $message->text }}</div>
                                    @endif
                                    @includeWhen($message->file, 'panel.partials.file-message')
                                    <div
                                        class="message-meta row @if($message->file) justify-content-center m-2 @else justify-content-between @endif px-1">
                                        <span class="message-time">
                                            {{ verta($message->created_at)->format('H:i - Y/m/d') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="chat-body-footer">
                    <!-- فرم ارسال پیام با AJAX -->
                    <form id="chatForm" action="{{ url(env('API_BASE_URL') . 'tickets/'. $ticket->id) }}" method="post"
                          enctype="multipart/form-data" class="d-flex align-items-center px-3">
                        @csrf
                        @method('PUT')
                        <input type="text" name="text" class="form-control" placeholder="پیام ..." required
                               autocomplete="off">
                        <input type="hidden" value="{{$ticket->id}}" name="ticket_id">
                        <input type="hidden" value="{{auth()->id()}}" name="sender_id">
                        <input type="hidden" value="{{env('COMPANY_NAME')}}" name="company">
                        <div class="d-flex ml-3">
                            <button type="submit" class="mx-2 btn btn-primary btn-floating">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                            <div class="dropup">
                                <button type="button" data-bs-toggle="dropdown"
                                        class=" btn btn-success btn-floating">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="dropdown-menu-body">
                                        <ul>
                                            <li>
                                                <label class="dropdown-item" for="file">
                                                    <i class="icon fa fa-file"></i>
                                                    <span id="file_lbl">فایل</span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <input type="file" name="file" class="d-none" id="file">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <!-- begin::lightbox -->
    <script src="/vendors/lightbox/jquery.magnific-popup.min.js"></script>
    <script src="/assets/js/examples/lightbox.js"></script>

    <script>
        $(document).ready(function () {
            $('.chat-body-messages').animate({scrollTop: $('.chat-body-messages')[0].scrollHeight}, 500);
            $('#file').on('change', function () {
                $('#file_lbl').text(this.files[0].name);
                $('input[name="text"]').removeAttr('required');
            });
            $('#chatForm').on('submit', function (e) {
                e.preventDefault();


                var formData = new FormData(this);
                var url = $(this).attr('action');

                // افزودن پیام موقت با آیکون در حال ارسال
                var tempMessageId = 'temp-' + Date.now();
                var tempMessage = `<div class="message-item" id="${tempMessageId}">
                            <div class="message-content">
                                <div class="message-text">${$('input[name="text"]').val()}</div>
                               <div class="message-meta d-flex align-items-center justify-content-between">
                                    <span class="message-time">در حال ارسال...</span>
                                    <i class="fa fa-spinner fa-spin"></i>
                                    </div>

                                    </div>
                                    </div>`;
                $('.message-items').append(tempMessage);
                $('.chat-body-messages').animate({scrollTop: $('.chat-body-messages')[0].scrollHeight}, 500);

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.message_html) {
                            // جایگزینی پیام موقت با پیام اصلی
                            $(`#${tempMessageId}`).replaceWith(response.message_html);
                            setTimeout(() => {
                                const container = $('.chat-body-messages')[0];
                                // اسکرول به پایین با محاسبه دقیق
                                container.scrollTop = container.scrollHeight;
                            }, 50);
                        }
                        $('#chatForm')[0].reset();
                        $('#file_lbl').text('فایل');
                    },
                    error: function () {
                        $(`#${tempMessageId} .message-meta`).html('<span class="text-danger">ارسال ناموفق</span>');
                    }
                });
            });

        });

        function updateReadStatus() {
            $.ajax({
                url: "{{ env('api_base_url') . 'tickets/getReadMessages' }}",
                type: "POST",
                data: {
                    ticket_id: {{$ticket->id}},
                    company_user_id: {{auth()->id()}},
                    company:@json(env('COMPANY_NAME'))
                },
                dataType: "json",
                success: function (response) {
                    if (response.read_messages && response.read_messages.length > 0) {
                        response.read_messages.forEach(function (id) {

                            var messageDiv = $('#message-' + id);
                            var icon = messageDiv.find('.status-sent');


                            if (icon.length) {
                                icon.removeClass('fa-check').addClass('fa-check-double status-read');
                            }
                        });
                    }
                },
                error: function () {
                    console.log('خطا در بروزرسانی وضعیت خوانده شدن پیام‌ها');
                }
            });
        }

        function fetchNewMessages() {
            // گرفتن آخرین پیام نمایش داده شده
            var lastMessage = $('.message-item').last();
            var lastId = lastMessage.attr('id') ? lastMessage.attr('id').replace('message-', '') : 0;
            var ticket_id = {{$ticket->id}};
            $.ajax({
                url: "{{env('API_BASE_URL') . 'tickets/' . $ticket->id . '/new-messages'}}",
                type: "GET",
                data: {
                    ticket_id: ticket_id,
                    last_id: lastId,
                    auth_id: {{auth()->id()}},
                    company: @json(env('COMPANY_NAME')),
                },
                dataType: "json",
                success: function (response) {
                    if (response.new_messages) {
                        console.log(response)
                        var newMessages = $(response.new_messages);
                        newMessages.each(function () {
                            var messageId = $(this).attr('id');
                            if (!$('#' + messageId).length) { // اگر پیام با این id وجود نداشته باشد
                                $('.message-items').append($(this));
                            }
                        });
                        updateReadStatus();
                        $('.chat-body-messages').animate({scrollTop: $('.chat-body-messages')[0].scrollHeight}, 500);
                    }
                },
                error: function () {
                    console.log("خطا در دریافت پیام‌های جدید");
                }
            });
        }

        // هر ۵ ثانیه یک بار اجرا شود
        setInterval(fetchNewMessages, 5000);
    </script>
@endsection
