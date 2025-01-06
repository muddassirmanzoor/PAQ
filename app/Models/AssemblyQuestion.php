<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssemblyQuestion extends Model
{
    use HasFactory;
    protected $table = 'assembly_question';
    protected $fillable = [
        'dairy_no','assembly_question_no','received_by','raised_by','subject', 'assembly_session_date','letter_issuance_date',
        'receiving_date','assign_to', 'description', 'created_by', 'updated_by', 'status'
    ];

    public function assigned_to(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'assign_to');
    }

    public function track(): HasMany
    {
        return $this->hasMany(AssemblyQuestionTrack::class, 'assembly_question_id', 'id')->orderBy('id','desc');
    }

    public function questionImages(): HasMany
    {
        return $this->hasMany(AssemblyQuestionImages::class, 'assembly_question_id', 'id');
    }

    public static function filterAssemblyQuestionList($request){

        $assemblyQuestions = AssemblyQuestion::query();
        if (!Auth::user()->hasAnyRole(['DEO', 'Sectary'])) {
            $assemblyQuestions = $assemblyQuestions->where('assign_to', Auth::user()->id)->whereNot('status', 'Accepted');
        }
        // Check for filters and apply them
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $raisedBy = $request->input('raised_by');
        $listType = $request->input('list_type');
        $assign_to = $request->input('assign_to');


        // Convert input dates from Pakistan time to UTC
        if (!is_null($dateFrom)) {
            $dateFrom = Carbon::parse($dateFrom, 'Asia/Karachi')->startOfDay()->setTimezone('UTC');
        }
        if (!is_null($dateTo)) {
            $dateTo = Carbon::parse($dateTo, 'Asia/Karachi')->endOfDay()->setTimezone('UTC');
        }

        // Apply date filters to the AssemblyQuestion model
        if (!is_null($dateFrom) && !is_null($dateTo)) {
            $assemblyQuestions = $assemblyQuestions->whereBetween('receiving_date', [$dateFrom, $dateTo]);
        } elseif (!is_null($dateFrom)) {
            $assemblyQuestions = $assemblyQuestions->where('receiving_date', '>=', $dateFrom);
        } elseif (!is_null($dateTo)) {
            $assemblyQuestions = $assemblyQuestions->where('receiving_date', '<=', $dateTo);
        }

        if (!is_null($raisedBy)) {
            $assemblyQuestions = $assemblyQuestions->where('raised_by', 'like', '%' . $raisedBy . '%');
        }

        // Apply the assign_to filter
        if (!is_null($assign_to)) {
            $assemblyQuestions->whereHas('track', function ($query) use ($assign_to) {
                $query->where('assign_to', $assign_to);
            });
        }

        // Subquery to get the latest track entry for each assembly question
        $latestTracks = DB::table('assembly_question_track')
            ->select('assembly_question_id', DB::raw('MAX(id) as latest_track_id'))
            ->groupBy('assembly_question_id');

        // Conditionally join with the latest track entry for specific listType filters
        if ($listType == 'response_awaited' || $listType == 'delayed') {
            $assemblyQuestions = $assemblyQuestions
                ->leftJoinSub($latestTracks, 'latest_tracks', function ($join) {
                    $join->on('assembly_question.id', '=', 'latest_tracks.assembly_question_id');
                })
                ->leftJoin('assembly_question_track', 'latest_tracks.latest_track_id', '=', 'assembly_question_track.id')
                ->whereNotNull('assembly_question_track.accepted_at');
        }

        // Apply listType filters
        if ($listType == 'forwarded_questions') {
            $assemblyQuestions = $assemblyQuestions->where('status', 'Forwarded');
        }elseif ($listType == 'response_awaited') {
            $assemblyQuestions = $assemblyQuestions->where('assembly_question.status', '!=', 'Completed')
                ->where('assembly_question.updated_at', '>=', Carbon::today()->subDays(3)->startOfDay()->toDateTimeString());
        } elseif ($listType == 'delayed') {
            $assemblyQuestions = $assemblyQuestions->where('assembly_question.status', '!=', 'Completed')
                ->where('assembly_question.updated_at', '<', Carbon::today()->subDays(3)->startOfDay()->toDateTimeString());
        }elseif($listType == 'answered_questions'){
            $assemblyQuestions = $assemblyQuestions->where('status', 'Completed');
        }elseif($listType == 'assigned_questions'){
            $assemblyQuestions = $assemblyQuestions->whereIn('status', ['Assigned', 'Replied']);
        }
        // Select the specific columns from the assembly_question table to avoid ID conflicts
        return $assemblyQuestions->select('assembly_question.*')
            ->with('assigned_to', 'track.assignedBy', 'track.assignedTo')
            ->where('assembly_question.status', '!=', 'Archived')
            ->orderBy('assembly_question.id', 'desc')
            ->get();
    }

    public static function filterArchivedAssemblyQuestionList($request){

        $assemblyQuestions = new AssemblyQuestion;

        // Check for filters and apply them
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $raisedBy = $request->input('raised_by');

        // Convert input dates from Pakistan time to UTC
        if (!is_null($dateFrom)) {
            $dateFrom = Carbon::parse($dateFrom, 'Asia/Karachi')->startOfDay()->setTimezone('UTC');
        }
        if (!is_null($dateTo)) {
            $dateTo = Carbon::parse($dateTo, 'Asia/Karachi')->endOfDay()->setTimezone('UTC');
        }
        // Apply date filters to the AssemblyQuestion model
        if (!is_null($dateFrom) && !is_null($dateTo)) {
            $assemblyQuestions = $assemblyQuestions->whereBetween('receiving_date', [$dateFrom, $dateTo]);
        } elseif (!is_null($dateFrom)) {
            $assemblyQuestions = $assemblyQuestions->where('receiving_date', '>=', $dateFrom);
        } elseif (!is_null($dateTo)) {
            $assemblyQuestions = $assemblyQuestions->where('receiving_date', '<=', $dateTo);
        }

        if (!is_null($raisedBy)) {
            $assemblyQuestions = $assemblyQuestions->where('raised_by', 'like', '%' . $raisedBy . '%');
        }

        return $assemblyQuestions->where('status', 'Archived')->orderBy('id','desc')->with('assigned_to')->paginate(10);
    }

    public static function dashboard($request){

        // Create the base query with potential date filters
        $assemblyQuestions = AssemblyQuestion::query();

        // Check for filters and apply them
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $assign_to = $request->input('assign_to');

        // Convert input dates from Pakistan time to UTC
        if (!is_null($dateFrom)) {
            $dateFrom = Carbon::parse($dateFrom, 'Asia/Karachi')->startOfDay()->setTimezone('UTC');
        }
        if (!is_null($dateTo)) {
            $dateTo = Carbon::parse($dateTo, 'Asia/Karachi')->endOfDay()->setTimezone('UTC');
        }

        // Apply date filters to the AssemblyQuestion model
        if (!is_null($dateFrom) && !is_null($dateTo)) {
            $assemblyQuestions = $assemblyQuestions->whereBetween('receiving_date', [$dateFrom, $dateTo]);
        } elseif (!is_null($dateFrom)) {
            $assemblyQuestions = $assemblyQuestions->where('receiving_date', '>=', $dateFrom);
        } elseif (!is_null($dateTo)) {
            $assemblyQuestions = $assemblyQuestions->where('receiving_date', '<=', $dateTo);
        }

        // Apply the assign_to filter
        if (!is_null($assign_to)) {
            $assemblyQuestions->whereHas('track', function ($query) use ($assign_to) {
                $query->where('assign_to', $assign_to);
            });
        }

        // Clone the query builder to reuse it
        $baseQuery = clone $assemblyQuestions;

        // Total questions count
        $total_questions = $baseQuery->where('assembly_question.status', '!=', 'Archived')->count();

        // Forwarded questions count
        $forwarded_questions = (clone $baseQuery)->where('assembly_question.status', 'Forwarded')->count();

        $assigned_questions = (clone $baseQuery)->whereIn('assembly_question.status', ['Assigned', 'Replied'])->count();

        // Answered questions count
        $answered_questions = (clone $baseQuery)->where('assembly_question.status', 'Completed')->count();

        // Subquery to get the latest track entry for each assembly question
        $latestTracks = DB::table('assembly_question_track')
            ->select('assembly_question_id', DB::raw('MAX(id) as latest_track_id'))
            ->groupBy('assembly_question_id');

        // Count of questions updated within the last 3 days
        $countWithin3Days = (clone $baseQuery)
            ->joinSub($latestTracks, 'latest_tracks', function ($join) {
                $join->on('assembly_question.id', '=', 'latest_tracks.assembly_question_id');
            })
            ->join('assembly_question_track as track', 'latest_tracks.latest_track_id', '=', 'track.id')
            ->whereNotNull('track.accepted_at')
            ->where('assembly_question.updated_at', '>=', Carbon::today()->subDays(3)->startOfDay()->toDateTimeString())
            ->where('assembly_question.status', '!=', 'Archived')
            ->where('assembly_question.status', '!=', 'Completed')
            ->count();

        // Count of questions updated before the last 3 days
        $countBefore3Days = (clone $baseQuery)
            ->joinSub($latestTracks, 'latest_tracks', function ($join) {
                $join->on('assembly_question.id', '=', 'latest_tracks.assembly_question_id');
            })
            ->join('assembly_question_track as track', 'latest_tracks.latest_track_id', '=', 'track.id')
            ->whereNotNull('track.accepted_at')
            ->where('assembly_question.updated_at', '<', Carbon::today()->subDays(3)->startOfDay()->toDateTimeString())
            ->where('assembly_question.status', '!=', 'Archived')
            ->where('assembly_question.status', '!=', 'Completed')
            ->count();

        $assignments_count = AssemblyQuestion::getUserAssignmentCounts($baseQuery);

        $data['total_questions'] = $total_questions;
        $data['forwarded_questions'] = $forwarded_questions;
        $data['assigned_questions'] = $assigned_questions;
        $data['response_awaited'] = $countWithin3Days;
        $data['delayed'] = $countBefore3Days;
        $data['answered_questions'] = $answered_questions;
        $data['assignments_count'] = $assignments_count;
        return $data;
    }

    public static function getUserAssignmentCounts($baseQuery)
    {
        // Subquery to get the latest track entries for each question_id and assign_to
        $latestTrackSubquery = AssemblyQuestionTrack::select(
            'assembly_question_id',
            'assign_to',
            DB::raw('MAX(id) as latest_id') // Get the latest ID for the group
        )
            ->groupBy('assembly_question_id', 'assign_to');


        // Main query to aggregate data based on the latest track entries
        $tracksQuery = AssemblyQuestionTrack::select(
            'assembly_question_track.assign_to',
            DB::raw('COUNT(DISTINCT CASE WHEN accepted_at IS NOT NULL THEN assembly_question_track.assembly_question_id ELSE NULL END) AS question_count'),

//            DB::raw('count(distinct assembly_question_track.assembly_question_id) as question_count'),
            DB::raw('SUM(CASE WHEN accepted_at IS NOT NULL AND DATEDIFF(assembly_question_track.forwarded_at, assembly_question_track.created_at) <= 3 THEN 1 ELSE 0 END) as in_time_count'),
//            DB::raw('sum(case when DATEDIFF(IFNULL(assembly_question_track.forwarded_at, CURDATE()), assembly_question_track.created_at) <= 3 then 1 else 0 end) as in_time_count'),
            DB::raw('sum(case WHEN accepted_at IS NOT NULL AND DATEDIFF(IFNULL(assembly_question_track.forwarded_at, CURDATE()), assembly_question_track.created_at) > 3 then 1 else 0 end) as delayed_count')
        )
            ->joinSub($latestTrackSubquery, 'latest_track', function ($join) {
                $join->on('assembly_question_track.id', '=', 'latest_track.latest_id');
            })
            ->joinSub($baseQuery, 'assembly_question', function ($join) {
                $join->on('assembly_question_track.assembly_question_id', '=', 'assembly_question.id');
            })
            ->where('assembly_question_track.assign_to', '!=', 2)
            ->groupBy('assembly_question_track.assign_to')
            ->with('assignedTo:id,designation')
            ->get();

        // Process the results
        $result = $tracksQuery->map(function ($track) {
            return [
                'assign_to' => $track->assign_to,
                'question_count' => $track->question_count,
                'in_time_count' => (int)$track->in_time_count,
                'delayed_count' => (int)$track->delayed_count,
                'designation' => $track->assignedTo->designation,
            ];
        });

        // Extract necessary arrays
        $designations = $result->pluck('designation')->all();
        $inTimeCounts = $result->pluck('in_time_count')->all();
        $delayedCounts = $result->pluck('delayed_count')->all();
        $assignedCounts = $result->pluck('question_count')->all();

        return [
            'data' => $result->toArray(),
            'designations' => $designations,
            'inTimeCounts' => $inTimeCounts,
            'delayedCounts' => $delayedCounts,
            'questionCounts' => $assignedCounts,
        ];
    }
}
