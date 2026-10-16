@extends('layouts.app')

@section('title', '月別記録')

@section('content')

<div>
    <h3>月別記録</h3>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ url()->current() }}" method="GET">
        <input type="month" name="month_input" class="form-control" value="{{ $month_input }}">
        <input type="submit" class="btn btn-outline-primary" value="決定">
    </form>
    <hr>
    <table class="table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤時間</th>
                <th>退勤時間</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->clockin_time->format('Y/m/d') }}</td>
                    <td>{{ $attendance->clockin_time->format('H:i') }}</td>
                    <td>{{ $attendance->clockout_time?->format('H:i') ?? '-' }}</td>
                    @if (auth()->check() && auth()->user()->is_admin && isset($user_id))
                        <td><a href="{{ route('attendance.edit', [$user_id, $attendance]) }}" class="btn btn-primary">編集</a>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
