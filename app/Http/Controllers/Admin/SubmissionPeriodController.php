<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionPeriod;
use Illuminate\Http\Request;

class SubmissionPeriodController extends Controller
{

    public function index()
    {
        return view('layouts.admin.submission-period.index');
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

        $query = SubmissionPeriod::query();
        // Filtering pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('periode', 'like', "%{$search}%");
            });
        }

        $total = $query->count();

        // Ambil data sesuai pagination
        $data = $query->orderBy($orderColumn, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // dd($data);

        // Format response untuk DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data->map(function ($row, $index) use ($start) {
                $buttonText = $row->status == 1 ? 'Deactivate' : 'Activate';
                $buttonClass = $row->status == 1 ? 'btn-danger' : 'btn-success';

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'periode' => $row->periode,
                    'action' => '<button class="btn btn-sm btn-warning btn-edit" data-id="' . $row->id . '">
                                    Edit
                                </button>',
                ];
            }),
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $user = SubmissionPeriod::find($request->id);

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
            'periode' => 'required|string|max:255',
        ]);

        SubmissionPeriod::create([
            'periode' => $data['periode'],
        ]);

        return response()->json(['success' => true, 'message' => 'Submission Type saved successfully']);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'periode' => 'required|string|max:255',
        ]);

        $submissionType = SubmissionPeriod::findOrFail($id);
        $submissionType->update($data);

        return response()->json(['success' => true, 'message' => 'Submission Type updated successfully']);
    }

    public function edit($id)
    {
        $submissionType = SubmissionPeriod::find($id);
        return response()->json($submissionType);
    }

    public function show($id)
    {
        $submissionType = SubmissionPeriod::find($id);
        return response()->json($submissionType);
    }
}
