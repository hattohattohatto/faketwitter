<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * ホワイトリストのセット
     *
     * @var array
     */
    protected $fillable = [
        'screen_name',
        'name',
        'profile_image',
        'email',
        'password'
    ];


    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function followers()
    {
        return $this->belongsToMany(self::class, 'followers', 'followed_id', 'following_id');
    }

    /**
     * フォロー、フォロワーのリレーションを定義（多対他）
     *
     * @return \Illuminate\Http\Response
     */
    public function follows()
    {
        return $this->belongsToMany(self::class, 'followers', 'following_id', 'followed_id');
    }

    /**
     * 全ユーザーのID取得
     *
     * @param int $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getAllUsers(int $userId)
    {
        return $this->Where('id', '<>', $userId)->paginate(5);
    }

    /**
     * フォロー
     *
     * @param int $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function follow(int $userId)
    {
        return $this->follows()->attach($userId);
    }

    /**
     * フォロー解除
     *
     * @param int $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function unfollow(int $userId)
    {
        return $this->follows()->detach($userId);
    }

    /**
     * フォローしているかどうか
     *
     * @param int $userId
     * 
     * @return boolean
     */
    public function isFollowing(int $userId)
    {
        return (bool) $this->follows()->where('followed_id', $userId)->first(['id']);
    }

    /**
     * フォローされているかどうか
     *
     * @param int $userId
     * 
     * @return boolean
     */
    public function isFollowed(int $userId)
    {
        return (bool) $this->followers()->where('following_id', $userId)->first(['id']);
    }

    /**
     * プロフィール更新機能
     *
     * @param array $params
     * 
     * @return void
     */
    public function updateProfile(array $params): void
    {
        if (isset($params['profile_image'])) {
            $fileName = $params['profile_image']->store('public/profile_image/');

            $this::where('id', $this->id)
                ->update([
                    'screen_name'   => $params['screen_name'],
                    'name'          => $params['name'],
                    'profile_image' => basename($fileName),
                    'email'         => $params['email'],
                ]);
        } else {
            $this::where('id', $this->id)
                ->update([
                    'screen_name'   => $params['screen_name'],
                    'name'          => $params['name'],
                    'email'         => $params['email'],
                ]);
        }

        return;
    }
}
