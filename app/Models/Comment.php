<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * ホワイトリストのセット
     *
     * @var array
     */
    protected $fillable = [
        'text'
    ];

    /**
     * ユーザーのリレーションを定義
     * 
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * ツイートのIDを取得
     * 
     * @return \Illuminate\Http\Response
     */
    public function getComments(int $tweet_id)
    {
        return $this->with('user')->where('tweet_id', $tweet_id)->get();
    }
}
