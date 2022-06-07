<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;



class Tweet extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text'
    ];


    /**
     * ユーザーのリレーションを定義（他対一）
     * 
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * いいねのリレーションを定義（一対多）
     * 
     * @return \Illuminate\Http\Response
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * いいねのリレーションを定義（一対多）
     * 
     * @return \Illuminate\Http\Response
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * タイムラインを取得
     * 
     * @param int $userid
     * 
     * @return \Illuminate\Http\Response
     */
    public function getUserTimeLine(int $userId)
    {
        return $this->where('user_id', $userId)->orderBy('created_at', 'DESC')->paginate(50);
    }

    public function getTweetCount(Int $userId)
    {
        return $this->where('user_id', $userId)->count();
    }

    /**
     * タイムラインを取得
     * 
     * @param int $userid
     * 
     * @return \Illuminate\Http\Response
     */
    public function getTimeLines(int $userId, array $followIds)
    {
        $followIds[] = $userId;
        return $this->whereIn('user_id', $followIds)->orderBy('created_at', 'DESC')->paginate(50);
    }

    /**
     * ツイートのID取得
     *
     * @param int $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getTweet(int $tweetId)
    {
        return $this->with('user')->where('id', $tweetId)->first();
    }

    /**
     * ツイートの保存
     *
     * @param int $userId
     * @param array $data
     * 
     * @return void
     */
    public function tweetStore(int $userId, array $data): void
    {
        $this->userId = $userId;
        $this->text = $data['text'];
        $this->save();

        return;
    }

    /**
     * ツイート編集機能
     *
     * @param int $userId
     * @param int $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getEditTweet(int $userId, int $tweetId)
    {
        return $this->where('user_id', $userId)->where('id', $tweetId)->first();
    }

    /**
     * ツイート更新機能
     *
     * @param integer $tweetId
     * @param array $data
     * 
     * @return void
     */
    public function tweetUpdate(int $tweetId, array $data): void
    {
        $this->id = $tweetId;
        $this->text = $data['text'];
        $this->update();

        return;
    }

    /**
     * ツイート削除
     *
     * @param int $userId
     * @param int $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function tweetDestroy(int $userId, int $tweetId)
    {
        return $this->where('user_id', $userId)->where('id', $tweetId)->delete();
    }
}
