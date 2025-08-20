<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InformationProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformationProgramController extends Controller
{
    public function index()
    {
        return view('layouts.admin.information-program.index');
    }

    public function data(Request $request)
    {
        $columns = ['id', 'title', 'content', 'created_by'];
        $search = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $start = $request->input('start', 0); // untuk nomor urut
        $length = $request->input('length', 10); // default 10 jika tidak ada

        $query = InformationProgram::query();
        // ->select('submission_types.*');
        // Filtering pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('created_by', 'like', "%{$search}%");
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

                return [
                    'no' => $start + $index + 1,
                    'id' => $row->id,
                    'title' => $row->title,
                    'content' => $row->content ?? '-',
                    'created_by' => $row->created_by ?? '-',
                    'action' => '<button class="btn btn-sm btn-warning btn-edit" data-id="' . $row->id . '">
                                    Edit
                                </button>',
                ];
            }),
        ]);
    }

  

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|max:255',
        ]);

        InformationProgram::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Information Program saved successfully']);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|max:255',
        ]);

        $submissionType = InformationProgram::findOrFail($id);
        $submissionType->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Information Program updated successfully']);
    }

    public function edit($id)
    {
        $submissionType = InformationProgram::find($id);
        return response()->json($submissionType);
    }

    public function show($id)
    {
        $submissionType = InformationProgram::find($id);
        return response()->json($submissionType);
    }

    public function destroy($id)
    {
        InformationProgram::destroy($id);
        return response()->json(['success' => true, 'message' => 'Information Program deleted successfully']);
    }
}
