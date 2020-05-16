<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ExerciseBook extends Model
{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'exercise_books';

    public $timestamps = false;

    protected $dateFormat = "Y/m/d";

    protected $fillable = [
        'exercise_books_name_id', 'user_id','created_at', 'updated_at'
    ];

    public function user() :BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function problem() :BelongsTo
    {
        return $this->belongsTo('App\Problem');
    }

    public function exerciseBooksName() :BelongsTo
    {
        return $this->belongsTo('App\ExerciseBookName');
    }

    /**
     * 日付のフォーマットを変更
     *
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format("Y/m/d");
    }
}
