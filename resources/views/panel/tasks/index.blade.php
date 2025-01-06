@extends('panel.layouts.master')
@section('title', 'وظایف')
@section('styles')
    <style>
        .drop-hover {
            background-color: #e9ecef;
            border: 2px dashed #6c757d;
            transition: background-color 0.3s ease;
        }

        .task {
            cursor: grab;
            user-select: none;
            display: inline-block;
            padding: 5px 10px;
            transition: transform 0.2s ease;
            border-radius: 5px;
            border: 1px solid #d0d0d0;
            margin: 5px;

        }

        .task:active {
            cursor: grabbing;
            transform: scale(0.95);
        }

        .task:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: scale(1.03);
        }


    </style>
    <link rel="stylesheet" href="{{asset('/assets/css/jquery-ui.css')}}">
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">وظایف</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>وظایف انجام نشده</h4>
                        </div>
                        <div class="card-body" id="uncomplete-section">
                            <span class="text-info loading" style="display: none">در حال بارگذاری ...</span>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>وظایف انجام شده</h4>
                        </div>
                        <div class="card-body" id="complete-section">
                            <span class="text-info loading" style="display: none">در حال بارگذاری ...</span>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('/assets/js/jquery-ui.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('.loading').show();

            $.ajax({
                url: "{{env('API_BASE_URL').'get-user-task/'.auth()->id().'/'.env('COMPANY_NAME')}}",
                method: 'GET',
                headers: {
                    'API_KEY': '{{ env('API_KEY_TOKEN_FOR_TICKET') }}'
                },
                success: function (response) {
                    $('.loading').hide();

                    $('#uncomplete-section').empty();
                    $('#complete-section').empty();

                    response.forEach(function (task) {
                        var taskElement = $('<div class="task" data-task-id="' + task.id + '">' + task.task + '</div>');

                        if (task.completed_at) {
                            $('#complete-section').append(taskElement);
                        } else {
                            $('#uncomplete-section').append(taskElement);
                        }
                    });

                    $('.task').draggable({
                        revert: "invalid",
                        zIndex: 1000,
                        helper: "original",
                        start: function () {
                            $(this).css('opacity', '0.5');
                        },
                        stop: function () {
                            $(this).css('opacity', '1');
                        }
                    });

                    $('#complete-section').droppable({
                        accept: "#uncomplete-section .task",
                        hoverClass: "drop-hover",
                        drop: function (event, ui) {
                            var taskId = ui.draggable.attr('data-task-id');
                            $.ajax({
                                url: '{{env('API_BASE_URL').'task/update-status'}}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    status: 'completed',
                                    task_id: taskId
                                },
                                success: function (response) {
                                    ui.draggable.detach().appendTo('#complete-section').css({
                                        top: 'auto',
                                        left: 'auto',
                                        position: 'relative'
                                    });
                                },
                                error: function () {
                                    alert('خطا در به‌روزرسانی وضعیت تسک!');
                                }
                            });
                        }
                    });

                    $('#uncomplete-section').droppable({
                        accept: "#complete-section .task",
                        hoverClass: "drop-hover",
                        drop: function (event, ui) {
                            var taskId = ui.draggable.attr('data-task-id');
                            $.ajax({
                                url: '{{env('API_BASE_URL').'task/update-status'}}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    status: 'uncompleted',
                                    task_id: taskId
                                },
                                success: function (response) {
                                    ui.draggable.detach().appendTo('#uncomplete-section').css({
                                        top: 'auto',
                                        left: 'auto',
                                        position: 'relative'
                                    });
                                },
                                error: function () {
                                    alert('خطا در به‌روزرسانی وضعیت تسک!');
                                }
                            });
                        }
                    });
                },
                error: function () {
                    $('.loading').hide();
                    alert('خطا در دریافت داده‌ها');
                }
            });
        });
    </script>

@endsection



