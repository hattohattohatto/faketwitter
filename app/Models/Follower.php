<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    /**
     * プライマリキーのセット
     * 
     * @var array
     */
    protected $primaryKey = [
        'following_id',
        'followed_id'
    ];
    /**
     * ホワイトリストのセット
     * 
     * @var array
     */
    protected $fillable = [
        'following_id',
        'followed_id'
    ];

    /**
     * タイムスタンプ更新の無効化
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * IDの自動増分の無効化
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * フォローしている人の人数のカウント
     * 
     * @param  int $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollowCount(int $userId)
    {
        return $this->where('following_id', $userId)->count();
    }

    /**
     * フォロワーの人数のカウント
     * 
     * @param int userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollowerCount(int $userId)
    {
        return $this->where('followed_id', $userId)->count();
    }

    /**
     * フォローしている人のID取得
     * 
     * @param int $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function followingIds(int $userId)
    {
        return $this->where('following_id', $userId)->get('followed_id');
    }
}
