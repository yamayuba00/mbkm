<?php

namespace App\Http\Controllers\Students;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MbkmProgramModel;
use App\Models\SubmissionPeriod;
use App\Models\SubmissionTypeModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MbkmProgramController extends Controller
{
    public function index()
    {
        return view('layouts.student.mbkm-program.index');
    }

    public function getSubmissionTypes()
    {
        $programs = SubmissionTypeModel::select('id','program_mbkm')->orderby('id', 'ASC')->get();
        return response()->json($programs);
    }
    public function getSubmissionPeriods()
    {
        $programs = SubmissionPeriod::select('id', 'periode')->orderby('id', 'ASC')->get();
        return response()->json($programs);
    }

    public function data(Request $request)
    {
        $columns = ['id','title', 'lecturer_id', 'submission_periods_id', 'submission_types_id', 'ipk', 'sks', 'academic_value', 'field_value', 'reason' ,  'status'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada



        $query = MbkmProgramModel::with(['lecturer', 'student', 'submissionType', 'submissionPeriod'])
            ->where('student_id', Auth::user()->id) // Assuming the student is logged in
            ->select('mbkm_programs.*');

        // Filtering pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('lecturer_id', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('submission_types_id', 'like', "%{$search}%")
                    ->orWhere('submission_periods_id', 'like', "%{$search}%")
                    ->orWhere('ipk', 'like', "%{$search}%")
                    ->orWhere('sks', 'like', "%{$search}%")
                    ->orWhere('academic_value', 'like', "%{$search}%")
                    ->orWhere('field_value', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
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

                // Badge status utama
                if ($row->status == 1) {
                    $statusLabel = '<span class="badge bg-warning text-white">PENDING</span>';
                } elseif ($row->status == 2) {
                    $statusLabel = '<span class="badge bg-success text-white">ACCEPTED</span>';
                } elseif ($row->status == 3) {
                    $statusLabel = '<span class="badge bg-danger text-white">REJECTED</span>';
                } else {
                    $statusLabel = '<span class="badge bg-secondary text-white">UNKNOWN</span>';
                }

                // Badge tambahan validasi dosen
                $lecturerValidateBadge = '';
                if ($row->validated_by == null) {
                    $lecturerValidateBadge = '<span class="badge bg-info text-white">WAITING ACCEPT FROM LECTURER</span>';
                } else {
                    $lecturerValidateBadge = '<span class="badge bg-success text-white">ACCEPTED</span>';
                }

                // Optional: tombol edit hanya saat status pending
                $editButton = '';
                if ($row->status == 1) {
                    $editButton = '<button title="Edit Data" class="btn btn-sm btn-outline-warning btn-edit" data-id="' . $row->id . '">
                        <i class="fas fa-edit"></i>
                    </button>';
                }

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'title' => $row->title . ' - (' . $row->code . ')',
                    'lecturer' => $row->lecturer ? $row->lecturer->username : '',
                    'program_mbkm' => $row->submissionPeriod->periode . ' - ' . $row->submissionType->program_mbkm,
                    'ipk' => $row->ipk,
                    'sks' => $row->sks,
                    'academic_value' => $row->academic_value,
                    'field_value' => $row->field_value,
                    'reason' => $row->reason
                        ? '<a href="' . $row->reason . '" target="_blank" title="Visit Your Meeting Link" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-link"></i> Visit Link
                        </a>'
                                            : '<button class="btn btn-sm btn-outline-secondary" disabled title="Link belum tersedia">
                                <i class="fas fa-unlink"></i> No link yet
                        </button>',
                    'status' => $statusLabel . ' ' . $lecturerValidateBadge,
                    'action' => $editButton,
                ];
            }),
        ]);
    }

    public function edit($id)
    {
        $program = MbkmProgramModel::findOrFail($id);
        return response()->json($program);
    }

    public function show($id)
    {
        $program = MbkmProgramModel::findOrFail($id);
        return response()->json($program);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'submission_types_id' => 'required',
            'submission_period_id' => 'required',
            'ipk' => 'required|numeric|min:0|max:4',
            'sks' => 'required|integer|min:0',
            'cv' => 'required|file|mimes:pdf|max:1024',
            'khs' => 'required|file|mimes:pdf|max:1024',
            'portfolio' => 'required|file|mimes:pdf|max:1024',
        ]);

        $data['student_id'] = Auth::user()->id;
        $data['lecturer_id'] = Auth::user()->lecturer->id;
        $data['validated_by'] = null;
        $data['status'] = 1; // Default status is pending

        if ($request->hasFile('cv')) {
            $ext = $request->file('cv')->getClientOriginalExtension();
            $filename = time(); 
            $path = "uploads/cv";
            Storage::makeDirectory($path);
            $request->file('cv')->storeAs($path, "$filename.$ext", 'public');
            $data['cv'] = $filename;
        }

        if ($request->hasFile('khs')) {
            $ext = $request->file('khs')->getClientOriginalExtension();
            $filename = time();
            $path = "uploads/khs";
            Storage::makeDirectory($path);
            $request->file('khs')->storeAs($path, "$filename.$ext", 'public');
            $data['khs'] = $filename;
        }

        if ($request->hasFile('portfolio')) {
            $ext = $request->file('portfolio')->getClientOriginalExtension();
            $filename = time();
            $path = "uploads/portfolios";
            Storage::makeDirectory($path);
            $request->file('portfolio')->storeAs($path, "$filename.$ext", 'public');
            $data['portfolio'] = $filename;
        }

        MbkmProgramModel::create([
            'title' => $data['title'],
            'code' => 'PMBKMUPB-' . Str::random(6),
            'student_id' => $data['student_id'],
            'lecturer_id' => $data['lecturer_id'],
            'submission_types_id' => $data['submission_types_id'],
            'submission_period_id' => $data['submission_period_id'],
            'ipk' => $data['ipk'],
            'sks' => $data['sks'],
            'cv' => $data['cv'],
            'khs' => $data['khs'],
            'portfolio' => $data['portfolio'],
        ]);

        return response()->json(['message' => 'Program MBKM created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'submission_types_id' => 'required',
            'submission_period_id' => 'required',
            'ipk' => 'required|numeric|min:0|max:4',
            'sks' => 'required|integer|min:0',
            'cv' => 'nullable|file|mimes:pdf|max:1024',
            'khs' => 'nullable|file|mimes:pdf|max:1024',
            'portfolio' => 'nullable|file|mimes:pdf|max:1024',
        ]);

        $program = MbkmProgramModel::findOrFail($id);

      
        if ($request->hasFile('cv')) {
            if ($program->cv) {
                unlink(public_path("uploads/cv/{$program->cv}.pdf"));
            }
            $ext = $request->file('cv')->getClientOriginalExtension();
            $filename = time();
            $path = "uploads/cv";
            Storage::makeDirectory($path);
            $request->file('cv')->storeAs($path, "$filename.$ext", 'public');
            $data['cv'] = $filename;
        }
        if ($request->hasFile('khs')) {
            if ($program->khs) {
                unlink(public_path("uploads/khs/{$program->khs}.pdf"));
            }
            $ext = $request->file('khs')->getClientOriginalExtension();
            $filename = time();
            $path = "uploads/khs";
            Storage::makeDirectory($path);
            $request->file('khs')->storeAs($path, "$filename.$ext", 'public');
            $data['khs'] = $filename;
        }

        if ($request->hasFile('portfolio')) {
            if ($program->portfolio) {
                unlink(public_path("uploads/portfolios/{$program->portfolio}.pdf"));
            }
            $ext = $request->file('portfolio')->getClientOriginalExtension();
            $filename = time();
            $path = "uploads/portfolios";
            Storage::makeDirectory($path);
            $request->file('portfolio')->storeAs($path, "$filename.$ext" , 'public');
            $data['portfolio'] = $filename;
        }

        $program->update($data); 
        return response()->json(['message' => 'Program MBKM updated successfully.']);
    }

    public function destroy($id)
    {
        $program = MbkmProgramModel::findOrFail($id);
        $program->delete();

        return response()->json(['message' => 'Program MBKM deleted successfully.']);
    }
}
