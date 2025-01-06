@extends('layouts.main')
@section('content')
    <script>
        var fln = @json($data);
    </script>
    <script src="{{ asset('assets/js/highcharts-PAQ.js') }}"></script>
    <style>
        .card-box {
            cursor: pointer;
        }
        .widget-icon {
            transition: color 0.3s ease, transform 0.3s ease;
        }
    </style>
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PAQ</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>

                        </ol>
                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <!-- start Filter -->
        <form class="filter-form" action="{{url()->current()}}" method="POST">
            @csrf
        <div class="row justify-content-end ">
            <div class="col-3 filter-bar">
                <div class="mb-3">
                    <label class="mb-1">From</label>
                    <input class="form-control" type="date" max="{{ now()->format('Y-m-d') }}" name="date_from" placeholder="" value="{{ isset($request['date_from']) && !is_null($request['date_from']) ? \Carbon\Carbon::parse($request['date_from'])->format('Y-m-d') : '' }}">
                </div>
            </div>
            <div class="col-3 filter-bar">
                <div class="mb-3">
                    <label class="mb-1">To</label>
                    <input class="form-control" type="date" max="{{ now()->format('Y-m-d') }}" name="date_to" placeholder="To" value="{{ isset($request['date_to']) && !is_null($request['date_from']) ? \Carbon\Carbon::parse($request['date_to'])->format('Y-m-d') : '' }}">
                </div>
            </div>
{{--            <div class="col-3  filter-bar">--}}
{{--                <div class="mb-3">--}}
{{--                    <label class="mb-1">Status</label>--}}
{{--                    <select class="form-select" id="filter-select">--}}
{{--                        <option>Select Status</option>--}}
{{--                        <option>Forwarded by SO-AB</option>--}}
{{--                        <option>Response Awaited</option>--}}
{{--                        <option>Delayed</option>--}}
{{--                        <option>Answered</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="filter-bar col-md-3" id="submitToContainer">
                <label for="submitTo" class="form-label">Select Department</label>
                <select id="submitTo" name="department_id" class="form-select select2">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->department_name }}</option>
                        @endforeach
                </select>
            </div>
            <div class="filter-bar col-md-3" id="potContainer">
                <label for="pot" class="form-label">Assigned To</label>
                <select id="pot" name="assign_to" class="form-select select2">
                    <option value="">Select Point of Contact</option>
                    @if($deptUsers)
                        @foreach($deptUsers as $user)
                            <option value="{{$user->id}}" {{ request('assign_to') == $user->id ? 'selected' : '' }}>{{$user->full_name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-3 filter-bar">
                <div class="mb-3 mt-3">
                    <button type="submit" class="btn btn-primary w-100 text-center">Submit</button>
                </div>
            </div>
        </div>
        </form>
        <!-- end Filter -->

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <!-- Hidden Form -->
                <form id="assemblyQuestionList" action="{{ url('assembly-question-list') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="list_type" id="filterInput">
                    <input type="hidden" name="date_from" value="{{$request['date_from']}}">
                    <input type="hidden" name="date_to" value="{{$request['date_to']}}">
                    <input type="hidden" name="assign_to" value="{{$request['assign_to']}}">
                </form>
                <div class="row"><!-- justify-content-center -->
                    <div class="col-lg-2 card-box total-questions" data-filter="total_questions">
                        <div class="card widget-flat">
                            <div class="card-body  py-3">
                                <div class="float-end">
                                    <i class="mdi mdi-beaker-question-outline  widget-icon-blue total-box "></i>
                                </div>
                                <h5 class="text-muted fw-bolder  mt-0" title="Number of Customers">Total Questions</h5>
                                <h3 class="mt-3 prima">{{$data['total_questions']}}</h3>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-lg-2 card-box forwarded-questions" data-filter="forwarded_questions">
                    <div class="card widget-flat">
                            <div class="card-body  py-3">
                                <div class="float-end">
                                    <i class="mdi mdi-archive widget-icon-yellow forwarded-box"></i>
                                </div>
                                <h5 class="text-muted fw-bolder  mt-0" title="Growth">Forwarded</h5>
                                <h3 class="mt-3 prima">{{$data['forwarded_questions']}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 card-box forwarded-questions" data-filter="assigned_questions">
                    <div class="card widget-flat">
                            <div class="card-body  py-3">
                                <div class="float-end">
                                    <i class="mdi mdi-archive widget-icon-yellow"></i>
                                </div>
                                <h5 class="text-muted fw-bolder  mt-0" title="Growth">Assigned</h5>
                                <h3 class="mt-3 prima">{{$data['assigned_questions']}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 card-box in-process" data-filter="response_awaited">
                        <div class="card widget-flat">
                            <div class="card-body  py-3">
                                <div class="float-end">
                                    <i class="mdi dripicons-checklist widget-icon-blue-green response-box"></i>
                                </div>
                                <h5 class="text-muted fw-bolder  mt-0" title="Number of Customers">In Process </h5>
                                <h3 class="mt-3 prima">{{$data['response_awaited']}}</h3>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                    <div class="col-lg-2 card-box delayed" data-filter="delayed">
                        <div class="card widget-flat">
                            <div class="card-body  py-3">
                                <div class="float-end">
                                    <i class="mdi mdi-calendar-clock widget-icon-yellow delayed-box"></i>
                                </div>
                                <h5 class="text-muted fw-bolder  mt-0" title="Growth">Delayed </h5>
                                <h3 class="mt-3 prima">{{$data['delayed']}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 card-box answered" data-filter="answered_questions">
                        <div class="card widget-flat">
                            <div class="card-body  py-3">
                                <div class="float-end">
                                    <i class="mdi mdi-sticker-check-outline widget-icon-moge answered-box"></i>
                                </div>
                                <h5 class="text-muted fw-bolder  mt-0" title="Growth">Answered </h5>
                                <h3 class="mt-3 prima">{{$data['answered_questions']}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
            <div class="col-lg-6">
                <div class="card card-h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Overall Status</h4>
                        <div dir="ltr">
                            <div id="month-wise-files"></div>
                        </div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->

            </div> <!-- end col -->

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Wings Wise Status</h4>
                        <div id="number-of-wing"></div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
        <!--------Model View start here-------->
        <div class="modal fade" id="puc-view-modal-lg" tabindex="-1" aria-labelledby="pucviewModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="PUCMarkModalLabel">Assembly Question Time Line</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <!--------------------------><!------------Quize Time Line Start Here-------------->
                        <div data-simplebar="" style="max-height: 419px;">
                            <div class="timeline-alt pb-0">

                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-gold-lighten text-primary timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-primary fw-bold mb-1 d-block">Assembly</a>
                                        <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                                        </small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted"><b class="text-info-green">Date and Time</b> 11-2-2024 and   11:24-PM</small>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-green-lighten text-info timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-info-green fw-bold mb-1 d-block">Minister</a>
                                        <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted"><b class="text-info-green">Date and Time</b> 11-2-2024 and   11:24-PM</small>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-gold-lighten text-primary timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-primary fw-bold mb-1 d-block">ASD</a>
                                        <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted"><b class="text-info-green">Date and Time</b> 11-2-2024 and   11:24-PM</small>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-green-lighten text-info timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-info-green fw-bold mb-1 d-block">SO-AB</a>
                                        <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted"><b class="text-info-green">Date and Time</b> 11-2-2024 and   11:24-PM</small>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-gold-lighten text-primary timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-primary fw-bold mb-1 d-block">Department</a>
                                        <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted"><b class="text-info-green">Date and Time</b> 11-2-2024 and   11:24-PM</small>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-green-lighten text-info timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-info-green fw-bold mb-1 d-block">AS-DEA</a>
                                        <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted"><b class="text-info-green">Date and Time</b> 11-2-2024 and   11:24-PM</small>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-gold-lighten text-primary timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-primary fw-bold mb-1 d-block">SO-AB</a>
                                        <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted"><b class="text-info-green">Date and Time</b> 11-2-2024 and   11:24-PM</small>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-green-lighten text-info timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-info-green fw-bold mb-1 d-block">D-Diary</a>
                                        <small>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted"><b class="text-info-green">Date and Time</b> 11-2-2024 and   11:24-PM</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- end timeline -->
                        </div> <!-- end slimscroll -->
                        <!------------Quize Time Line End Here-------------->
                        <!-------------------------->
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!--------Model View End here-------->
    </div> <!-- container -->
    <script>
        $(document).ready(function() {
            $('.card-box').click(function() {
                var filterValue = $(this).data('filter');
                if (filterValue !== undefined) {
                    $('#filterInput').val(filterValue);
                    $('#assemblyQuestionList').submit();
                } else {
                    console.error('Filter value is undefined');
                }
            });
        });
    </script>
@endsection
