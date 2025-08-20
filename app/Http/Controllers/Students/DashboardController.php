<?php

namespace App\Http\Controllers\Students;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MbkmProgramModel;
use App\Models\InformationProgram;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
       
        return view('layouts.student.dashboard.index');
    }

    public function getDashboardData()
    {
       

        return response()->json([
            'success' => true,
            'data' => [
                // 'student' => $student,
                'mbkm_program' => $this->getMbkmProgramForYou(),
                'welcome_text' => $this->welcomeText(),
                'lecturer' => $this->getLecturer(),
                'information' => $this->getInformationPrograms(),
            ],
        ]);
    }

    private function welcomeText(){
        return 'This is your central hub for all things related to your MBKM journey. Here, you’ll find the latest updates on your program, including logbook activity, participation status, and your assigned lecturer’s details. Don’t forget to update your logbook regularly.
         If you experience any issues or have questions, please reach out to your supervising lecturer for support.';
    }

    private function getInformationPrograms()
    {
        // This method can be used to fetch information programs if needed
        $programs = InformationProgram::with('creator')->take(3)->latest()->get()->map(function ($program) {
            return [
                'id' => $program->id,
                'title' => $program->title,
                'content' => $program->content,
                'created_by' => $program->creator->username ?? 'System',
                'updated_by' => $program->updated_by,
                'created_at' => $program->created_at->format('d M Y H:i:s'),
                'updated_at' => $program->updated_at ? $program->updated_at->format('d M Y') : null,
            ];
        });

        return $programs;
    }

    private function getLecturer()
    {
        // This method can be used to fetch lecturer data if needed
        $student = User::with([
            'lecturer:id,username,email',
            'lecturer.detail:id,user_id,nidn,phone'
        ])
            ->select('id', 'username', 'email', 'lecturer_id') // harus sertakan lecturer_id
            ->where('role', 4)
            ->where('id', Auth::id())
            ->first();

        $lecturer = $student?->lecturer;

        $data = $lecturer ? [
            'id' => $lecturer->id,
            'name' => $lecturer->username,
            'email' => $lecturer->email,
            'nidn' => $lecturer->detail->nidn ?? null,
            'phone' => $lecturer->detail->phone ?? null,
        ] : null;

        return $data;
    }

    private function getMbkmProgramForYou()
    {
        $programs = MbkmProgramModel::
        with([
            'lecturer:id,username',
            'lecturer.detail:id,user_id,nidn',
            'student:id,username',
            'student.detail:id,user_id,nidn',
            'submissionType:id,program_mbkm',
            'submissionPeriod:id,periode',
        ])
        ->where('student_id', Auth::id())
        ->take(1)->latest()->get()->map(function ($program) {
            return [
                'id' => $program->id,
                'title' => $program->title . ' - ' . $program->code,

                'submission_period_id' => $program->submissionPeriod->periode,
                'submission_type_id' => $program->submissionType->program_mbkm,
                'lecturer_id' => $program->lecturer->username,
                'reason' => $program->reason,
                'approval_status' => $program->validated_by == null ? 'Pending' : 'Accepted by ' . Str::upper($program->lecturer->username),
                'validated_at' => Carbon::parse($program->validated_at)->format('d M Y'),
            ];
        });

        return $programs;
    }
}
