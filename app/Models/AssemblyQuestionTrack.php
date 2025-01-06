<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssemblyQuestionTrack extends Model
{
    use HasFactory;
    protected $table = 'assembly_question_track';
    protected $fillable = [
        'assembly_question_id','assign_to','accepted_at', 'action', 'comments', 'status', 'assigned_by', 'status_by', 'forwarded_at'
    ];


    public function assignedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'assigned_by');
    }
    public function assignedTo(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'assign_to');
    }

    public function trackImages(): HasMany
    {
        return $this->hasMany(AssemblyQuestionTrackImages::class, 'assembly_question_track_id', 'id');
    }
}
