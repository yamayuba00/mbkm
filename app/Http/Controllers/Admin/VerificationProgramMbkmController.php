<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\MbkmProgramModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VerificationProgramMbkmController extends Controller
{
    public function index()
    {
        return view('layouts.admin.verification-program-mbkm.index');
    }
    public function getViewlistProgram()
    {
        return view('layouts.admin.verification-program-mbkm.list-verification');
    }

    public function data(Request $request)
    {
        $columns = ['id', 'title', 'lecturer_id', 'submission_period_id', 'submission_types_id', 'ipk', 'sks', 'status', 'student_id'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada

        $query = MbkmProgramModel::with(['lecturer', 'student', 'submissionType','submissionPeriod'])
            ->where('status', 1)
            ->select('mbkm_programs.*');
        // Filtering pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('lecturer_id', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('submission_period_id', 'like', "%{$search}%")
                    ->orWhere('submission_types_id', 'like', "%{$search}%")
                    ->orWhere('ipk', 'like', "%{$search}%")
                    ->orWhere('sks', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $total = $query->count();

        $data = $query->orderBy('created_at', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        // Format response untuk DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data->map(function ($row, $index) use ($start) {

                if ($row->status == 1) {
                    $statusLabel = '<span class="badge bg-warning text-white">PENDING</span>';
                } elseif ($row->status == 2) {
                    $statusLabel = '<span class="badge bg-success text-white">APPROVED</span>';
                } elseif ($row->status == 3) {
                    $statusLabel = '<span class="badge bg-danger text-white">REJECTED</span>';
                }

                $editButton = '';
                // $trackingButton = '';

                if ($row->status == 1) {
                    $editButton = '<button title="Verfication" class="btn btn-sm btn-outline-info btn-edit" data-id="' . $row->id . '">
                    <i class="fas fa-eye"></i> Verification
                    </button>';
                }

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'title' => $row->title . ' - ' . '(' . $row->code . ')',
                    'lecturer' => $row->lecturer ? $row->lecturer->username : '',
                    'student' => $row->student ? $row->student->username : '',
                    'program_mbkm' => $row->submissionPeriod->periode . ' - ' . $row->submissionType->program_mbkm,
                    'ipk' => $row->ipk,
                    'sks' => $row->sks,
                    'status' => $statusLabel,
                    'action' => $editButton,
                ];
            }),
        ]);
    }

    public function edit($id)
    {
        $program = MbkmProgramModel::where('id', $id)
            ->with([
                'lecturer:id,username',
                'lecturer.detail:id,user_id,nidn',
                'student:id,username',
                'student.detail:id,user_id,nim,class,phone',
                'submissionType:id,program_mbkm',
                'submissionPeriod:id,periode',
            ])->findOrFail($id);
        return response()->json($program);
    }

    public function list(Request $request)
    {
        $columns = ['id','title', 'lecturer_id', 'submission_types_id', 'ipk', 'sks', 'status', 'student_id'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada

        $query = MbkmProgramModel::with(['lecturer', 'student', 'submissionType']);
        // Filtering pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('lecturer_id', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('submission_types_id', 'like', "%{$search}%")
                    ->orWhere('ipk', 'like', "%{$search}%")
                    ->orWhere('sks', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $total = $query->count();

        $data = $query->orderBy('created_at', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        // Format response untuk DataTables
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data->map(function ($row, $index) use ($start) {

                if ($row->status == 1) {
                    $statusLabel = '<span class="badge bg-warning text-white">PENDING</span>';
                } elseif ($row->status == 2) {
                    $statusLabel = '<span class="badge bg-success text-white">ACCEPTED</span>';
                } elseif ($row->status == 3) {
                    $statusLabel = '<span class="badge bg-danger text-white">REJECTED</span>';
                }

                $editButton = '';
                // $trackingButton = '';

                if ($row->status == 1) {
                    $editButton = '<button title="Verfication" class="btn btn-sm btn-outline-info btn-edit" data-id="' . $row->id . '">
                    <i class="fas fa-eye"></i> Verification
                    </button>';
                }

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'title' => $row->title . ' - ' . '(' . $row->code . ')',
                    'lecturer' => $row->lecturer ? $row->lecturer->username : '',
                    'student' => $row->student ? $row->student->username : '',
                    'program_mbkm' => $row->submissionType ? $row->submissionType->periode . ' - ' . $row->submissionType->program_mbkm : '',
                    'ipk' => $row->ipk,
                    'sks' => $row->sks,
                    'status' => $statusLabel,
                    'action' => $editButton,
                ];
            }),
        ]);
    }

    public function show($id)
    {
        $program = MbkmProgramModel::where('id', $id)->with('lecturer:id,username', 'student:id,username', 'submissionType', 'submissionPeriod')->findOrFail($id);
        return response()->json($program);
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:2,3', // 2 = approved, 3 = rejected
        ]);

        $program = MbkmProgramModel::findOrFail($id);
        $program->verified_at = now();
        $program->verified_by = Auth::user()->id;
        $program->status = $request->status;
        $program->save();

        return response()->json(['success' => true]);
    }
}
