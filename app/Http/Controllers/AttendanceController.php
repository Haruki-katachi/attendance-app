<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Services\AttendanceService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonImmutable;
use App\Models\User;

class AttendanceController extends Controller
{
    public function index(AttendanceService $service)
    {
        $attendance = $service->getTodayAttendance(Auth::id());

        return view('attendance.index', compact('attendance'));
    }

    public function store(AttendanceService $service)
    {
        $service->registerAttendance();

        return redirect()->route('attendance.index');
    }

    public function month(AttendanceService $service, Request $request)
    {
        $month_input = CarbonImmutable::parse($request->input('month_input'));

        if (!$month_input) {
            $today = CarbonImmutable::today();
            $month_input = CarbonImmutable::parse($today->format('Y-m'));
        }

        $user = Auth::user();

        $attendances = $service->getAttendancesByMonth($user->id, $month_input->year, $month_input->month);
        $month_input = $month_input->format('Y-m');

        return view('attendance.month', compact('attendances', 'month_input'));
    }

    public function adminMonth(AttendanceService $service, Request $request, User $user)
    {
        if (!$user) {
            return redirect()->route('user.index');
        }
        $month_input = CarbonImmutable::parse($request->input('month_input'));
        if (!$month_input) {
            $today = CarbonImmutable::today();
            $month_input = CarbonImmutable::parse($today->format('Y-m'));
        }
        $attendances = $service->getAttendancesByMonth($user->id, $month_input->year, $month_input->month);
        $month_input = $month_input->format('Y-m');

        $user_id = $user->id;

        return view('attendance.month', compact('attendances', 'month_input', 'user_id'));
    }

    public function edit($user_id, AttendanceRecord $attendance)
    {
        return view('attendance.edit', compact('user_id', 'attendance'));
    }

    public function update(EditAttendanceRequest $request, $user_id, AttendanceRecord $attendance, AttendanceService $attendanceService, UserService $userService)
    {
        $attendanceService->update($attendance, $request->validated());

        $user = $userService->findById($user_id);

        return redirect()->route('attendance.admin_month', compact('user'))->with('success', '更新しました。');
    }
}
