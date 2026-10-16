<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;

class AttendanceService
{
    public function getattendanceByIdAndUserId($id, $user_id)
    {
        return AttendanceRecord::where('id', $id)
            ->where('user_id', $user_id)
            ->first();
    }

    public function getTodayAttendance($user_id)
    {
        $today = CarbonImmutable::today();
        return AttendanceRecord::where('user_id', $user_id)
            ->where(function ($query) use ($today) {
                $query->whereNull('clockout_time')
                    ->orWhere(function ($q) use ($today) {
                        $q->whereDate('clockin_time', $today)
                            ->whereDate('clockout_time', $today);
                    });
            })
            ->latest()
            ->first();
    }

    public function getAttendancesByMonth($user_id, $year, $month)
    {
        return AttendanceRecord::where('user_id', $user_id)
            ->whereYear('clockin_time', $year)->whereMonth('clockin_time', $month)
            ->orderBy('clockin_time', 'ASC')
            ->get();
    }

    public function registerAttendance()
    {
        $attendance = self::getClockoutNullAttendance();
        if($attendance) {
            $attendance->clockout_time = CarbonImmutable::now();
            $attendance->save();
        } else {
            AttendanceRecord::create([
                'user_id' => Auth::id(),
                'clockin_time' => CarbonImmutable::now(),
            ]);
        }
    }

    public function update(AttendanceRecord $attendance, array $validated)
    {
        $attendance->update($validated);
    }

    private static function getClockoutNullAttendance()
    {
        return AttendanceRecord::where('user_id', Auth::id())
            ->whereNull('clockout_time')
            ->first();
    }
}
