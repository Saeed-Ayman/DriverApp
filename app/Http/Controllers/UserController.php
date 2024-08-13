<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function show()
    {
        return auth()->user();
    }

    public function updateAvatar(Request $request)
    {
        $user = auth()->user();

        $validator = \Validator::make($request->all(), [
            'avatar' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $validator->errors(),
            ], 422);
        }


        if (!\Str::contains($user->avatar, 'default')) {
            \Storage::disk('public')
                ->delete("avatars/".pathinfo($user->avatar, PATHINFO_BASENAME));
        }

        info("1 - update avatar", [$request->avatar]);

        $avatarPath = $request->file('avatar')->store('avatars', 'public');

        info("2 - new path", [$avatarPath]);

        $user->avatar = $avatarPath;
        $user->save();

        info("3 - full path", [$user->avatar]);

        return response()->json([
            "status" => "success",
            "avatar" => $user->avatar,
        ]);
    }

    public function updateInfo(Request $request)
    {
        $user = auth()->user();

        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['required', 'max:14', 'unique:users,phone,'.$user->id],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $validator->errors(),
            ], 422);
        }

        if (!\Hash::check($validator->getValue('password'), $user->password)) {
            return response()->json([
                "status" => "error",
                "message" => "Password is not correct!",
            ], 401);
        }

        $user->update($validator->validated());

        return response()->json([
            "status" => "success",
            "message" => "Update information successfully!",
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validator = \Validator::make($request->all(), [
            'old_password' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $validator->errors(),
            ], 422);
        }

        if (!\Hash::check($validator->getValue('old_password'), $user->password)) {
            return response()->json([
                "status" => "error",
                "message" => "Password is not correct!",
            ], 401);
        }

        $user->update($validator->validated());

        return response()->json([
            "status" => "success",
            "message" => "Update password successfully!",
        ]);
    }

    public function destroy(Request $request)
    {
        $user = auth()->user();

        $validator = \Validator::make($request->all(), [
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $validator->errors(),
            ], 422);
        }

        if (!\Hash::check($validator->getValue('password'), $user->password)) {
            return response()->json([
                "status" => "error",
                "message" => "Password is not correct!",
            ], 401);
        }


        if (!$user->delete()) {
            return response()->json([
                "status" => "error",
                "message" => "Something wrong please contact with developer!",
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Delete account success!",
        ]);
    }
}
