<?php

namespace App\Http\Controllers\Lecturer;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        return view('layouts.admin.students.index');
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
            ->where('lecturer_id', Auth::user()->id)
            ->where('role', 3);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('detail.prodi_id', 'like', "%{$search}%")
                    ->orWhere('detail.nim', 'like', "%{$search}%");
            });
        }

        $total = $query->count();

        $data = $query->orderBy($orderColumn, $orderDir)
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
                    'nim' => optional($row->detail)->nim ?? '-', // Ambil dari relasi `detail`
                    'username' => $row->username,
                    'prodi' => optional($row->detail)->prodi?->name . ' - ' . optional($row->detail->prodi)->faculty?->name ?? '-',
                    'email' => $row->email,
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
}
