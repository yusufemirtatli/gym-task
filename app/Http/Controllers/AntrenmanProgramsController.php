<?php

namespace App\Http\Controllers;

use App\Models\athlete_programs;
use App\Models\training_programs;
use App\Models\workouts;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AntrenmanProgramsController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'coach_id'    => 'required|exists:users,id', // Gelen coach_id'nin users tablosunda olup olmadığını kontrol et
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after:start_date',
            'workouts'    => 'required|array|min:1',
            'workouts.*.name' => 'required|string|max:255',
            'workouts.*.duration' => 'required|integer|min:1',
            'workouts.*.calories_burn' => 'required|integer|min:1',
        ]);
        $user = \App\Models\User::find($request->coach_id);
        if ($user->role == "ANTRENOR"){
            try {
                DB::beginTransaction();

                $program = training_programs::create([
                    'coach_id'    => $request->coach_id,
                    'title'       => $request->title,
                    'description' => $request->description,
                    'start_date'  => $request->start_date,
                    'end_date'    => $request->end_date,
                ]);

                // Gelen workoutları ekleyelim
                foreach ($request->workouts as $workout) {
                    workouts::create([
                        'program_id'      => $program->id,
                        'name'            => $workout['name'],
                        'duration'        => $workout['duration'],
                        'calories_burn' => $workout['calories_burn'],
                    ]);
                }

                DB::commit();

                return response()->json([
                    'message' => 'Antrenman programı oluşturuldu!',
                    'program' => $program->load('workouts'),
                ], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Bir hata oluştu!', 'details' => $e->getMessage()], 500);
            }
        }
        else{
            return response()->json(['error' => 'Bu kullanıcı antrenor degil']);
        }
    }

    public function list(){
        $programs = training_programs::all();

        return response()->json(['programs' => $programs]);
    }

    public function get($id){
        $program = training_programs::find($id);

        if (!$program){
            return response()->json(['program' => 'Herhangi bir program bulunamadı']);
        }
        return response()->json(['program' => $program]);
    }

    public function update(Request $request,$id){
        $program = training_programs::find($id);

        if (!$program) {
            return response()->json(['error' => 'Program bulunamadı'], 404);
        }

        // 2. Validasyon
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'coach_id' => 'required|exists:users,id',
        ]);

        // 3. Güncelleme işlemi
        $program->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'coach_id' => $request->coach_id,
        ]);

        return response()->json([
            'message' => 'Antrenman programı başarıyla güncellendi',
            'program' => $program
        ]);
    }

    public function delete($id)
    {
        $program = training_programs::find($id);

        if (!$program) {
            return response()->json([
                'message' => 'Program bulunamadı.',
            ], 404);
        }

        $program->delete();

        return response()->json([
            'message' => 'Program başarıyla silindi.',
        ], 200);
    }

    public function assign(Request $request,$id){
        $request->validate([
            'sporcu_id' => 'required|exists:users,id',
        ]);
        $sporcu = \App\Models\User::find($request->sporcu_id);
        if ($sporcu->role == "SPORCU"){
            $data = new athlete_programs();
            $data->athlete_id = $request->sporcu_id;
            $data->program_id = $id;
            $data->save();
            return response()->json([
                'message' => 'Program sporcuya atandı',
            ]);
        }
        else{
            return response()->json([
                'error' => 'Seçtiğiniz kullanıcı sporcu değil',
            ]);
        }

    }
}
