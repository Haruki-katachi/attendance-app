<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * 勤怠記録サービス
 */
class AttendanceService
{
    /**
     * 指定した勤怠記録を返す
     *
     * @param int $id
     * @param int $user_id
     * @return AttendanceRecord 勤怠記録
     */
    public function getAttendanceByIdAndUserId($id, $user_id): AttendanceRecord
    {
        return AttendanceRecord::where('id', $id)
            ->where('user_id', $user_id)
            ->first();
    }

    /**
     * 指定したユーザーの今日の日付の記録を取得して返す
     * 存在しなければnullを返す
     *
     * @param int $user_id
     * @return AttendanceRecord
     */
    public function getTodayAttendance($user_id): AttendanceRecord|null
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

    /**
     * 指定したユーザーの指定した年月の記録のコレクションを返す
     *
     * @param int $user_id
     * @param int $year
     * @param int $month
     * @return Collection 勤怠記録のコレクション
     */
    public function getAttendancesByMonth($user_id, $year, $month): Collection
    {
        return AttendanceRecord::where('user_id', $user_id)
            ->whereYear('clockin_time', $year)->whereMonth('clockin_time', $month)
            ->orderBy('clockin_time', 'ASC')
            ->get();
    }

    /**
     * 記録を作成するかnullのclockout_timeを記録する形で更新する
     *
     * @return void
     */
    public function registerAttendance($user_id): void
    {
        $attendance = self::getClockoutNullAttendance($user_id);
        if($attendance) {
            $attendance->clockout_time = CarbonImmutable::now();
            $attendance->save();
        } else {
            AttendanceRecord::create([
                'user_id' => $user_id,
                'clockin_time' => CarbonImmutable::now(),
            ]);
        }
    }

    /**
     * 記録を更新する
     *
     * @param AttendanceRecord $attendance
     * @param array $validated
     * @return void
     */
    public function update(AttendanceRecord $attendance, array $validated)
    {
        $attendance->update($validated);
    }

    /**
     * clockout_timeがnullの記録を取得する
     * なければnullを返す
     *
     * @return AttendanceRecord|null 勤怠記録かnull
     */
    private static function getClockoutNullAttendance($user_id): AttendanceRecord|null
    {
        return AttendanceRecord::where('user_id', $user_id)
            ->whereNull('clockout_time')
            ->first();
    }
}
