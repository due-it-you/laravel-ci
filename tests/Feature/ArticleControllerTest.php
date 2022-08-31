<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class ArticleControllerTest extends TestCase
{   
    //DBのリセット
    use RefreshDatabase;

    public function testIndex() {
        //getメソッドにてTestResponseクラスを受け取る。
        $response = $this->get(route('articles.index'));

        //ステータスコードのテスト
        $response->assertStatus(200)
        //$responseで使用されているビューがちゃんと使用されているかのテスト
            ->assertViewIs('articles.index');
    }

    public function testGuestCreate() {
        //記事投稿画面のURLを取得
        $response = $this->get(route('articles.create'));
        //引数として渡したURLにリダイレクトされたかどうかをテスト
        $response->assertRedirect(route('login'));
    }

    public function testAuthCreate() 
    {
        // テストに必要なUserモデルを「準備」
        $user = factory(User::class)->create();
        //ユーザーログインをした後に、記事投稿画面にアクセスしているという状態
        // ログインして記事投稿画面にアクセスすることを「実行」
        $response = $this->actingAs($user)
            ->get(route('articles.create'));
        //ステータス状況と引数のビューが使用されているかのチェック
        // レスポンスを「検証」
        $response->assertStatus(200)
            ->assertViewIs('articles.create');
    }
}
