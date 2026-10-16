@extends('layouts.app')

@section('title', '記録編集')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6 my-2">
        <div class="card">
            <div class="card-header">記録編集</div>
            <div class="card-body">
                <form action="{{ route('attendance.update', [$user_id, $attendance]) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="clockin_time" class="form-label">出勤時間</label>
                        <input type="datetime-local" name="clockin_time" id="clockin_time" class="form-control @error('clockin_time') is-invalid @enderror" value="{{ old('clockin_time', $attendance->clockin_time->format('Y-m-d\TH:i')) }}">
                        @error('clockin_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="clockout_time" class="form-label">退勤時間</label>
                        <input type="datetime-local" name="clockout_time" id="clockout_time" class="form-control @error('clockout_time') is-invalid @enderror" value="{{ old('clockout_time', $attendance->clockout_time->format('Y-m-d\TH:i')) }}">
                        @error('clockout_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <input type="submit" class="btn btn-primary" value="編集">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">キャンセル</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
