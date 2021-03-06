<?php

namespace App\Http\Controllers;

use App\Experience;
use App\FileIndustry;
use App\FileStudent;
use App\Guidance;
use App\GuidanceStudent;
use App\Internship;
use App\Journal;
use App\Portfolio;
use App\User;
use App\Vacancy;
use App\VacancyApplicant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuidanceStudentController extends Controller
{
    public function index(Guidance $guidance)
    {

    }

    public function create(Guidance $guidance)
    {
        $data = $guidance;
        // $students = User::role('siswa')->whereDoesntHave('guidance_student')->get();

        return view('guidance.gscreate', compact('data'));
    }

    public function store(Request $request, Guidance $guidance)
    {
        DB::beginTransaction();
        try {
            foreach($request->students as $student){
                GuidanceStudent::create([
                    'guidance_id' => $guidance->id,
                    'student_id' => $student,
                ]);
            }
            
            DB::commit();
            flash('Berhasil menambahkan siswa')->success();
    
            return redirect()->route('guidance.show', [$guidance->slug]);
        } catch (Exception $e) {
            DB::rollback();
            flash('Pilih siswa terlebih dahulu')->error();

            return redirect()->route('guidance_s.create', [$guidance->slug]);
        }
    }

    public function studentProfile(Guidance $guidance, $id)
    {
        $data = GuidanceStudent::find($id);
        $experience = Experience::where('user_id', $data->student->id)->get();
        $portfolio = Portfolio::where('user_id', $data->student->id)->get();

        return view('guidance.showstd', compact('data', 'experience', 'portfolio'));
    }

    public function industryProfile(Guidance $guidance, $id)
    {
        $gs = GuidanceStudent::find($id)->student->vapplicant;
        $data = null;

        foreach($gs as $value){
            if($value->status == 'approved' && $value->vacancy->started_internship == 'yes'){
                $data = VacancyApplicant::find($value->id);
            }
        }

        if($data && isset($data->biography_id)){
            return view('guidance.showids', compact('data'));
        } else {
            $industry = Internship::where('student_id', GuidanceStudent::find($id)->student->id)->latest()->get()[0];
            return view('guidance.showidscustom', compact('industry'));
        }
    }

    public function studentJournal(Guidance $guidance, $id, $vacancy)
    {
        $user = User::find($id);
        $vacanc = Vacancy::find($vacancy)->biography;
        $industry = null;
        if(isset($vacanc)){
            $industry = User::find(Vacancy::find($vacancy)->biography->user->id);
        }else {
            $industry = Internship::where('student_id', $id)->latest()->get()[0];
        }
        $journal = Journal::where([
                'student_id' => $id,
                'vacancy_id' => $vacancy
            ])->get();
        $sfile = FileStudent::where([
            'student_id' => $id,
            'vacancy_id' => $vacancy
        ])->get();
        $ifile = FileIndustry::where([
            'student_id' => $id,
            'vacancy_id' => $vacancy
        ])->get();

        return view('guidance.showjrnl', compact('user', 'journal', 'sfile', 'ifile', 'industry', 'vacanc'));
    }

    public function studentFile(Guidance $guidance, $id, $vacancy)
    {
        $user = User::find($id);
        $data = FileStudent::where([
                'student_id' => $id,
                'vacancy_id' => $vacancy
            ])->get();

        return view('guidance.showsfile', compact('user', 'data'));
    }

    public function industryFile(Guidance $guidance, $id, $vacancy)
    {
        $user = User::find(Vacancy::find($vacancy)->biography->user->id);
        $data = FileIndustry::where([
                'student_id' => $id,
                'vacancy_id' => $vacancy
            ])->get();

        return view('guidance.showifile', compact('user', 'data'));
    }

    public function destroy(Guidance $guidance, $id)
    {
        try {
            $gs = GuidanceStudent::find($id);
            $gs->delete();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus siswa',
                'url' => route('guidance.show', [$guidance->slug]),
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => route('guidance.show', [$guidance->slug]),
            ]);
        }
    }
}
