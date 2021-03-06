@extends('layouts.app')

@extends('layouts.link')

@extends('layouts.searchUser')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="d-inline-flex">
                    <div class="p-3 d-flex flex-column">
                        <img src="{{ asset('storage/profile_image/' .$user->profile_image) }}" class="rounded-circle" width="100" height="100">
                        <div class="mt-3 d-flex flex-column">
                            <h4 class="mb-0 font-weight-bold">{{ $user->name }}</h4>
                            <span class="text-secondary">{{ $user->screen_name }}</span>
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column justify-content-between">
                        <div class="d-flex">
                            <div>
                                @if ($user->id === Auth::user()->id)
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">プロフィールを編集する</a>
                                @else
                                    @if ($isFollowing)
                                        <span class="follow">
                                            @csrf

                                            <button type="submit" class="btn btn-danger follow-toggle follow{{ $user->id }}" data-follow-review-id="{{ $user->id }}" id="followBtn{{ $user->id }}">フォロー解除</button>
                                        </span>
                                    @else
                                        <span class="follow">
                                            @csrf

                                            <button type="submit" class="btn btn-primary follow-toggle follow{{ $user->id }}" data-follow-review-id="{{ $user->id }}" id="followBtn{{ $user->id }}">フォローする</button>
                                        </span>
                                    @endif

                                    @if ($isFollowed)
                                        <span class="mt-2 px-1 bg-secondary text-light">フォローされています</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="p-2 d-flex flex-column align-items-center">
                                <p class="font-weight-bold">ツイート数</p>
                                <span>{{ $tweetCount }}</span>
                            </div>
                            <div class="p-2 d-flex flex-column align-items-center">
                                <p class="font-weight-bold">フォロー数</p>
                                <span>{{ $followCount }}</span>
                            </div>
                            <div class="p-2 d-flex flex-column align-items-center">
                                <p class="font-weight-bold">フォロワー数</p>
                                <span id="follower-count">{{ $followerCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($timelines))
            @foreach ($timelines as $timeline)
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-haeder p-3 w-100 d-flex">
                            <img src="{{ asset('storage/profile_image/' .$user->profile_image) }}" class="rounded-circle" width="50" height="50">
                            <div class="ml-2 d-flex flex-column flex-grow-1">
                                <p class="mb-0">{{ $timeline->user->name }}</p>
                                <a href="{{ route('users.show', $timeline->user->id) }}" class="text-secondary">{{ $timeline->user->screen_name }}</a>
                            </div>
                            <div class="d-flex justify-content-end flex-grow-1">
                                <p class="mb-0 text-secondary">{{ $timeline->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $timeline->text }}
                        </div>
                        <div class="card-footer py-1 d-flex justify-content-end bg-white">
                            @if ($timeline->user->id === Auth::user()->id)
                                <div class="dropdown mr-3 d-flex align-items-center">
                                    <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-fw"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <form method="POST" action="{{ route('tweets.destroy', $timeline->id) }}" class="mb-0">
                                            @csrf
                                            @method('DELETE')

                                            <a href="{{ route('tweets.edit', $timeline->id) }}" class="dropdown-item">編集</a>
                                            <button type="submit" class="dropdown-item del-btn">削除</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                            <div class="mr-3 d-flex align-items-center">
                                <a href="{{ route('retweet', $timeline->id) }}"><i class="fa fa-retweet fa-fw" aria-hidden="true"></i></a>
                            </div>
                            <div class="mr-3 d-flex align-items-center">
                                <a href="{{ route('tweets.show', $timeline->id) }}"><i class="far fa-comment fa-fw"></i></a>
                                <p class="mb-0 text-secondary">{{ count($timeline->comments) }}</p>
                            </div>
                            <div class="d-flex align-items-center">
                                @if (!in_array(Auth::user()->id, array_column($timeline->favorites->toArray(), 'user_id'), TRUE))
                                    <span class="fav">
                                        @csrf

                                        <input type="hidden" name="tweet_id" value="{{ $timeline->id }}">
                                        <button type="submit" class="btn p-0 border-0 text-primary fav-toggle favColor{{ $timeline->id }}" data-review-id="{{ $timeline->id }}"><i class="far fa-heart fa-fw favIcon{{ $timeline->id }}"></i></button> 
                                        <span class="mb-0 text-secondary" id = "favCounted{{ $timeline->id }}">{{count($timeline->favorites)}}</span>
                                    </span>  
                                @else
                                    <span class="fav">
                                        @csrf

                                        <input type="hidden" name="tweet_id" value="{{ $timeline->id }}">
                                        <button type="submit" class="btn p-0 border-0 text-danger fav-toggle favColor{{ $timeline->id }} favColor{{ $timeline->id }}" data-review-id="{{ $timeline->id }}"><i class="fas fa-heart fa-fw favIcon{{ $timeline->id }}"></i></button> 
                                        <span class="mb-0 text-secondary" id = "favCounted{{ $timeline->id }}">{{count($timeline->favorites)}}</span>
                                    </span>  
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $timelines->links() }}
    </div>
</div>
@endsection
<script src ="{{ asset('/js/followShow.js/') }}" defer></script>
<script src ="{{ asset('/js/favorite.js/') }}" defer></script>
