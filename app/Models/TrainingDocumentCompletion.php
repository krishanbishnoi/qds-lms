<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingDocumentCompletion Model
 */

class TrainingDocumentCompletion extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'training_document_completions';

    protected $fillable = ['document_id', 'user_id', 'pdf_pages', 'video_duration', 'status'];
}// end Question class
