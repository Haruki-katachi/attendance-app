<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        @auth
            <a href="{{ route('attendance.index') }}" class="navbar-brand">{{ config('app.name', 'Laravel') }}</a>
        @else
            <span class="navbar-brand">{{ config('app.name', 'Laravel') }}</span>
        @endauth
        @auth
            <button class="navbar-toggler" type="button" data-bs-toggler="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('attendance.index') }}" class="nav-link">ホーム</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('attendance.month') }}" class="nav-link">月別記録</a>
                    </li>
                    @if(Auth::user()->is_admin)
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}" class="nav-link">従業員一覧</a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}さん
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('user.password') }}">パスワード変更</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">ログアウト</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</nav>
