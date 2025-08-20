<?php

namespace App\Http\Controllers\Lecturer;

use Illuminate\Http\Request;
use App\Models\MbkmProgramModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MbkmProgramController extends Controller
{

    public function index()
    {
        return view('layouts.lecturer.mbkm-program.index');
    }


    public function data(Request $request)
    {
        $columns = ['id','title', 'lecturer_id', 'submission_periods_id', 'submission_types_id', 'ipk', 'sks', 'status', 'validated_by'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada



        $query = MbkmProgramModel::with(['lecturer', 'student', 'submissionType', 'submissionPeriod'])
            ->where('lecturer_id', Auth::user()->id)
            ->where('status', 2)
            ->select('mbkm_programs.*');

        // Filtering pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('lecturer_id', 'like', "%{$search}%")
                    ->orWhere('submission_types_id', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('submission_periods_id', 'like', "%{$search}%")
                    ->orWhere('ipk', 'like', "%{$search}%")
                    ->orWhere('sks', 'like', "%{$search}%")
                    ->orWhere('validated_by', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $total = $query->count();

        $data = $query->orderBy('created_at', 'desc')
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

                if($row->validated_by == Auth::user()->id){
                    $validated = '<span class="badge bg-success text-white">ACCEPTED</span>';
                }else{
                    $validated = '<span class="badge bg-warning text-white">PENDING</span>';
                }

                $validateProgram = '';
                $trackingButton = '';
                $inputValue = '';

                if ($row->status == 2 &&  $row->validated_by == null) {
                    $validateProgram = '<button title="Accepted Program ' . $row->student->username .' " class="btn btn-sm btn-outline-info btn-accepted-program" data-id="' . $row->id . '">
                    <i class="fas fa-check"></i> Accepted Program
                    </button>';
                }else if($row->status == 2 && $row->validated_by == Auth::user()->id && $row->validated_at != null){
                    $inputValue = '<button title="Input : ' . $row->student->username . ' " class="btn btn-sm btn-outline-success btn-input-nilai" data-id="' . $row->id . '">
                    <i class="fas fa-comment"></i> Nilai & Send Link
                    </button>';
                    $trackingButton = '<button title="Look Logbook: ' . $row->student->username . ' " class="btn btn-sm btn-outline-info btn-list-logbook" data-id="' . $row->id . '">
                    <i class="fas fa-eye"></i> View Logbook
                    </button>';
                    
                }
                elseif ($row->status == 3) {
                    $trackingButton = '';
                    $inputValue = '';
                }

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'title' => $row->title . ' - ' . '(' . $row->code . ')',
                    'lecturer' => $row->lecturer ? $row->lecturer->username : '',
                    'program_mbkm' =>  $row->submissionPeriod->periode . ' - ' . $row->submissionType->program_mbkm,
                    'ipk' => $row->ipk,
                    'sks' => $row->sks,
                    'status' => $statusLabel,
                    'validated_by' => $validated,
                    'action' => $inputValue . ' '. $validateProgram . ' ' . $trackingButton,
                ];
            }),
        ]);
    }




    public function edit($id)
    {
        $program = MbkmProgramModel::with(['lecturer', 'student', 'submissionType', 'submissionPeriod'])->find($id);
        return response()->json($program);
    }

    public function show($id)
    {
        $program = MbkmProgramModel::findOrFail($id);
        return response()->json($program);
    }

    

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'academic_value' => 'nullable',
            'field_value' => 'nullable',
            'reason' => 'nullable',
        ]);

        $program = MbkmProgramModel::find($id);

        $program->update($data);
        return response()->json(['message' => 'Program MBKM updated successfully.']);
    }  

    public function validateProgramWithLecturer(Request $request)
    {
        $program = MbkmProgramModel::find($request->id);
        $program->update([
            'validated_by' => Auth::user()->id,
            'validated_at' => now(),
        ]);
        return response()->json(['message' => 'Program MBKM accepted successfully.']);
    }
}
