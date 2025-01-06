<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssemblyQuestionTrackImages extends Model
{
    use HasFactory;
    protected $table = 'assembly_question_track_images';
    protected $fillable = [
        'assembly_question_track_id','doc_link'
    ];
}
