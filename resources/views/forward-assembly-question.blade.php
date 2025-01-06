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
                            <li class="breadcrumb-item active">Received Question Forwarded</li>

                        </ol>
                    </div>
                    <h4 class="page-title">Assigned Question Forwarded</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        @if($track['assign_to'] == $user_id && $track['status'] != 'Completed')
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

                                    <!-------------------------->
                                    <form action="{{url('assembly-question-track')}}" method="POST" id="markPUCForm" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="question_id" value="{{$question['id']}}">
                                        <div class="row g-2">
                                            <div class="col-lg-12">
                                                <div class="card bg-success text-white mb-1">
                                                    <div class="card-body" style="padding: 10px;">
                                                        <div class="text-left" >
                                                            <h4>Note:</h4>
                                                            <p> Following Pre-requisites are required to answer Assembly questions/Attending Assembly Session.</p>
                                                            <ul>
                                                                <li>Detailed Facts  </li>
                                                                <li>Liason with all concerned</li>
                                                                <li>Concerned CEO/ Officer(s) shall be available in-person on the day scheduled for questions of SED. </li>
                                                            </ul>
                                                        </div>
                                                    </div> <!-- end card-body-->
                                                </div>
                                            </div>
                                            <!--------Question Info Start--------->
                                            <div class="col-lg-6">
                                                <div class="mb-1">
                                                    <label for="dairyno" class="form-label">Diary No  </label>
                                                    <input type="text" id="dairyno" name="dairyNo" class="form-control" placeholder="Enter Dairy No"  value="{{$question['dairy_no']}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-1">
                                                    <label for="assamblyQuestionNo" class="form-label">Ass.Question No</label>
                                                    <input type="text" id="assamblyQuestionNo"  name="assamblyQuestionNo" class="form-control" placeholder="Enter Question No"  value="{{$question['assembly_question_no']}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-1">
                                                    <label for="subject" class="form-label">Subject</label>
                                                    <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter File File Name Or Subject" value="{{$question['subject']}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-1">
                                                    <label for="project-overview" class="form-label"> Description</label>
                                                    <p>  {{$question['description']}}</p>
                                                </div>
                                            </div>
                                            @if(!Auth::user()->hasRole('SO-AB'))
                                                <div class="col-lg-12">
                                                    <div class="mb-1">
                                                        <label for="project-overview" class="form-label">Last Comment</label>
                                                        <p> {!! $track['comments'] !!} </p>
                                                    </div>
                                                </div>
                                            @endif
                                            <!--------Question Info End--------->
                                            @if($track->assigned_by != 1)
                                                <div class="mb-1 col-md-6">
                                                    <label for="assignTo" class="form-label">Assigned From</label>
                                                    <input type="text" id="assignFrom" name="assignFrom" class="form-control" placeholder="assignTo" value="{{$track->assignedBy->full_name }}" readonly>

                                                </div>
                                                <div class="mb-1 col-md-6">
                                                    <label for="markTo" class="form-label">Action</label>
                                                    <select id="markTo" name="action" class="form-select select2">
                                                        <option value="">Select Action</option>
                                                        @if(Auth::user()->hasRole('SO-AB') || Auth::user()->hasRole('DS')|| Auth::user()->hasRole('AS')|| Auth::user()->hasRole('Minister')|| Auth::user()->hasRole('Assembly'))
                                                            <option value="Approve">Approve</option>
                                                            <option value="Reject">Reject</option>
                                                        @else
                                                            <option value="Not Relevant">Not Relevant</option>
                                                            <option value="Reply">Reply </option>
                                                        @endif

                                                    </select>
                                                </div>
                                            @else
                                                <input name="action" value="" type="hidden">
                                            @endif

                                            <div class="mb-1 col-md-6" id="submitToContainer">
                                                <label for="submitTo" class="form-label">Assign To / Submit To</label>
                                                <select id="submitTo" name="department_id" class="form-select select2" required="">
                                                    @if(Auth::user()->hasRole('SO-AB'))
                                                    <option value="">Select Department</option>
                                                    @foreach($departments as $department)
                                                        <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                                    @endforeach
                                                    @else
                                                        <option value="1">SED</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="mb-1 col-md-6" id="potContainer">
                                                <label for="pot" class="form-label">Point of Contact</label>
                                                <select id="pot" name="assignTo" class="form-select select2" required="">
                                                    <option value="">Select Point of Contact</option>
                                                @if(Auth::user()->hasRole('SO-AB'))
                                                    @else
                                                        @if(Auth::user()->department_id == 1)
                                                        @foreach($sedUsers as $user)
                                                            <option value="{{$user['id']}}">{{$user['full_name']}}</option>
                                                        @endforeach
                                                        @else
                                                        <option value="2">SO-AB</option>
{{--                                                        @if(Auth::user()->hasRole('DS'))--}}
{{--                                                        <option value="30">Additional Sectary</option>--}}
{{--                                                            @elseif(Auth::user()->hasRole('AS'))--}}
{{--                                                            <option value="30">Deputy Sectary</option>--}}
{{--                                                            <option value="29">Minister</option>--}}
{{--                                                        @endif--}}
                                                    @endif
                                                    @endif
                                                </select>
                                            </div>

                                        <div class="col-xl-12">
                                            <div class="mb-1">
                                                <label for="FileComment" class="form-label">Comments</label>
                                                <textarea class="form-control" rows="5" name="puc_comments" placeholder="Enter Comments" require=""></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="mb-1">
                                                <label for="fileinput" class="form-label">Attach File Here <sup style=" color: red;"></sup></label>
                                                <ul>
                                                    <li>Certificate with Signature of Concerned Officer submitting reply of Assembly Questions.</li>
                                                    <li>Proof of physical Verification. e.g. Pictures, Documentary evidence while preparing answer to Assembly Question.</li>
                                                </ul>
                                                <input type="file" name="files[]" id="fileinput" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="mb-3">
                                                @foreach($tracks as $images)
                                                    @foreach($images['trackImages'] as $image)
                                                        <div class="attach-doc-file">
                                                            <a href="{{ url($image['doc_link']) }}" target="_blank">
                                                                @php
                                                                    $extension = pathinfo($image['doc_link'], PATHINFO_EXTENSION);
                                                                @endphp
                                                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                                    <img src="{{ asset($image['doc_link']) }}" alt="Image">
                                                                @elseif($extension === 'pdf')
                                                                    <img src="{{ asset('assets/images/PDF_file_icon.png') }}" alt="PDF Icon">
                                                                @elseif(in_array($extension, ['doc', 'docx']))
                                                                    <img src="{{ asset('assets/images/word.jpg') }}" alt="Word Icon">
                                                                @else
                                                                    <img src="{{ asset('assets/images/Files_App_icon.png') }}" alt="File Icon">
                                                                @endif
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endforeach

                                                    @foreach($question['questionImages'] as $qImage)
                                                        <div class="attach-doc-file">
                                                            <a href="{{ url($qImage['doc_link']) }}" target="_blank">
                                                                @php
                                                                    $extension = pathinfo($qImage['doc_link'], PATHINFO_EXTENSION);
                                                                @endphp
                                                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                                    <img src="{{ asset($qImage['doc_link']) }}" alt="Image">
                                                                @elseif($extension === 'pdf')
                                                                    <img src="{{ asset('assets/images/PDF_file_icon.png') }}" alt="PDF Icon">
                                                                @elseif(in_array($extension, ['doc', 'docx']))
                                                                    <img src="{{ asset('assets/images/word.jpg') }}" alt="Word Icon">
                                                                @else
                                                                    <img src="{{ asset('assets/images/Files_App_icon.png') }}" alt="File Icon">
                                                                @endif
                                                            </a>
                                                        </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                            <div class="col-xl-12">
                                                <div class="mb-3">

                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                    <!-------------------------->
                                </div>
                            </div> <!-- end col-->

                        </div>
                        <!-- end row -->

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        @endif
        <!-- end row-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="modal-title" id="PUCMarkModalLabel">Assembly Question Time Line</h4>
                        <!------------Quize Time Line Start Here-------------->
                        <div data-simplebar="" style="max-height: 419px;">
                            <div class="timeline-alt pb-0">
                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-gold-lighten text-primary timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-primary fw-bold mb-1 d-block">{{$question['assigned_to']['full_name']}} / {{$question['assigned_to']['designation']}}</a>
                                        <small>
                                        </small>
{{--                                        <p class="mb-0 pb-2">--}}
{{--                                            <small class="text-muted"><b class="text-info-green">Date and Time</b>--}}
{{--                                                {{date('Y-m-d', strtotime($question['track'][0]['created_at']))}} and   {{ \Carbon\Carbon::parse($question['track'][0]['created_at'])->timezone('Asia/Karachi')->format('H:i') }}</small>--}}
{{--                                        </p>--}}
                                    </div>
                                </div>
                                @foreach($tracks as $qTrack)
                                <div class="timeline-item">
                                    <i class="mdi mdi-upload bg-gold-lighten text-primary timeline-icon"></i>
                                    <div class="timeline-item-info">
                                        <a href="#" class="text-primary fw-bold mb-1 d-block">{{$qTrack['assignedBy']['full_name']}} / {{$qTrack['assignedBy']['designation']}}</a>
                                        <small>{{$qTrack['comments']}}
                                        </small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted"><b class="text-info-green">Date and Time</b>
                                                {{date('Y-m-d', strtotime($qTrack['created_at']))}} and   {{ \Carbon\Carbon::parse($qTrack['created_at'])->timezone('Asia/Karachi')->format('H:i') }}</small>
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <!-- end timeline -->
                        </div> <!-- end slimscroll -->
                        <!------------Quize Time Line End Here-------------->
                    </div>
                </div>
            </div>
        </div>


    </div> <!-- container -->
    </div> <!-- content -->
    <script>
        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
    </script>
    <script>
        $(document).ready(function() {
            $('#markTo').change(function() {
                var selectedAction = $(this).val();
                var userRole = "{{ Auth::user()->getRoleNames()[0] }}"; // Assuming a single role per user

                if (userRole === 'Assembly' && selectedAction === 'Approve') {

                    $('#submitToContainer').hide();
                    $('#potContainer').hide();
                    $('#submitTo').prop('required', false); // Make not required
                    $('#pot').prop('required', false);
                } else {
                    $('#submitToContainer').show();
                    $('#potContainer').show();
                    $('#submitTo').prop('required', true); // Make required
                    $('#pot').prop('required', true);
                }
            });
        });
    </script>
@endsection
