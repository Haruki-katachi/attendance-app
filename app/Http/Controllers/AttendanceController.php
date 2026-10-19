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
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * 勤怠記録コントローラー
 */
class AttendanceController extends Controller
{
    /**
     * その日のログインユーザーの記録でattendance.indexのビューを返す
     *
     * @param AttendanceService $service
     * @return View attendance.index
     */
    public function index(AttendanceService $service): View
    {
        $attendance = $service->getTodayAttendance(Auth::id());

        return view('attendance.index', compact('attendance'));
    }

    /**
     * 登録処理を行いリダイレクトする
     *
     * @param AttendanceService $service
     * @return RedirectResponse ルートのattendance.index
     */
    public function store(AttendanceService $service): RedirectResponse
    {
        $service->registerAttendance(Auth::user());

        return redirect()->route('attendance.index');
    }

    /**
     * ログインユーザーのリクエストされた月の記録でattendance.monthを返す
     *
     * @param AttendanceService $service
     * @param Request $request
     * @return View attendance.month
     */
    public function month(AttendanceService $service, Request $request): View
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

    /**
     * 指定したユーザーのリクエストされた月の月別記録のビューを返す
     *
     * @param AttendanceService $service
     * @param Request $request
     * @param User $user
     * @return View|RedirectResponse attendance.monthのビューまたはルートuser.index
     */
    public function adminMonth(AttendanceService $service, Request $request, User $user): View|RedirectResponse
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

    /**
     * 指定した記録の編集画面のビューを返す
     *
     * @param int $user_id
     * @param AttendanceRecord $attendance
     * @return View attendance.edit
     */
    public function edit($user_id, AttendanceRecord $attendance): View
    {
        return view('attendance.edit', compact('user_id', 'attendance'));
    }

    /**
     * 更新処理を行いルートattendance.admin_monthにリダイレクト
     *
     * @param EditAttendanceRequest $request
     * @param int $user_id
     * @param AttendanceRecord $attendance
     * @param AttendanceService $attendanceService
     * @param UserService $userService
     * @return RedirectResponse
     */
    public function update(EditAttendanceRequest $request, $user_id, AttendanceRecord $attendance, AttendanceService $attendanceService, UserService $userService): RedirectResponse
    {
        $attendanceService->update($attendance, $request->validated());

        $user = $userService->findById($user_id);

        return redirect()->route('attendance.admin_month', compact('user'))->with('success', '更新しました。');
    }
}
