<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Faculty;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function viewLogin()
    {
        return view('auth.login');
    }

    // POST LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // $redirectUrl = match ($user->role) {
            //     1 => route('admin.dashboard'),
            //     2 => route('kaprodi.dashboard'),
            //     3 => route('lecturer.dashboard'),
            //     4 => route('student.dashboard'),
            //     default => route('login'),
            // };

            $redirectUrl = '';
            switch ($user->role) {
                case 1:
                    $redirectUrl = route('admin.dashboard');
                    break;
                case 2:
                    $redirectUrl = route('kaprodi.dashboard');
                    break;
                case 3:
                    $redirectUrl = route('lecturer.dashboard');
                    break;
                case 4:
                    $redirectUrl = route('student.dashboard');
                    break;
                default:
                    $redirectUrl = route('login');
                    break;
            }

            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl,
            ]);
        }

        return response()->json([
            'message' => 'Email and password does not match.',
        ], 401);
    }

    public function viewRegister()
    {
        return view('auth.register');
    }

    public function getLecturer()
    {
        $data = User::with('detail:id,user_id,nidn')
            ->where('role', 3)
            ->select('id', 'username')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'nidn' => $user->detail->nidn ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function getFaculty()
    {
        $data = Faculty::orderBy('name', 'ASC')->get(['id', 'name']);
        return response()->json($data);
    }
    public function getProdi($id)
    {
        $prodis = Faculty::find($id)?->prodi()->orderBy('name', 'ASC')->get(['id', 'name']) ?? [];
        return response()->json($prodis);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'nim' => 'required|string|unique:user_details,nim',
            'gender' => 'required|in:1,2',
            'email' => 'required|email|unique:users,email',
            'class' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'password' => 'required|min:8|confirmed',
            'lecturer_id' => 'required|exists:users,id,role,3', // Ensure lecturer_id exists and is a lecturer
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 4,
            'status' => 1,
            'lecturer_id' => $request->lecturer_id,
        ]);

        $user->detail()->create([
            'nim' => $request->nim,
            'prodi_id' => $request->prodi_id,
            'faculties_id' => $request->faculties_id,
            'class' => $request->class,
            'address' => $request->address,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('login'),
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('login')
            ]);
        }

        return redirect()->route('login');
    }

    public function viewForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function verifyReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nim' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->detail->nim !== $request->nim) {
            return response()->json(['message' => 'Email or NIM does not match.'], 400);
        }

        return response()->json(['message' => 'User verified.']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nim' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->detail->nim !== $request->nim) {
            return response()->json(['message' => 'Email or NIM does not match.'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password has been reset, please login.']);
    }
}
