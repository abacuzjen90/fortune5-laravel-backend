<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['login'])
        ];

    }

    public function index()
    {
        return User::all();
    }

    public function register(Request $request)
    {
        $field = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'branch'   => 'required',
            'role'     => 'required',
            'image'    => 'nullable|string',
        ]);

        $user = User::create([
            'name'     => $field['name'],
            'email'    => $field['email'],
            'password' => Hash::make($field['password']),
            'branch'   => $field['branch'],
            'role'     => $field['role'],
            'image'    => $field['image'] ?? null,
        ]);

        return response()->json([
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // note: import Hash word
        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'errors' => [
                    'email' => ['The provided credentials are incorrect']
                ]
            ];
        }

        $token = $user->createToken($user->name);

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
        ];

    }

    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return [
            'message' => 'You are logged out!'

        ];
    }

    public function show($id)
    {
        $data = \DB::table('users')->where('id', '=', $id)->get(['id','name','email','branch','role', 'image'] );
        return $data;
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|max:255',
            'email'  => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|confirmed',
            'branch'   => 'required',
            'role'     => 'required',
            'image'    => 'nullable|string',
        ]);

        $oldImage = $user->image;

        $user->name = $request->name;
        $user->branch = $request->branch;
        $user->role = $request->role;

        if ($user->email !== $request->email) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('image')) {
            $newImage = $request->image;

            // only update if changed
            if ($newImage !== $oldImage) {
                $user->image = $newImage;

                if (!empty($oldImage)) {
                    try {
                        Storage::disk('s3')->delete($oldImage);
                    } catch (\Exception $e) {
                        // ignore delete errors
                    }
                }
            }
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user,
            'url' => $user->image
                ? env('AWS_URL') . '/' . $user->image
                : null,
        ]);
    }


    public function destroy($id)
    {
        \DB::table('users')->where('id', '=', $id)->delete();
        return ['message' => 'Account deleted!'];
    }


    public function uploadImageUser(Request $request)
    {
        try {
            $request->validate([
                'file_name' => 'required|string',
                'file_type' => 'required|string',
            ]);

            // Allow only images
            if (!str_starts_with($request->file_type, 'image/')) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Only image files allowed'
                ], 400);
            }

            $fileName = time() . '_' . Str::random(10) . '_' . $request->file_name;
            $path = "images/users/" . $fileName;

            $disk = Storage::disk('s3');
            $client = $disk->getClient();

            $command = $client->getCommand('PutObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $path,
                'ContentType' => $request->file_type,
            ]);

            $presignedRequest = $client->createPresignedRequest($command, '+5 minutes');

            return response()->json([
                'status' => 1,
                'upload_url' => (string) $presignedRequest->getUri(),
                'path' => $path,
                'url' => env('AWS_URL') . '/' . $path
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
