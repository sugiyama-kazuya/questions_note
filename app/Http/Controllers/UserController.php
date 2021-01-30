<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $current_user = $this->user->profileBaseData($user_id);
        $current_user = $this->user->getLoginUserOtherThanFollowPresence($current_user, $user_id);

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
        $current_user = $this->user->currentUser($user_id);
        if ($current_user->profile_img) {
            $file_path = $current_user->urlFetch($current_user->profile_img);
            $current_user["profile_img"] = $file_path;
        }

        return response()->json(['user' => $current_user]);
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
            $file = $request->file("file");
            $extension = $request->file->extension();
            $file_path = $user->getRandomId() . '.' . $extension;

            DB::beginTransaction();
            try {
                $this->user->saveFile($file, $file_path);
                $this->user->profileUpdate($request, $file_path);
                DB::commit();
            } catch (\Exception $exception) {
                Log::debug("エラーキャッチ！！");
                DB::rollback();
                if (Storage::disk('public')->exists($file_path)) {
                    $this->user->deleteFile($file_path);
                }
                return abort(500);
            }
        } else {
            $this->user->profileUpdate($request);
        }

        return http_response_code(200);
    }
}
