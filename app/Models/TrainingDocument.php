<?php

namespace App\Models;

use Eloquent, Session;

/**
 * TrainingDocument Model
 */

class TrainingDocument extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'training_documents';

	protected $fillable = ['course_id', 'type', 'document', 'document_type', 'title', 'length'];
}// end TrainingDocument class
