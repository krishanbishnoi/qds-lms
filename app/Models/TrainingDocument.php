<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingDocument Model
 */

class TrainingDocument extends Eloquent
{

	protected $table = 'training_documents';

	protected $fillable = ['course_id', 'training_id', 'type', 'document', 'document_type', 'title', 'length'];
}
