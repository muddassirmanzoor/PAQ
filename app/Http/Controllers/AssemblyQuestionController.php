<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssemblyQuestion;
use App\Models\AssemblyQuestionImages;
use App\Models\AssemblyQuestionTrack;
use App\Models\AssemblyQuestionTrackImages;
use App\Models\Departments;
use App\Models\Schools;
use App\Models\SchoolVisit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AssemblyQuestionController extends Controller
{
    /**
     * Display a form of the Assembly Question.
     */
    public function dashboard(Request $request)
    {
        $data = AssemblyQuestion::dashboard($request);
        $departments = Departments::get();

        $deptUsers = '';
        if(!is_null($request->department_id)){
            $deptUsers =User::where('department_id', $request->department_id)->get();
        }

        return view('dashboard', compact('data','request', 'departments', 'deptUsers'));
    }
    /**
     * Display a form of the Assembly Question.
     */
    public function assemblyQuestion()
    {
        return view('assembly-question');
    }

    /**
     * Display a form of the Edit Assembly Question.
     */
    public function editAssemblyQuestion($question_id)
    {
        $question_id = decrypt($question_id);

        $question = AssemblyQuestion::where('id', $question_id)->first();

        return view('edit-assembly-question', compact('question'));
    }

    /**
     * Display list of Assembly Question.
     */
    public function assemblyQuestionList()
    {
        $assemblyQuestions = new AssemblyQuestion;
        if (!Auth::user()->hasRole('DEO')) {
            $assemblyQuestions = $assemblyQuestions->where('assign_to', Auth::user()->id)->whereNot('status', 'Accepted');
        }
        $assemblyQuestions = $assemblyQuestions->with('assigned_to', 'track.assignedBy')->where('status','!=', 'Archived')
            ->where('status','!=', 'Completed')->orderBy('id','desc')->paginate(10);

        return view('assembly-question-list', compact('assemblyQuestions'));
    }

    /**
     * Display list of Assembly Question.
     */
    public function filterAssemblyQuestionList(Request $request)
    {
        $assemblyQuestions = AssemblyQuestion::filterAssemblyQuestionList($request);
        return view('assembly-question-list', compact('assemblyQuestions'));
    }
    /**
     * Display list of Assembly Question.
     */
    public function archivedAssemblyQuestionList()
    {
        $assemblyQuestions = AssemblyQuestion::where('status', 'Archived')->orderBy('id','desc')->paginate(10);

        return view('assembly-question-list', compact('assemblyQuestions'));
    }

    /**
     * Display list of Assembly Question.
     */
    public function filterArchivedAssemblyQuestionList(Request $request)
    {
        $assemblyQuestions = AssemblyQuestion::filterArchivedAssemblyQuestionList($request);

        return view('assembly-question-list', compact('assemblyQuestions'));
    }

    /**
     * Display list of Assembly Question.
     */
    public function acceptedAssemblyQuestionList()
    {
        $assemblyQuestions = new AssemblyQuestion;
        if (!Auth::user()->hasRole('DEO')) {
            $assemblyQuestions = $assemblyQuestions->where('assign_to', Auth::user()->id)->where('status', 'Accepted');
        }
        $assemblyQuestions = $assemblyQuestions->with('assigned_to', 'track.assignedBy')->orderBy('id','desc')->paginate(10);

        $departments = Departments::get();
        return view('accepted-assembly-question-list', compact('assemblyQuestions', 'departments'));
    }

    /**
     * Display list of Assembly Question.
     */
    public function forwardedAssemblyQuestionList()
    {
        $questions_ids = AssemblyQuestionTrack::where('assigned_by',  Auth::user()->id)->pluck('assembly_question_id');
        $assemblyQuestions = AssemblyQuestion::whereIn('id', $questions_ids)->with('assigned_to', 'track.assignedBy', 'track.assignedTo')->orderBy('id','desc')->paginate(10);

        $departments = Departments::get();
        return view('accepted-assembly-question-list', compact('assemblyQuestions', 'departments'));
    }

    /**
     * Display list of Assembly Question.
     */
    public function completedAssemblyQuestionList()
    {
        $assemblyQuestions = AssemblyQuestion::where('status', 'Completed')->with('assigned_to', 'track.assignedBy', 'track.assignedTo')->orderBy('id','desc')->paginate(10);

        $departments = Departments::get();
        return view('accepted-assembly-question-list', compact('assemblyQuestions', 'departments'));
    }

    /**
     * Display list of Assembly Question.
     */
    public function forwardAssemblyQuestion($question_id)
    {
        $question_id = decrypt($question_id);

        $question = AssemblyQuestion::where('id', $question_id)->with('assigned_to','questionImages', 'track.assignedBy', 'track.trackImages')->first();
        $track = $question->track->first();
        $tracks = $question->track;
        $departments = Departments::get();
        $user_id = Auth::user()->id;

        $sedUsers = User::where('department_id', 1)->whereNot('id', $user_id)->get();
        return view('forward-assembly-question', compact('question', 'departments','track', 'user_id', 'tracks','sedUsers'));
    }

    /**
     * Submit Question.
     */
    public function saveAssemblyQuestion(Request $request): RedirectResponse
    {
        $request->validate([
            'dairy_no' => ['required', 'regex:/^[a-zA-Z0-9\/\-!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/', 'unique:assembly_question,dairy_no'],
            'assembly_question_no' => ['required', 'regex:/^[a-zA-Z0-9\/\-!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/','unique:assembly_question,assembly_question_no'],
            'receivedBy' => ['required', 'regex:/^[a-zA-Z0-9\s!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/'],
            'raisedBy' => ['required', 'regex:/^[a-zA-Z0-9\s!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/'],
            'subject' => ['required', 'regex:/^[a-zA-Z0-9\s!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/'],
            'assbSessionDate' => 'required|date|before_or_equal:today',
            'letterIssuanceDate' => 'required|date|after_or_equal:assbSessionDate',
            'recivingDate' => 'required|date|after_or_equal:letterIssuanceDate',
            'assignTo' => 'required',
            'description' => 'required',
        ],
            [
                'assbSessionDate.before_or_equal' => 'The Assb. Session Date must be today or earlier.',
                'letterIssuanceDate.after_or_equal' => 'The Letter Issuance Date must be after or equal to Assb. Session Date.',
                'recivingDate.after_or_equal' => 'The Receiving Date must be after or equal to Letter Issuance Date.',
                'file.max' => 'The file size must not exceed 10 MB.',
            ]);
        $data = $request->all();
        $filePaths = []; // Array to store file paths

        if ($request->file('files')) { // Check if there are multiple files
            $request->validate([
                'files.*' => 'max:10240', // Validate each file
            ], [
                'files.*.max' => 'The file size must not exceed 10 MB.',
            ]);

            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $sanitizedOriginalName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
                $fileName = $data['dairy_no'] . '_' . $sanitizedOriginalName. '.' . $extension;
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $filePaths[] = $filePath; // Add the file path to the array
            }
        }
        $status = 'Forwarded';
        if ($data['assignTo'] == 0) {
            $status = 'Archived';
        }

        $assemblyQuestion = AssemblyQuestion::create([
            'dairy_no' => $data['dairy_no'],
            'assembly_question_no' => $data['assembly_question_no'],
            'received_by' => $data['receivedBy'],
            'raised_by' => $data['raisedBy'],
            'subject' => $data['subject'],
            'assembly_session_date' => $data['assbSessionDate'],
            'letter_issuance_date' => $data['letterIssuanceDate'],
            'receiving_date' => $data['recivingDate'],
            'assign_to' => $data['assignTo'],
            'description' => $data['description'],
            'status' => $status,
            'created_by' => Auth::user()->id,
        ]);

        // Create records in AssemblyQuestionImages for each file
        foreach ($filePaths as $filePath) {
            AssemblyQuestionImages::create([
                'assembly_question_id' => $assemblyQuestion->id,
                'doc_link' => '/storage/' . $filePath,
            ]);
        }

        if ($data['assignTo'] != 0) {
            AssemblyQuestionTrack::create([
                'assembly_question_id' => $assemblyQuestion->id,
                'assign_to' => $data['assignTo'],
                'assigned_by' => Auth::user()->id,
                'status' => $status,
                'status_by' => $status,

            ]);
        }


        return redirect()->intended('assembly-question-list');

    }

    /**
     * Update Question.
     */
    public function updateAssemblyQuestion(Request $request): RedirectResponse
    {
        $data = $request->all();

        $request->validate([
            'dairy_no' => 'required|regex:/^[a-zA-Z0-9\/\-!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/|unique:assembly_question,dairy_no,' . $data['question_id'],
            'assembly_question_no' => 'required|regex:/^[a-zA-Z0-9\/\-!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/|unique:assembly_question,assembly_question_no,' . $data['question_id'],
            'receivedBy' => ['required', 'regex:/^[a-zA-Z0-9\s!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/'],
            'raisedBy' => ['required', 'regex:/^[a-zA-Z0-9\s!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/'],
            'subject' => ['required', 'regex:/^[a-zA-Z0-9\s!@#\$%\^&\*\(\)_\+=\[\]\{\};:,.<>?]+$/'],
            'assbSessionDate' => 'required|date|before_or_equal:today',
            'letterIssuanceDate' => 'required|date|after_or_equal:assbSessionDate',
            'recivingDate' => 'required|date|after_or_equal:letterIssuanceDate',
            'description' => 'required',
        ],
            [
                'assbSessionDate.before_or_equal' => 'The Assb. Session Date must be today or earlier.',
                'letterIssuanceDate.after_or_equal' => 'The Letter Issuance Date must be after or equal to Assb. Session Date.',
                'recivingDate.after_or_equal' => 'The Receiving Date must be after or equal to Letter Issuance Date.',
                'file.max' => 'The file size must not exceed 10 MB.',
            ]);
        $filePaths = []; // Array to store file paths

        if ($request->file('files')) { // Check if there are multiple files
            $request->validate([
                'files.*' => 'max:10240', // Validate each file
            ], [
                'files.*.max' => 'The file size must not exceed 10 MB.',
            ]);

            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $sanitizedOriginalName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
                $fileName = $data['dairy_no'] . '_' . $sanitizedOriginalName. '.' . $extension;
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $filePaths[] = $filePath; // Add the file path to the array
            }
        }
        // Retrieve the existing record
        $assemblyQuestion = AssemblyQuestion::findOrFail($data['question_id']);
        $assemblyQuestion->update([
            'dairy_no' => $data['dairy_no'],
            'assembly_question_no' => $data['assembly_question_no'],
            'received_by' => $data['receivedBy'],
            'raised_by' => $data['raisedBy'],
            'subject' => $data['subject'],
            'assembly_session_date' => $data['assbSessionDate'],
            'letter_issuance_date' => $data['letterIssuanceDate'],
            'receiving_date' => $data['recivingDate'],
            'description' => $data['description'],
            'updated_by' => Auth::user()->id,
        ]);

        // Create records in AssemblyQuestionImages for each file
        foreach ($filePaths as $filePath) {
            AssemblyQuestionImages::create([
                'assembly_question_id' => $assemblyQuestion->id,
                'doc_link' => '/storage/' . $filePath,
            ]);
        }


        return redirect()->intended('assembly-question-list');

    }

    /**
     * Submit Question Track.
     */
    public function saveAssemblyQuestionTrack(Request $request): RedirectResponse
    {
        $data = $request->all();
        $filePaths = []; // Array to store file paths

        if ($request->file('files')) { // Check if there are multiple files
            $request->validate([
                'files.*' => 'max:10240', // Validate each file
            ], [
                'files.*.max' => 'The file size must not exceed 10 MB.',
            ]);

            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $sanitizedOriginalName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
                $fileName = $data['dairyNo'] . '_' . $sanitizedOriginalName. '.' . $extension;
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $filePaths[] = $filePath; // Add the file path to the array
            }
        }

        $status = 'Assigned';

        if(!Auth::user()->hasRole('SO-AB')){
            if (Auth::user()->hasRole('DS') && $data['action'] == 'Approve') {
                $status = 'Approved';
            } elseif (Auth::user()->hasRole('AS') && $data['action'] == 'Approve') {
                $status = 'Approved';
            } elseif (Auth::user()->hasRole('Minister') && $data['action'] == 'Approve') {
                $status = 'Approved';
            } elseif (Auth::user()->hasRole('Assembly') && $data['action'] == 'Approve') {
                $status = 'Completed';
            }else {
                if ($data['action'] == 'Not Relevant') {
                    $status = 'Returned';
                } elseif ($data['action'] == 'Reply') {
                    $status = 'Replied';
                }
            }
        }

        if($data['action'] == 'Reject'){
            $status = 'Rejected';
        }

        $question_id = $data['question_id'];
        $user_id = Auth::user()->id;

        if(is_null($data['assignTo'])){
            $data['assignTo'] = $user_id;
        }
        //Assembly Question
        $question = AssemblyQuestion::where('id', $question_id)->first();
        $question->status = $status;
        $question->updated_by = $user_id;
        $question->assign_to = $data['assignTo'];
        $question->save();

        //Assembly Question Track
        $question_track = AssemblyQuestionTrack::where('assembly_question_id', $question_id)->where('assign_to', $user_id)->orderBy('id','desc')->first();
        $question_track->forwarded_at = date('Y-m-d H:i:s');
        $question_track->save();

        //Assembly Question Track
        if ($data['assignTo'] != 0) {
            $assemblyQuestionTrack = AssemblyQuestionTrack::create([
                'assembly_question_id' => $question_id,
                'assign_to' => $data['assignTo'],
                'action' => $data['action'],
                'comments' => $data['puc_comments'],
                'assigned_by' => $user_id,
                'status' => $status,
                'status_by' => $status,
            ]);

            // Create records in AssemblyQuestionImages for each file
            foreach ($filePaths as $filePath) {
                AssemblyQuestionTrackImages::create([
                    'assembly_question_track_id' => $assemblyQuestionTrack->id,
                    'doc_link' => '/storage/' . $filePath,
                ]);
            }
        }

        return redirect()->intended('forwarded-assembly-question-list');

    }

    /**
     * Update Question.
     */
    public function acceptAssemblyQuestion($question_id): RedirectResponse
    {
        $question_id = decrypt($question_id);
        $user_id = Auth::user()->id;

        //Assembly Question
        $question = AssemblyQuestion::where('id', $question_id)->first();
        $question->status = 'Accepted';
        $question->updated_by = $user_id;
        $question->save();

        //Assembly Question Track
        $question_track = AssemblyQuestionTrack::where('assembly_question_id', $question_id)->where('assign_to', $user_id)->orderBy('id','desc')->first();
        $question_track->status = 'Accepted';
        $question_track->accepted_at = date('Y-m-d H:i:s');
        $question_track->save();

        return redirect()->intended('accepted-assembly-question-list');

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersByDepartment($id)
    {
        $users = User::where('department_id', $id)->whereNot('id', Auth::user()->id)->get();
        return response()->json($users);
    }

}
