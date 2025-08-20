<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SubmissionTypeModel;
use App\Http\Controllers\Controller;
use App\Models\MbkmProgramModel;

class DashboardController extends Controller
{
    
    public function countProgramMbkm($status){
        return MbkmProgramModel::where('status', $status)->count() ?? 0;
    }

    private function viewLatestPrograms(){
        $latestPrograms = MbkmProgramModel::with(['lecturer:id,username', 'student:id,username', 'submissionType'])
        ->latest()->take(5)->get();
        return $latestPrograms;
    }

    private function countUsers($role){
        return User::where('role', $role)->count() ?? 0;
    }
    public function viewDashboard(){
        return view('layouts.admin.dashboard.index');
    }

    public function getDashboardData(){
        $dashboardData = [
            [
                'title' => 'Total Students',
                'value' => $this->countUsers(4),
                'icon' => 'far fa-user',
                'color' => 'primary',
            ],
            [
                'title' => 'Total Lecturers',
                'value' => $this->countUsers(3),
                'icon' => 'far fa-user',
                'color' => 'info',
            ],
            [
                'title' => 'Total Submission Types',
                'value' => SubmissionTypeModel::count() ?? 0,
                'icon' => 'far fa-file',
                'color' => 'info',
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
