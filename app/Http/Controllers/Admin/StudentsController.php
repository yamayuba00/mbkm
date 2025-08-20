<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentsController extends Controller
{
    public function index()
    {
        return view('layouts.admin.students.index');
    }

    public function getFaculty()
    {
        $faculties = Faculty::orderBy('name', 'ASC')->get(['id', 'name']);
        return response()->json($faculties);
    }

    public function getProdi($id)
    {
        $prodis = Faculty::find($id)?->prodi()->orderBy('name', 'ASC')->get(['id', 'name']) ?? [];
        return response()->json($prodis);
    }

    public function data(Request $request)
    {
        $columns = ['id', 'nim', 'username', 'email', 'status', 'prodi_id'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada
        $query = User::with(
            [
                'detail:id,user_id,nim,prodi_id',
                
                'detail.prodi:id,name,faculty_id',
            ]
        )
            ->where('role', 4);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");

                // $q->orWhereHas('detail', function ($sub) use ($search) {
                //     $sub->where('nim', 'like', "%{$search}%")
                //         ->orWhere(' ', 'like', "%{$search}%")
                //         ->orWhere('prodi_id', 'like', "%{$search}%");
                // });
            });
        }

        $total = $query->count();

        $data = $query->orderBy('created_at', 'DESC')
            ->skip($start)
            ->take($length)
            ->get();

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
                    'nim' => optional($row->detail)->nim, // Ambil dari relasi `detail`
                    'username' => $row->username,
                    'prodi' => optional($row->detail)->prodi?->name . ' - ' . optional($row->detail->prodi)->faculty?->name ?? '-',
                    'email' => $row->email,
                    'status' => $row->status == 1 ? '<span class="badge bg-success text-white">Active</span>' : '<span class="badge bg-danger text-white">Inactive</span>',
                    'action' => '<button class="btn btn-sm ' . $buttonClass . ' btn-toggle-status" data-id="' . $row->id . '">' . $buttonText . '</button>',
                ];
            }),
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $user = User::findOrFail($request->id);

        // Toggle: 1 → 0, 0 → 1
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
            'nim' => 'required|string|max:20|unique:user_details,nim',
            'gender' => 'required|integer|in:1,2', // 1: laki-laki, 2: perempuan
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);


        $data['password'] = bcrypt($request->nidn);
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' =>  4,
        ]);

        $user->detail()->create([
            'nidn' => $data['nidn'],
            'prodi_id' => $data['prodi_id'],
            'facultie_id' => $data['facultie_id'],
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
        ]);
        return response()->json(['message' => 'Lecturer created successfully.']);
    }


    public function show($id)
    {
        $data = User::findOrFail($id);
        return response()->json($data);
    }

    public function edit($id)
    {
        $user = User::with('detail')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:users,id,' . $id,
            'email' => 'required|string|email|max:255|unique:users,id,' . $id,
        ]);

        $student = User::findOrFail($id);
        $student->update([]);

        if ($request->filled('password')) {
            $student->password = bcrypt($data['password']);
            $student->save();
        }
        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}
