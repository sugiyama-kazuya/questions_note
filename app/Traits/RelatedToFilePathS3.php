<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait RelatedToFilePathS3
{
    private $id_length = 10;

    /**
     * s3からプロフィール画像のurlを取得
     *
     * @param string $file_path
     * @return string
     */
    public function awsUrlFetch(string $file_path = null): string
    {
        return $file_path ? Storage::cloud()->url($file_path) : "";
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

    /**
     * S3にファイルを保存する
     *
     * @param [type] $file
     * @param [type] $file_path
     * @return void
     */
    public function saveFileToS3($file, $file_path)
    {
        // 第３引数の名前で第2引数を保存する
        Storage::cloud()->putFileAs('', $file, $file_path, 'public');
    }

    /**
     * S３のファイルを削除する
     *
     * @param [type] $file
     * @return void
     */
    public function deleteFile($file)
    {
        Storage::disk('s3')->delete([$file]);
    }
}
