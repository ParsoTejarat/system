@extends('panel.layouts.master')
@section('title', 'مشاهده تیکت')

@section('styles')
    <link rel="stylesheet" href="/vendors/lightbox/magnific-popup.css" type="text/css">
    <style>
        ul li {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .chat-body {
            position: relative;
            overflow: hidden;
            height: 85vh;
        }

        /* پس‌زمینه چت */
        .chat-body-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url({{ asset('/assets/images/background.jpg') }});
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            filter: blur(5px);
            z-index: 0;
        }

        /* پیام‌ها */
        .chat-body-messages {
            position: relative;
            z-index: 1;
            padding: 15px;
            overflow-y: auto;
            height: 70vh;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(3px);
            scroll-behavior: smooth;
        }

        .message-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .message-item {
            background: linear-gradient(135deg, rgba(34, 112, 127, 0.8), rgba(60, 180, 150, 0.8));
            padding: 10px;
            border-radius: 5px;
            max-width: 70%;
        }

        .outgoing-message {
            background: linear-gradient(135deg, rgba(34, 112, 127, 0.4), rgba(60, 180, 150, 0.4));
            align-self: flex-end;
        }

        .message-time {
            font-size: 0.8rem;
            color: #ffd600;
        }

        .fa-check {
            color: #00ff2d;
        }

        .fa-check-double {
            color: #34b7f1;
        }
    </style>
@endsection

@section('content')
    <div class="card mt-3 chat-app-wrapper">
        <div class="row d-flex chat-app">
            <div class="col-xl-12 mt-1 col-md-12 chat-body">
                <div class="chat-body-wrapper"></div>
                <div class="chat-body-messages">
                    <div class="message-items">
                        @foreach($ticket->messages as $message)
                            <div id="message-{{ $message->id }}"
                                 class="message-item {{ $message->user->company_user_id == auth()->id() ? 'outgoing-message' : '' }}">
                                <div class="message-content">
                                    @if($message->text)
                                        <div class="message-text">{{ $message->text }}</div>
                                    @endif
                                    <div class="message-meta d-flex justify-content-between">
                                        <span class="message-time">{{ verta($message->created_at)->format('H:i - Y/m/d') }}</span>
                                        @if($message->read_at)
                                            <i class="status-read fa fa-check-double"></i>
                                        @else
                                            <i class="status-sent fa fa-check"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <form id="chatForm" action="{{ url(env('API_BASE_URL') . 'tickets/'. $ticket->id) }}" method="post"
                      enctype="multipart/form-data" class="d-flex align-items-center px-3">
                    @csrf
                    <input type="text" name="text" class="form-control" placeholder="پیام ..." required autocomplete="off">
                    <button type="submit" class="mx-2 btn btn-primary">
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let chatBox = document.querySelector('.chat-body-messages');
        let userScrolledUp = false;

        $(document).ready(function () {
            chatBox.scrollTop = chatBox.scrollHeight; // اسکرول به پایین در ابتدا

            chatBox.addEventListener('scroll', function () {
                userScrolledUp = chatBox.scrollTop + chatBox.clientHeight < chatBox.scrollHeight;
            });

            $('#chatForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let url = $(this).attr('action');

                let tempMessageId = 'temp-' + Date.now();
                let tempMessage = `<div class="message-item outgoing-message" id="${tempMessageId}">
                                        <div class="message-content">
                                            <div class="message-text">${$('input[name="text"]').val()}</div>
                                            <div class="message-meta">
                                                <span class="message-time">در حال ارسال...</span>
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                        </div>
                                    </div>`;
                $('.message-items').append(tempMessage);
                if (!userScrolledUp) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.message_html) {
                            $(`#${tempMessageId}`).replaceWith(response.message_html);
                            if (!userScrolledUp) {
                                chatBox.scrollTop = chatBox.scrollHeight;
                            }
                        }
                        $('#chatForm')[0].reset();
                    },
                    error: function () {
                        $(`#${tempMessageId} .message-meta`).html('<span class="text-danger">ارسال ناموفق</span>');
                    }
                });
            });

            function fetchNewMessages() {
                let lastMessageId = $('.message-item:last').attr('id')?.replace('message-', '') || 0;
                $.ajax({
                    url: "{{env('API_BASE_URL') . 'tickets/' . $ticket->id . '/new-messages'}}",
                    type: "GET",
                    data: { last_id: lastMessageId },
                    success: function (response) {
                        if (response.new_messages) {
                            $('.message-items').append(response.new_messages);
                            if (!userScrolledUp) {
                                chatBox.scrollTop = chatBox.scrollHeight;
                            }
                        }
                    }
                });
            }

            setInterval(fetchNewMessages, 5000);
        });
    </script>
@endsection
