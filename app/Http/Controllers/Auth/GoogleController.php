<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('auth.login')
                ->with('thatbai', 'Đăng nhập Google thất bại, vui lòng thử lại.');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            $user = User::create([
                'ten_nguoi_dung' => $googleUser->getName(),
                'email'          => $googleUser->getEmail(),
                'sdt'            => null,
                'Ten_dang_nhap'  => $googleUser->getEmail(),
                'password'       => bcrypt(uniqid()),
                'id_phan_quyen'  => 2,
                'google_id'      => $googleUser->getId(),
                'avatar'         => $googleUser->getAvatar(),
            ]);
        } else {
            $user->google_id = $user->google_id ?: $googleUser->getId();
            $user->avatar    = $googleUser->getAvatar();
            $user->save();
        }

        Auth::login($user, true);

        Session::put('DangNhap', $user->id);

        if ((string)$user->id_phan_quyen === '1') {
            Session::put('check', '1');
        } else {
            Session::put('check', '2');
        }

        Session::regenerate();

        if ((string)$user->id_phan_quyen === '1') {
            return redirect('/admin')->with('success', 'Đăng nhập Google thành công');
        }

        return redirect('/trang-chu')->with('success', 'Đăng nhập Google thành công');
    }
}
