<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProgramsCampus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Prodi;

class ProgramCampusController extends Controller
{
    public function index()
    {
        return view('layouts.admin.faculty-study-program.index');
    }

    public function getDataFaculty(Request $request)
    {
        $columns = ['id', 'name', 'code'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada

        $query = Faculty::withCount(['prodi','userDetail']);
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
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

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'name' => $row->name,
                    'code' => $row->code ?? '-',
                    'prodi_count' => $row->prodi_count,
                    'user_count' => $row->user_detail_count,
                    'action' => '<button class="btn btn-sm btn-outline-warning btn-edit" data-id="' . $row->id . '">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-trash-faculty" data-id="' . $row->id . '">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info btn-add-prodi" data-id="' . $row->id . '">
                                    <i class="fas fa-plus"></i> Add Prodi (' . $row->prodi->count() . ')
                                </button>
                                ',
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',

        ]);

        DB::beginTransaction();

        try {

            Faculty::create([
                'name' => $request->name,
                'code' => $request->code,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
        ]);


        $faculty = Faculty::findOrFail($id);
        $faculty->update([
            'name' => $data['name'],
            'code' => $data['code'],

        ]);

        return response()->json(['success' => true, 'message' => 'Faculty updated successfully']);
    }

    public function edit($id)
    {
        $dataView = Faculty::find($id);
        return response()->json($dataView);
    }

    public function show($id)
    {
        $dataView = Faculty::find($id);
        return response()->json($dataView);
    }

    public function destroy($id)
    {
        $faculty = Faculty::withCount(['prodi', 'userDetail'])->find($id);
        if($faculty->user_detail_count > 0 || $faculty->prodi_count > 0){
            return response()->json(['success' => false, 'message' => 'The Faculty cannot be deleted because it still has users or study programs.'], 400);
        }
        return response()->json(['success' => true, 'message' => 'Faculty deleted successfully']);
    }

    // focus on program studi
    public function getDataProdi(Request $request)
    {
        $columns = ['id', 'name', 'code', 'faculty_id', 'level'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada

        $query = Prodi::with('faculty')->withCount('userDetail');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('faculty_id', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
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

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'faculty_id' => $row->faculty->name . ' - ' . $row->faculty->code ?? '-',
                    'name' => $row->name,
                    'code' => $row->code ?? '-',
                    'userCount' => $row->userDetail->count(),
                    'level' => $row->level ?? '-',
                    'action' => '<button class="btn btn-sm btn-outline-warning btn-edit-prodi" data-id="' . $row->id . '">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <button class="btn btn-sm btn-outline-danger btn-delete-prodi" data-id="' . $row->id . '">
                                    <i class="fas fa-trash"></i>
                                </button>
                                ',
                ];
            }),
        ]);
    }

    public function storeProdi(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
        ]);
        Prodi::create([
            'name' => $request->name,
            'code' => $request->code,
            'faculty_id' => $request->faculty_id,
            'level' => $request->level
        ]);
        return response()->json(['success' => true, 'message' => 'Program Studi created successfully.']);
    }

    public function editProdi($id)
    {
        $prodi = Prodi::with('faculty')
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'id' => $prodi->id,
            'name_prodi' => $prodi->name,
            'code' => $prodi->code,
            'level' => $prodi->level,
            'faculty_id' => $prodi->faculty_id,
            'faculty' => $prodi->faculty->name,
        ]);
    }
    public function updateProdi(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
        ]);
        $prodi = Prodi::where('faculty_id', $request->faculty_id)->findOrFail($id);
        $prodi->update([
            'name' => $request->name,
            'code' => $request->code,
            'level' => $request->level,
            'faculty_id' => $request->faculty_id
        ]);
        return response()->json(['success' => true, 'message' => 'Program Studi updated successfully']);
    }

    public function destroyProdi($id)
    {
        $prodi = Prodi::withCount('userDetail')->find($id);
        if($prodi->user_detail_count > 0){
            return response()->json(['success' => false, 'message' => 'The Study Program cannot be deleted because it still has users.'], 400);
        }
        $prodi->delete();
        return response()->json(['success' => true, 'message' => 'Program Studi deleted successfully']);
    }
}
