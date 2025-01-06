@extends('layouts.main')
@section('content')
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PAQ</a></li>
                            <li class="breadcrumb-item active">All Question list</li>

                        </ol>
                    </div>
                    <h4 class="page-title"> Questions List</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        @if(!Auth::user()->hasRole('Sectary'))
        <form class="filter-form" action="{{url()->current()}}" method="POST">
            @csrf
        <!-- start Filter -->
        <div class="row justify-content-end ">
            <div class="col-3">
                <div class="mb-3">
                    <label class="mb-1">From</label>
                    <input class="form-control" type="date" name="date_from" placeholder="">
                    </input>
                </div>
            </div>
            <div class="col-3">
                <div class="mb-3">

                    <label class="mb-1">To</label>
                    <input class="form-control" type="date" name="date_to" placeholder="To">
                    </input>
                </div>
            </div>
            <!----------------------->
            <div class="col-3"><!-- filter-bar-->
                <div class="mb-3">
                    <label class="mb-1">Question Raised By </label>
                    <input type="text" class="form-control" name="raised_by" placeholder="Enter MPA Name">
                </div>
            </div>
            <div class="col-2"><!-- filter-bar--></div>
            <div class="col-1">
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary w-100 text-center">Submit</button>
                </div>
            </div>
        </div>
        </form>
        @endif
        <!-- end Filter -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 align-self-center table-wrapper">
                                <!-- <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                                    <strong><i class="mdi mdi-robot-angry"></i> - </strong> no record found !
                                </div> -->
                                <!-- Table Start-->
                                <table
                                    class="table table-centered  table-centered table-striped dt-responsive nowrap w-100 mb-0">
                                    <thead class="table-dark">
                                    <tr>
                                        <th>Sr #</th>
                                        <th>
                                            <div id="tooltip-container2">
                                                <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip"
                                                       data-bs-placement="top" title="Question Number">Q No.</label>
                                            </div>
                                        </th>
                                        <th>Diary No.</th>
                                        <th>
                                            <div id="tooltip-container2">
                                                <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip"
                                                       data-bs-placement="top" title="Received By">Rec. by</label>
                                            </div>
                                        </th>
                                        <th>
                                            <div id="tooltip-container2">
                                                <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip"
                                                       data-bs-placement="top" title="Assembly Question Raised">Assb. Q
                                                    Raised</label>
                                            </div>
                                        </th>
                                        <th>Subject</th>
                                        <th>
                                            <div id="tooltip-container2">
                                                <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip"
                                                       data-bs-placement="top" title="Assembly Session">Assb.
                                                    Session</label>
                                            </div>
                                        </th>
                                        <th>Letter Issuance</th>
                                        <th>
                                            <div id="tooltip-container2">
                                                <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip"
                                                       data-bs-placement="top" title="Received Date">Rec. Date</label>
                                            </div>
                                        </th>
                                        <th>Assigned From</th>
                                        <th>Assigned To</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($assemblyQuestions as $index=>$question)
                                        <tr>
                                            @if($assemblyQuestions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                            <td>{{ ($assemblyQuestions->currentPage() - 1) * $assemblyQuestions->perPage() + $index + 1 }}</td>
                                            @else
                                                <td>{{$index+1}}</td>
                                            @endif
                                            <td>{{$question['assembly_question_no']}}</td>
                                            <td>{{$question['dairy_no']}}</td>
                                            <td>{{$question['received_by']}}</td>
                                            <td>{{$question['raised_by']}} </td>
                                            <td>{{$question['subject']}}</td>
                                            <td>{{ date('d-m-Y', strtotime($question['assembly_session_date'])) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($question['letter_issuance_date'])) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($question['receiving_date'])) }}</td>
                                            <td>
                                                @if($question['assigned_to'])
                                                    {{$question['track'][0]['assignedBy']['designation']}}
                                                @else
                                                    Archived
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($question['track'][0]))
                                                    {{$question['track'][0]['assignedTo']['designation']}}
                                                @else
                                                    Archived
                                                @endif
                                            </td>
                                            <td><span class="badge  @if ($question['status'] == 'Forwarded')
                                            bg-dark
                                            @elseif ($question['status'] == 'Archived')
                                            bg-info
                                            @elseif ($question['status'] == 'Rejected')
                                            bg-danger
                                            @elseif ($question['status'] == 'Replied')
                                            bg-primary
                                            @elseif ($question['status'] == 'Accepted')
                                            bg-warning
                                            @else
                                            bg-success @endif">{{$question['status']}}</span></td>

                                            @if(Auth::user()->hasRole('DEO'))
                                                <td>
                                                    <a href="{{url('edit-assembly-question/'.encrypt($question['id']))}}"
                                                       class="btn btn-info">Edit</a>
                                                </td>
                                            @elseif(Auth::user()->hasRole('Sectary'))
                                                    <td>
                                                        <p class="muted mb-0" id="tooltip-container">
                                                            <a href="{{url('forward-assembly-question/'.encrypt($question['id']))}}" class="action-icon">
                                                                <i class="mdi mdi-file-send" data-bs-container="#tooltip-container" data-bs-toggle="tooltip" title="Action"></i>
                                                            </a>
                                                        </p>
                                                    </td>
                                            @else
                                                <td>
                                                    <a href="{{url('accept-assembly-question/'.encrypt($question['id']))}}"
                                                       class="btn btn-success">Accept</a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <!-- Pagination links -->
                                @if($assemblyQuestions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="mt-3">
                                    {{ $assemblyQuestions->links() }}
                                </div>
                                @endif
                                <!-- Table End-->

                            </div> <!-- end col-->

                        </div>
                        <!-- end row -->

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row-->
    </div><!-- container -->

    <script>
        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
    </script>
@endsection
