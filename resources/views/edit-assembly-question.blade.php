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
                            <li class="breadcrumb-item active">Assembly Question </li>

                        </ol>
                    </div>
                    <h4 class="page-title">Assembly Question (Initiation/Assignment)  </h4>
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
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="create-puc" method="POST" action="{{url('update-assembly-question')}}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="question_id" value="{{$question['id']}}">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <div class="mb-3">
                                                <label for="dairyNo" class="form-label">Diary No  </label>
                                                <input type="text" id="dairyNo" value="{{$question['dairy_no']}}" name="dairy_no" class="form-control" placeholder="Enter Diary No">
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            <div class="mb-3">
                                                <label for="assamblyQuestionNo" class="form-label">Ass.Question No</label>
                                                <input type="text" id="assamblyQuestionNo" value="{{$question['assembly_question_no']}}"  name="assembly_question_no" class="form-control" placeholder="Enter Question No">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="receivedBy" class="form-label">Received By</label>
                                                <input type="text" id="receivedBy" value="{{$question['received_by']}}"   name="receivedBy" class="form-control" placeholder="Name of User Logged">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="raisedBy" class="form-label">Assb. Question Raised by</label>
                                                <input type="text" id="raisedBy" value="{{$question['raised_by']}}" name="raisedBy" class="form-control" placeholder="Enter Name">
                                                <!--<select class="select2 form-control select2 select2-hidden-accessible" id="received_from_department" name="received_from_dpt" data-toggle="select2" data-placeholder="Select MPA" data-select2-id="received_from_department" tabindex="-1" aria-hidden="true">
                                                    <option value="" data-select2-id="4">Select</option>
                                                    <option value="SED">Rana Sikandar Hayat</option>
                                                    <option value="QAED">Shaukat Basra</option>
                                                    <option value="PSPA">Agha Ali Haider</option>
                                                    <option value="PEIMA">Akhtar Hussain</option>
                                                    <option value="SPED">Ahsan Raza</option>
                                                    <option value="Other">Ayesha Iqbal</option>
                                                </select>-->
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="subject" class="form-label">Subject</label>
                                                <input type="text" id="subject" value="{{$question['subject']}}" name="subject" class="form-control" placeholder="Enter File Name Or Subject">
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            <div class="mb-3">
                                                <label for="assbSessionDate" class="form-label">Assb. Session Date</label>
                                                <input class="form-control" id="assbSessionDate" value="{{ isset($question['assembly_session_date']) ? \Carbon\Carbon::parse($question['assembly_session_date'])->format('Y-m-d') : '' }}"
                                                       type="date" name="assbSessionDate" max="{{ now()->format('Y-m-d') }}">
                                            </div>
                                        </div>

                                        <div class="col-xl-3">
                                            <div class="mb-3">
                                                <label for="letterIssuanceDate" class="form-label">Letter Issuance Date from Assb.</label>
                                                <input class="form-control" id="letterIssuanceDate" type="date" value="{{ isset($question['letter_issuance_date']) ? \Carbon\Carbon::parse($question['letter_issuance_date'])->format('Y-m-d') : '' }}"
                                                       name="letterIssuanceDate" max="{{ now()->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            <div class="mb-3">
                                                <label for="recivingDate" class="form-label">Receiving Date</label>
                                                <input class="form-control" id="recivingDate" type="date" value="{{ isset($question['receiving_date']) ? \Carbon\Carbon::parse($question['receiving_date'])->format('Y-m-d') : '' }}"
                                                       name="recivingDate" max="{{ now()->format('Y-m-d') }}">
                                            </div>
                                        </div>
{{--                                        <div class="col-xl-3">--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="assignTo" class="form-label">Assign To</label>--}}
{{--                                                <select class="select2 form-control select2 select2-hidden-accessible" id="assignTo" name="assignTo" data-toggle="select2" data-placeholder="Select User" data-select2-id="received_from_department" tabindex="-1" aria-hidden="true">--}}
{{--                                                    <option value="" data-select2-id="4">Select</option>--}}
{{--                                                    <option value="2">SO-AB</option>--}}
{{--                                                    <option value="0">Archive</option>--}}
{{--                                                    <option value="SOG">SOG</option>--}}
{{--                                                    <option value="SO B&P">SO B&P</option>--}}
{{--                                                    <option value="PMIU">PMIU</option>--}}
{{--                                                    <option value="QAED">QAED</option>--}}
{{--                                                    <option value="PCTB">PCTB</option>--}}
{{--                                                    <option value="CEO Lahore">CEO Lahore</option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="col-xl-12">
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea   class="form-control"  rows="7" name="description" placeholder="Enter Description" require>{{$question['description']}}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-xl-12">
                                            <div class="mb-3">
                                                <label for="example-fileinput" class="form-label">Attach File <sup style=" color: red;"></sup></label>
                                                <input type="file" id="example-fileinput" name="files[]" class="form-control" multiple="multiple">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="mb-3 text-right">
                                                <button type="submit" class="btn btn-primary"> Update <i class="mdi mdi-arrow-right-bold"></i></button>
                                            </div>
                                        </div>
                                    </div><!-- end row-->

                                </form><!-- end form-->

                            </div> <!-- end col-->

                        </div>
                        <!-- end row -->

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row-->


    </div> <!-- container -->

    </div> <!-- content -->
    <script>
        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
    </script>
@endsection
