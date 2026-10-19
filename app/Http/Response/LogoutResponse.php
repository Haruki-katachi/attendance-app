<?php

namespace App\Http\Response;

use Laravel\Fortify\Contracts\LogoutResponse as ContractsLogoutResponse;
use Override;

/**
 * ログアウト処理の後のクラス
 */
class LogoutResponse implements ContractsLogoutResponse
{
    #[Override]
    public function toResponse($request)
    {
        return redirect('/login')->with('success', 'ログアウトしました');
    }
}
