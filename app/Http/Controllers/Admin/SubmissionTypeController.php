<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionTypeModel;
use Illuminate\Http\Request;

class SubmissionTypeController extends Controller
{
    public function index()
    {
        return view('layouts.admin.submission-type.index');
    }

    public function data(Request $request)
    {
        $columns = ['id', 'periode', 'program_mbkm', 'start_date', 'end_date', 'status'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada

        $query = SubmissionTypeModel::query()
            ->select('submission_types.*');
        // Filtering pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('periode', 'like', "%{$search}%")
                    ->orWhere('program_mbkm', 'like', "%{$search}%");
            });
        }

        $total = $query->count();

        // Ambil data sesuai pagination
        $data = $query->orderBy($orderColumn, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data->map(function ($row, $index) use ($start) {
                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'periode' => $row->periode,
                    'program_mbkm' => $row->program_mbkm ?? '-',
                    'start_date' => $row->start_date ?? '-',
                    'end_date' => $row->end_date ?? '-',
                    'status' => $row->status == 1 ? '<span class="badge bg-success text-white">Active</span>' : '<span class="badge bg-danger text-white">Inactive</span>',
                    'action' => '<button class="btn btn-sm btn-warning btn-edit" data-id="' . $row->id . '">
                                    Edit
                                </button> ',
                ];
            }),
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $user = SubmissionTypeModel::find($request->id);

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
            'program_mbkm' => 'nullable|string|max:255',
        ]);

        SubmissionTypeModel::create([
            'program_mbkm' => $data['program_mbkm'],
        ]);

        return response()->json(['success' => true, 'message' => 'Submission Type saved successfully']);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'program_mbkm' => 'nullable|string|max:255',
        ]);

        $submissionType = SubmissionTypeModel::findOrFail($id);
        $submissionType->update($data);

        return response()->json(['success' => true, 'message' => 'Submission Type updated successfully']);
    }

    public function edit($id)
    {
        $submissionType = SubmissionTypeModel::find($id);
        return response()->json($submissionType);
    }

    public function show($id)
    {
        $submissionType = SubmissionTypeModel::find($id);
        return response()->json($submissionType);
    }
}
