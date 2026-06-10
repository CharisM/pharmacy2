<?php

namespace App\Http\Controllers;

use App\Notifications\EmailVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth('web')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->hasFile('profile_picture')) {
            $upload = $request->file('profile_picture');
            $filename = uniqid('profile_').'.'.$upload->getClientOriginalExtension();
            $upload->move(public_path('profile-photos'), $filename);

            if ($user->profile_picture && file_exists(public_path('profile-photos/'.$user->profile_picture))) {
                @unlink(public_path('profile-photos/'.$user->profile_picture));
            }

            $user->profile_picture = $filename;
        }

        $emailChanged = $user->email !== $request->email;

        $user->name = $request->name;
        $user->email = $request->email;

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($emailChanged) {
            $code = (string) random_int(100000, 999999);

            $user->forceFill([
                'email_verification_code' => Hash::make($code),
                'email_verification_code_expires_at' => now()->addMinutes(10),
            ])->save();

            $request->session()->put('pending_verification_user_id', $user->id);
            $user->notify(new EmailVerificationCode($code));

            return redirect()->route('verification.notice')
                ->with('status', 'verification-code-sent');
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}
