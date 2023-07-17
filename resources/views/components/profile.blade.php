<x-layout :doctitle="$doctitle">
    <div class="container py-md-5 container--narrow">
        <h2>
            <img class="avatar-small" src="{{ $sharedData['avatar']}}" />
            {{ $sharedData['username'] }}
            @auth
                @if (!$sharedData['currentlyFollowing'] and auth()->user()->username != $sharedData['username'])
                    <form class="ml-2 d-inline" action="{{ route('create-follow', $sharedData['username']) }}" method="POST">
                        @csrf
                        <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
                    </form>
                @endif

                @if ($sharedData['currentlyFollowing'])
                    <form class="ml-2 d-inline" action="{{ route('remove-follow', $sharedData['username']) }}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm">Unfollow <i class="fas fa-user-times"></i></button>
                    </form>
                @endif
                @if (auth()->user()->username == $sharedData['username'])
                    <a href="{{ route('manage-avatar') }}" class="btn btn-secondary btn-sm">Manage Avatar</a>
                @endif
            @endauth
        </h2>

        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a href="{{ route('profile', $sharedData['username']) }}"
                class="profile-nav-link nav-item nav-link {{ Request::segment(3) == '' ? 'active' : '' }}">Posts:
                {{ $sharedData['postCount'] }}</a>
            <a href="{{ route('profile-followers', $sharedData['username']) }}"
                class="profile-nav-link nav-item nav-link {{ Request::segment(3) == 'followers' ? 'active' : '' }}">Followers:
                {{$sharedData['followerCount']}}</a>
            <a href="{{ route('profile-following', $sharedData['username']) }}"
                class="profile-nav-link nav-item nav-link {{ Request::segment(3) == 'following' ? 'active' : '' }}">Following:
                {{$sharedData['followingCount']}}</a>
        </div>

        <div class="profile-slot-content">
            {{ $slot }}
        </div>


    </div>
</x-layout>
