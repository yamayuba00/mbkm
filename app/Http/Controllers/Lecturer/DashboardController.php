<?php

namespace App\Http\Controllers\Lecturer;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MbkmProgramModel;
use App\Models\SubmissionTypeModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        return view('layouts.lecturer.dashboard.index');
    }

    public function countProgramMbkm($status)
    {
        return MbkmProgramModel::where('status', $status)
            ->where('lecturer_id', Auth::user()->id)
            ->count() ?? 0;
    }

    private function viewLatestPrograms()
    {
        $latestPrograms = MbkmProgramModel::with(['lecturer:id,username', 'student:id,username', 'submissionType'])
            ->where('lecturer_id', Auth::user()->id)
            ->latest()->take(5)->get();
        return $latestPrograms;
    }

    private function countUsers($role)
    {
        return User::where('lecturer_id', Auth::user()->id)

            ->where('role', $role)->count() ?? 0;
    }

    public function getDashboardData()
    {
        $dashboardData = [
            [
                'title' => 'Total Students',
                'value' => $this->countUsers(4),
                'icon' => 'far fa-user',
                'color' => 'primary',
            ],
            [
                'title' => 'Program MBKM Accepted',
                'value' => $this->countProgramMbkm(2),
                'icon' => 'far fa-file',
                'color' => 'success',
            ],
            [
                'title' => 'Program MBKM Pending',
                'value' => $this->countProgramMbkm(1),
                'icon' => 'fas fa-file',
                'color' => 'warning',
            ],
            [
                'title' => 'Program MBKM Rejected',
                'value' => $this->countProgramMbkm(3),
                'icon' => 'fas fa-file',
                'color' => 'danger',
            ],
        ];

        $data['data'] = [
            'dashboardData' => $dashboardData,
            'latestPrograms' => $this->viewLatestPrograms(),
        ];

        return response()->json($data);
    }
}
