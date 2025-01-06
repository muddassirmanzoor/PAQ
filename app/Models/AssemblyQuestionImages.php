<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssemblyQuestionImages extends Model
{
    use HasFactory;
    protected $table = 'assembly_question_images';
    protected $fillable = [
        'assembly_question_id','doc_link'
    ];
}
