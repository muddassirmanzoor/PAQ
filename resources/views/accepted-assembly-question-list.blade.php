@extends('layouts.main')
@section('content')
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
    <div class="container-fluid">
<?php $route = request()->segment(count(request()->segments()));
if( $route == 'accepted-assembly-question-list'){
    $heading = 'Accepted Questions';
}else{
    $heading = 'Forwarded Questions';
}
?>
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PAQ</a></li>
                            <li class="breadcrumb-item active">{{$heading}} list</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{$heading}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="col-xl-12 align-self-center">
                                    <!-- <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                                        <strong><i class="mdi mdi-robot-angry"></i> - </strong> no record found !
                                    </div> -->
                                    <!-- Table Start-->
                                    <table class="table  table-centered table-striped dt-responsive nowrap w-100 mb-0">
                                        <thead class="table-dark">
                                        <tr>
                                            <th>Sr #</th>
                                            <th>
                                                <div id="tooltip-container2">
                                                    <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip" data-bs-placement="top" title="Question Number">Q No.</label>
                                                </div>
                                            </th>
                                            <th>Diary No.</th>
                                            <th>
                                                <div id="tooltip-container2">
                                                    <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip" data-bs-placement="top" title="Received By">Rec. by</label>
                                                </div>
                                            </th>
                                            <th>
                                                <div id="tooltip-container2">
                                                    <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip" data-bs-placement="top" title="Assembly Question Raised">Assb. Q Raised</label>
                                                </div>
                                            </th>
                                            <th>Subject</th>
                                            <th>
                                                <div id="tooltip-container2">
                                                    <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip" data-bs-placement="top" title="Assembly Session">Assb. Session</label>
                                                </div>
                                            </th>
                                            <th>Letter Issuance</th>
                                            <th>
                                                <div id="tooltip-container2">
                                                    <label data-bs-container="#tooltip-container2" data-bs-toggle="tooltip" data-bs-placement="top" title="Received Date">Rec. Date</label>
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
                                                <td>{{ ($assemblyQuestions->currentPage() - 1) * $assemblyQuestions->perPage() + $index + 1 }}</td>
                                                <td>{{$question['assembly_question_no']}}</td>
                                                <td>{{$question['dairy_no']}}</td>
                                                <td>{{$question['received_by']}}</td>
                                                <td>{{$question['raised_by']}} </td>
                                                <td>{{$question['subject']}}</td>
                                                <td>{{ date('d-m-Y', strtotime($question['assembly_session_date'])) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($question['letter_issuance_date'])) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($question['receiving_date'])) }}</td>
                                                <td>
                                                    {{$question['track'][0]['assignedBy']['designation']}}
                                                </td><td>
                                                    {{$question['track'][0]['assignedTo']['designation']}}
                                                </td>
                                                @php $status = $question['track'][0]['status_by'];@endphp
                                                <td><span class="badge  @if ($status == 'Forwarded')
                                            bg-dark
                                            @elseif ($status == 'Archived')
                                            bg-info
                                            @elseif ($status == 'Rejected')
                                            bg-danger
                                            @elseif ($status == 'Replied')
                                            bg-primary
                                            @elseif ($status == 'Accepted')
                                            bg-warning
                                            @else
                                            bg-success @endif">{{$status}}</span></td>
                                                <td>
                                                    <p class="muted mb-0" id="tooltip-container">
                                                        <a href="{{url('forward-assembly-question/'.encrypt($question['id']))}}" class="action-icon">
                                                            <i class="mdi mdi-file-send" data-bs-container="#tooltip-container" data-bs-toggle="tooltip" title="Action"></i>
                                                        </a>
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>

                                    <!-- Table End-->

                                </div>
                            </div> <!-- end col-->

                        </div>
                        <!-- end row -->

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row-->


    </div> <!-- container -->
    <!--------Model View End here-------->
    </div> <!-- content -->
    <script>
        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
    </script>

@endsection
