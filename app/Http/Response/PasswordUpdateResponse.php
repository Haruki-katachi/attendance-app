<?php
namespace App\Http\Response;

use Laravel\Fortify\Contracts\PasswordUpdateResponse as ContractsPasswordUpdateResponse;
use Override;

class PasswordUpdateResponse implements ContractsPasswordUpdateResponse
{
    #[Override]
    public function toResponse($request)
    {
        return redirect()->route('attendance.index')->with('success', 'パスワードを変更しました');
    }
}
