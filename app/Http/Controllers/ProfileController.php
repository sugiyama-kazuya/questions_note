<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
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
        if ($login_user_id === (int) $user_id) {
            $current_user = $this->user->getProfileRequiredData($user_id);
        } else {
            $current_user = $this->user->getProfileRequiredData($user_id);
            $current_user['is_followed_by'] = $current_user->isFollowedBy($login_user_id);
        }
        return response()->json(['user' => $current_user]);
    }

    public function edit($user_id)
    {
        $user = $this->user->find($user_id);

        if ($user->profile_img) {
            $file_path = $user->awsUrlFetch($user->profile_img);
            $user->profile_img = $file_path;
        }

        return $user;
    }

    /**
     * プロフィール編集
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

            // S3にファイルを保存する。第３引数の名前で第2引数を保存する
            Storage::cloud()->putFileAs('', $request->file, $file_path, 'public');

            DB::beginTransaction();

            try {
                $user->findOrFail(Auth::id())->fill([
                    'name' => $request->name,
                    'email' => $request->email,
                    'profile_img' => $file_path
                ])->save();
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollback();
                Storage::cloud()->delete($user->profile_img);
                throw $exception;
            }
        } else {
            $user->findOrFail(Auth::id())->fill([
                'name' => $request->name,
                'email' => $request->email,
            ])->save();
        }
        return http_response_code(200);
    }
}
