<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LecturerController extends Controller
{
    public function index()
    {
        return view('layouts.admin.lecturer.index');
    }
    public function data(Request $request)
    {
        $columns = ['id', 'nidn', 'username', 'email', 'status', 'prodi_id'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada

        $query = User::with(
            [
                'detail:id,user_id,nidn,prodi_id',
                'detail.prodi:id,name,faculty_id',
            ]
        )->where('role', 3);
        // Filtering pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('detail.prodi_id', 'like', "%{$search}%")
                    ->orWhere('detail.nidn', 'like', "%{$search}%");
            });
        }

        $total = $query->count();

        // Ambil data sesuai pagination
        $data = $query->orderBy($orderColumn, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // Format response untuk DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data->map(function ($row, $index) use ($start) {
                $buttonText = $row->status == 1 ? 'Deactivate' : 'Activate';
                $buttonClass = $row->status == 1 ? 'btn-outline-danger' : 'btn-outline-success';

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'nidn' => optional($row->detail)->nidn ?? '-', // Ambil dari relasi `detail`
                    'username' => $row->username,
                    'email' => $row->email,
                    'prodi' => optional($row->detail)->prodi?->name . ' - ' . optional($row->detail->prodi)->faculty?->name ?? '-',
                    'status' => $row->status == 1 ? '<span class="badge bg-success text-white">Active</span>' : '<span class="badge bg-danger text-white">Inactive</span>',
                    'action' => '<button class="btn btn-sm btn-outline-warning btn-edit" data-id="' . $row->id . '">
                                    Edit
                                </button> 
                                <button class="btn btn-sm ' . $buttonClass . ' btn-toggle-status" data-id="' . $row->id . '">' . $buttonText . '</button>
',
                ];
            }),
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return response()->json([
            'success' => true,
            'status' => $user->status,
            'message' => $user->status == 1 ? 'User Activated' : 'User Deactivated',
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'nidn' => 'required|string|max:20|unique:user_details,nidn',
            'gender' => 'required|integer|in:1,2', // 1: laki-laki, 2: perempuan
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $data['role'] = 3;
        $data['password'] = bcrypt($request->nidn);
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
        ]);

        $user->detail()->create([
            'nidn' => $data['nidn'],
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
        ]);
        return response()->json(['message' => 'Lecturer created successfully.']);
    }

    public function show($id)
    {
        $lecturer = User::findOrFail($id);
        return response()->json($lecturer);
        // return view('layouts.admin.lecturer.show', compact('lecturer'));
    }

    public function edit($id)
    {
        $user = User::with('detail')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            'nidn' => 'nullable|string|max:20|unique:user_details,nidn,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|integer|in:1,2', // 1: laki-laki, 2: perempuan
            // 'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            // 'nidn' => 'nullable|string|max:20|unique:user_details,nidn,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'username' => $data['username'],
            'email' => $data['email'] ?? $user->email,
            'role' => 3,
            'gender' => $request->gender
        ]);

        $user->detail()->update([
            // 'nidn' => $data['nidn'] ?? $user->detail->nidn,
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
        ]);
        return response()->json(['message' => 'Lecturer updated successfully.']);
    }

    // reset password

    public function resetPassword(Request $request, $id)
    {
        $user = User::with('detail')->findOrFail($id);
        $nidn = $user->detail->nidn ?? null;
        if (!$nidn) {
            return response()->json(['message' => 'NIDN not found for this user.'], 422);
        }

        // Set password baru sebagai bcrypt(NIDN)
        $user->password = bcrypt($nidn);
        $user->save();

        return response()->json(['message' => 'Password has been reset using NIDN.']);
    }

    // non active can't destroy

    public function deactivedAccount($id)
    {
        $user = User::findOrFail($id);
        $user->status = 0; // Set status to non-active
        $user->save();

        return response()->json(['message' => 'Lecturer account deactivated successfully.']);
    }
}
