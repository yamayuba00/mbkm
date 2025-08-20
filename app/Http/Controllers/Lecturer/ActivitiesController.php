<?php

namespace App\Http\Controllers\Lecturer;

use Carbon\Carbon;
use App\Models\Activities;
use Illuminate\Http\Request;
use App\Models\MbkmProgramModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActivitiesController extends Controller
{
    public function index()
    {
        return view('layouts.lecturer.activities.index');
    }

    public function getYourMbkmProgramsAccepted()
    {
        $mbkm = MbkmProgramModel::with(['submissionType:id,program_mbkm', 'submissionPeriod:id,periode'])
            ->where('student_id', Auth::user()->id)->where('validated_by', Auth::user()->lecturer->id)
            ->where('status', 2)
            ->get();

        return response()->json($mbkm);
    }



    public function data(Request $request)
    {
        $columns = ['id', 'lecturer_id', 'mbkm_program_id', 'date', 'activity', 'duration', 'status'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada



        $query = Activities::with(['lecturer', 'student', 'mbkmProgram'])
            ->where('lecturer_id', Auth::user()->id);

        // Filtering pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('lecturer_id', 'like', "%{$search}%")
                    ->orWhere('activity', 'like', "%{$search}%")
                    ->orWhere('mbkm_program_id', 'like', "%{$search}%")
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

                if ($row->status == 0) {
                    $statusLabel = '<span class="badge bg-warning text-white">PENDING</span>';
                } elseif ($row->status == 1) {
                    $statusLabel = '<span class="badge bg-success text-white">ACCEPTED</span>';
                } elseif ($row->status == 2) {
                    $statusLabel = '<span class="badge bg-danger text-white">REJECTED</span>';
                }

                $editButton = '';
                $trackingButton = '';

                if ($row->status == 0) {
                    $editButton = '<button title="View Activity: ' . $row->student->username . '" class="btn btn-sm btn-outline-warning btn-edit" data-id="' . $row->id . '">
                    <i class="fas fa-eye"></i> View Activity
                    </button> 
                    ';
                } else {
                    $trackingButton = '<button title="View Activity: ' . $row->student->username . '" class="btn btn-sm btn-outline-warning btn-edit" data-id="' . $row->id . '">
                    <i class="fas fa-eye"></i> View Activity
                    </button> 
                    ';
                }

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'title' => $row->mbkmProgram->title,
                    'lecturer' => $row->lecturer ? $row->lecturer->username : '',
                    'program_mbkm' =>  $row->mbkmProgram->submissionType->program_mbkm,
                    'date' => Carbon::parse($row->date)->format('d M Y'),
                    'activity' => $row->activity,
                    'duration' => $row->duration . ' Minutes',
                    'status' => $statusLabel,
                    'action' => $editButton . ' ' . $trackingButton,
                ];
            }),
        ]);
    }


    public function edit($id)
    {
        $activities = activities::with(['lecturer', 'student', 'mbkmProgram'])->find($id);
        return response()->json($activities);
    }

    public function updateStatus(Request $request, $id)
    {
        $activities = Activities::find($id);
        $activities->update([
            'status' => $request->status,
            'validated_at' => now(),
        ]);
        return response()->json($activities);
    }
}
