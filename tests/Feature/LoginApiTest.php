<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Models\User;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // テストユーザー作成
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function test_should_登録済みのユーザーを認証して返却する()
    {
        // 作成したテストユーザーの認証のリクエスト
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        // 成功のステータスが返り、ユーザー名が取得できるか
        $response
            ->assertStatus(200)
            ->assertJson(['name' => $this->user->name]);

        // リクエストしたユーザーが認証されていることを確認
        $this->assertAuthenticatedAs($this->user);
    }
}
