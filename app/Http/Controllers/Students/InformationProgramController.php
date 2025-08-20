<?php

namespace App\Http\Controllers\Students;

use Illuminate\Http\Request;
use App\Models\InformationProgram;
use App\Http\Controllers\Controller;

class InformationProgramController extends Controller
{
    public function index()
    {
        return view('layouts.student.information-program.index');
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

        $query = InformationProgram::with(['creator']);
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('created_by', 'like', "%{$search}%");
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
                    'title' => $row->title,
                    'content' => $row->content ?? '-',
                    'created_by' => $row->creator->username ?? '-',
                ];
            }),
        ]);
    }
}
