<?php

namespace App\Observers;

use App\ExerciseBook;

class ExerciseBookObserver
{
    /**
     * 問題集削除に伴って、紐づいてる問題も削除
     *
     * @param ExerciseBook $exerciseBook
     * @return void
     */
    public function deleting(ExerciseBook $exerciseBook)
    {
        $exerciseBook->problem()->each(function ($problem) {
            $problem->delete();
        });
    }
}
