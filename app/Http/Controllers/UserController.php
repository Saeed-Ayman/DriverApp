<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
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
            'avatar' => 'image|mimes:jpg,jpeg,bmp,svg,png|max:8192',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $validator->errors(),
            ], 422);
        }

        $image = $user->image();

        if ($validator->getValue('avatar')) {
            $options = [
                "format" => "png",
                "folder" => "avatars"
            ];

            $cloudinary = \Cloudinary::uploadFile($request->file('avatar')->getRealPath(), $options);

            $image->updateOrCreate([
                'image_id' => $cloudinary->getPublicId(),
                'image_url' => $cloudinary->getPath(),
            ]);
        } else {
            $image->delete();
        }

        return response()->json([
            "status" => "success",
            "avatar" => $user->avatar,
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

        if ($user->image_id !== User::DEFAULT_AVATAR) {
            Image::destroy($user->image_id);
        }

        return response()->json([
            "status" => "success",
            "message" => "Delete account success!",
        ]);
    }

    public function updateInfo(
        Request $request
    ) {
        $user = auth()->user();

        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['required', 'max:14', 'unique:users,phone,'.$user->id],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $validator->errors(),
            ], 422);
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
}
