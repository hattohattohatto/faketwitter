<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tweet;
use App\Models\Follower;


class UsersController extends Controller
{
    /**
     * ミドルウェアでのバリデーション
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('profilecheck')->only('update');
    }
    /**
     * ツイートのリストを表示
     * 
     * @param  User $User
     * 
     * @return \Illuminate\View\View
     */
    public function index(User $user)
    {
        $all_users = $user->getAllUsers(auth()->user()->id);

        return view('users.index', [
            'all_users'  => $all_users
        ]);
    }

    /**
     * ツイート表示
     *
     * @param  User $User
     * @param  Tweet $Tweet
     * @param  Follower $Follower
     * 
     * @return \Illuminate\View\View
     */
    public function show(User $user, Tweet $tweet, Follower $follower)
    {
        $isFollowing = $user->isFollowing($user->id);
        $isFollowed = $user->isFollowed($user->id);
        $timelines = $tweet->getUserTimeLine($user->id);
        $tweetCount = $tweet->getTweetCount($user->id);
        $followCount = $follower->getFollowCount($user->id);
        $followerCount = $follower->getFollowerCount($user->id);

        return view('users.show', [
            'user'           => $user,
            'is_following'   => $isFollowing,
            'is_followed'    => $isFollowed,
            'timelines'      => $timelines,
            'tweet_count'    => $tweetCount,
            'follow_count'   => $followCount,
            'follower_count' => $followerCount
        ]);
    }

    /**
     * ツイート作成
     *
     * @param  User $user
     * 
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }


    /**
     * ツイート編集
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $user->updateProfile($data);

        return redirect('users/' . $user->id);
    }

    /**
     * フォロー
     *
     * @param \Illuminate\Http\Request  $request
     * @param User $user
     * 
     * @return \Illuminate\Foundation\helpers
     */
    public function follow(Request $request, User $user)
    {
        $follower = $user->where('id', $request->loginUserId);
        $isFollowing = $follower->isFollowing($request->id);
        if (!$isFollowing) {
            $follower->follow($request->id);
            return back();
        }
    }

    /**
     * フォロー解除
     *
     * @param  \Illuminate\Http\Request  $request
     * @param User $user
     * 
     * @return \Illuminate\Foundation\helpers
     */
    public function unfollow(Request  $request, User $user)
    {
        $follower = $user->where('id', $request->loginUserId);
        $isFollowing = $follower->isFollowing($request->id);
        if ($isFollowing) {
            $follower->unfollow($request->id);
            return back();
        }
    }
}
