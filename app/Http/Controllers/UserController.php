<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * プロフィール画面の情報の取得
     *
     * @param User $user
     * @param [string] $user_id
     * @return object
     */
    public function show($user_id): object
    {
        $login_user_id = Auth::id();
        $user_id = (int) $user_id;

        // ユーザーがログインしていない場合
        if ($login_user_id === null) {
            $current_user = $this->user->getProfileRequiredData($user_id);
            return response()->json(['user' => $current_user]);
        }

        // ログインユーザー自身のページの場合、それ以外のページの場合
        if ($login_user_id === $user_id) {
            $current_user = $this->user->getProfileRequiredData($user_id);
        } else {
            $current_user = $this->user->getProfileRequiredData($user_id);
            $current_user['is_followed_by'] = $current_user->isFollowedBy($login_user_id);
        }
        return response()->json(['user' => $current_user]);
    }

    /**
     * 編集画面
     *
     * @param [string] $user_id
     * @return void
     */
    public function edit($user_id)
    {
        $user = $this->user->find($user_id);

        if ($user->profile_img) {
            $user->profile_img = $user->awsUrlFetch($user->profile_img);
        }

        return response()->json(['user' => $user]);
    }

    /**
     * プロフィール編集の更新
     *
     * @param ProfileRequest $request
     * @param User $user
     * @return User $user
     */
    public function update(ProfileRequest $request, User $user)
    {
        if ($request->file) {
            $extension = $request->file->extension();
            $file_path = $user->getRandomId() . '.' . $extension;

            DB::beginTransaction();
            try {
                $this->user->saveFileToS3($request->file, $file_path);
                $this->user->profileUpdate($request, $file_path);
                DB::commit();
            } catch (\Exception $exception) {
                Log::debug($exception->getMessage());
                DB::rollback();
                Storage::cloud()->delete($this->user->profile_img);
                return abort(500);
            }
        } else {
            $this->user->profileUpdate($request);
        }

        return http_response_code(200);
    }
}
