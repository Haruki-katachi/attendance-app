@extends('layouts.app')

@section('title', '記録登録')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6 my-2">
            <div class="card">
                <div class="card-header">記録登録</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <p class="card-text">出勤時間：{{ $attendance?->clockin_time?->format('H:i') ?? '-' }} 退勤時間：{{ $attendance?->clockout_time?->format('H:i') ?? '-' }}</p>
                    <form action="{{ route('attendance.store') }}" method="POST">
                        @csrf
                        @if (is_null($attendance))
                            <input type="submit" class="btn btn-primary" value="出勤" />
                        @elseif (is_null($attendance->clockout_time))
                            <input type="submit" class="btn btn-danger" value="退勤" />
                        @else
                            <!-- 何も表示しない  -->
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
