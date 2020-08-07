<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use App\Traits\RelatedToFilePathS3;
use Illuminate\Support\Facades\Log;

class Problem extends Model
{
    use RelatedToFilePathS3;

    protected $fillable = [
        'content', 'answer', 'user_id', 'exercise_book_id', 'url', 'problem_img', 'answer_img'
    ];

    protected $hidden = [
        'created_at', 'exercise_book_id'
    ];


    public function exerciseBook(): BelongsTo
    {
        return $this->belongsTo('App\ExerciseBook');
    }

    /**
     * 問題と答えの新規登録
     *
     * @param [object]] $request
     * @param [object] $exercise_book
     * @return void
     */
    public function newRegister($request, $exercise_book)
    {
        $problem_img = $request->problem_image;
        $answer_img = $request->answer_image;

        if ($problem_img && !$answer_img) {
            $problem_img_path  = $this->createFilePath($problem_img);
            $this->saveFileToS3($problem_img, $problem_img_path);
            $this->fill([
                'content' => $request->problem,
                'answer' => $request->answer,
                'url' => $request->url,
                'user_id' => Auth::id(),
                'exercise_book_id' => $exercise_book->id,
                'problem_img' => $problem_img_path,
            ])->save();
            return;
        }

        if ($answer_img && !$problem_img) {
            $answer_img_path = $this->createFilePath($answer_img);
            $this->saveFileToS3($answer_img, $answer_img_path);
            $this->fill([
                'content' => $request->problem,
                'answer' => $request->answer,
                'url' => $request->url,
                'user_id' => Auth::id(),
                'exercise_book_id' => $exercise_book->id,
                'answer_img' => $answer_img_path
            ])->save();
            return;
        }

        if ($problem_img && $answer_img) {
            $problem_img_path = $this->createFilePath($problem_img);
            $answer_img_path = $this->createFilePath($answer_img);
            $this->saveFileToS3($problem_img, $problem_img_path);
            $this->saveFileToS3($answer_img, $answer_img_path);

            $this->fill([
                'content' => $request->problem,
                'answer' => $request->answer,
                'url' => $request->url,
                'user_id' => Auth::id(),
                'exercise_book_id' => $exercise_book->id,
                'problem_img' => $problem_img_path,
                'answer_img' => $answer_img_path
            ])->save();
            return;
        }

        $this->fill([
            'content' => $request->problem,
            'answer' => $request->answer,
            'url' => $request->url,
            'user_id' => Auth::id(),
            'exercise_book_id' => $exercise_book->id,
        ])->save();
    }

    /**
     * 問題の更新
     *
     * @param [type] $problem_id
     * @param [type] $request
     * @param [type] $exercise_book
     * @return void
     */
    public function problemUpdate($problem, $request, $exercise_book)
    {
        $problem->content = $request->problem;
        $problem->answer = $request->answer;
        $problem->user_id = Auth::id();
        $problem->exercise_book_id = $exercise_book->id;

        if ($request->url) {
            $problem->url = $request->url;
        } else {
            $problem->url = null;
        }

        // 問題が空の場合は、S3のデータは削除、DBのデータを空に
        if ($request->problem_image === 'null') {
            // 問題が既に存在している場合
            if (isset($problem->problem_img)) {
                $this->deleteFile($problem->problem_img);
            }
            $problem->problem_img = null;
        }

        // 問題に変更がある場合は、パスを生成してS３へ登録
        if ($request->problem_image !== $problem->problem_img && $request->problem_image !== 'null') {
            $problem_img_path = $this->createFilePath($request->problem_image);
            $this->saveFileToS3($request->problem_image, $problem_img_path);
            $problem->problem_img = $problem_img_path;
        }

        // 解答が空の場合は、S3のデータは削除、DBのデータを空に
        if ($request->answer_image === 'null') {
            if (isset($problem->answer_img)) {
                $this->deleteFile($problem->answer_img);
            };
            $problem->answer_img = null;
        }

        // 解答に変更がある場合は、パスを生成してS３へ
        if ($request->answer_image !== $problem->answer_img && $request->answer_image !== 'null') {
            $answer_img_path = $this->createFilePath($request->answer_image);
            $this->saveFileToS3($request->answer_image, $answer_img_path);
            $problem->answer_img = $answer_img_path;
        }

        $problem->save();
    }

    /**
     * user_idと問題数を追加する
     *
     * @param [object] $problems
     * @return object
     */
    public function addUserIdAndProblemsCount($problems)
    {
        $user_id = $problems->map(function ($problem) {
            return $problem->user_id;
        });

        $problems_count = $problems->count('id');
        $problems = Arr::add(['problems' => $problems], 'problems_count', $problems_count);
        $problems['user_id'] = $user_id[0];

        return $problems;
    }

    /**
     * 問題と解答の画像のURLを追加する
     *
     * @param [type] $problem
     * @return void
     */
    public function addImageUrl($problem)
    {
        $problem_img = $problem->problem_img;
        $answer_img = $problem->answer_img;

        if ($problem_img && !$answer_img) {
            $problem['problem_img_url'] = $this->awsUrlFetch($problem_img);
            return $problem;
        }
        if ($answer_img && !$problem_img) {
            $problem['answer_img_url'] = $this->awsUrlFetch($answer_img);
            return $problem;
        }
        if ($answer_img && $problem_img) {
            $problem['problem_img_url'] = $this->awsUrlFetch($problem_img);
            $problem['answer_img_url'] = $this->awsUrlFetch($answer_img);
            return $problem;
        }
        return $problem;
    }

    /**
     * 問題が存在しているかのチェック
     *
     * @param [type] $problem
     * @return void
     */
    public function checkExists($problem)
    {
        if (!isset($problem)) {
            return abort(404);
        } else {
            return;
        }
    }
}
