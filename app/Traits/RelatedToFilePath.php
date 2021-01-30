<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait RelatedToFilePath
{
    private $id_length = 10;

    /**
     * ローカルストレージからプロフィール画像のurlを取得
     *
     * @param string $file_path
     * @return string
     */
    public function urlFetch(string $file_path = null): string
    {
        return $file_path ? Storage::url($file_path) : "";
    }

    /**
     * ローカルストレージのファイルを削除する
     *
     * @param [type] $file
     * @return void
     */
    public function deleteFile($file_path)
    {
        Storage::delete($file_path);
    }

    /**
     * ローカルストレージにファイルを保存する
     *
     * @param [type] $file
     * @param [type] $file_path
     * @return void
     */
    public function saveFile($file, $file_path)
    {
        $file->storeAs(
            'public',
            $file_path
        );
    }

    /**
     * ランダムなid値を作成
     *
     * @return integer
     */
    public function getRandomId(): string
    {
        $characters = array_merge(
            range(0, 9),
            range('a', 'z'),
            range('A', 'Z'),
            ['-', '_']
        );

        $length = count($characters);
        $id = "";

        for ($i = 0; $i < $this->id_length; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        };

        return $id;
    }

    /**
     * ランダムIDからファイルパスを作成
     *
     * @param [type] $file
     * @return void
     */
    public function createFilePath($file)
    {
        $extension = $file->extension();
        $file_path = $this->getRandomId() . '.' . $extension;
        return $file_path;
    }
}
