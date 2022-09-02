<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Article;
use App\User;

class ArticleTest extends TestCase
{
     use RefreshDatabase;

     //投稿にいいねがされていない時にfalseが返ってくるかのチェック
     public function testIsLikedByNull()
     {
        //Articleモデルを作成する（準備）
        $article = factory(Article::class)->create();
        //Articleモデルが代入された変数$articleよりisLikedByメソッドを使用してnullを引数に取る
        $result = $article->isLikedBy(null);
        //引数がfalseになるかのテスト
        $this->assertFalse($result);
     }

     //投稿にいいねがされていればtrueを返すかのチェック
     public function testIsLikedByTheUser()
     {
        //Article, Userモデルをそれぞれ作成
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();

        //記事にいいねをする
        //likes関数でまず　Likesテーブルを中間にしたArticleテーブルとUserテーブルの多対多のリレーションが返ってくる。
        //次に、Attach関数にて、ユーザーの情報をlikesテーブルにいいねしたユーザーの情報が登録される。
        //これはつまり、「ファクトリで生成した$user」が、「ファクトリで生成された$article』をいいねしている状態ということになる。
        $article->likes()->attach($user);

        //$articleをいいねした$userを引数に、論理型を返す
        $result = $article->isLikedBy($user);

        //返ってきた値がtrueであるかをチェック
        $this->assertTrue($result);
     }

     public function testIsLikedByAnother()
     {
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();
        $another = factory(User::class)->create();
        //自分ではない他人がいいねした状態
        $article->likes()->attach($another);
        //自分がいいねしたかの判定
        $result = $article->isLikedBy($user);
        //その内容がfalseかどうか
        //(この場合、自分ではない他人がいいねをしていて、自分はいいねをしていないので、falseが返るはず)
        $this->assertFalse($result);
     }
}
