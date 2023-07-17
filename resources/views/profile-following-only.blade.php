<div class="list-group">
    @foreach ($following as $follow)
        <a href="{{ route('profile-following', $follow->userFollowers->username) }}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{ $follow->userFollowers->avatar }}" />
            {{$follow->userFollowers->username}}
        </a>
    @endforeach
</div>