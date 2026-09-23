<?php

namespace App\Http\Controllers;

use App\Domain\Candidates\Models\CandidateProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit() { return view('profile.edit', ['profile' => CandidateProfile::with('skills')->firstOrCreate(['id'=>1], ['name'=>'My profile'])]); }

    public function update(Request $request)
    {
        $data = $request->validate(['name'=>'required','target_roles'=>'nullable|string','experience_years'=>'required|integer|min:0','preferred_locations'=>'nullable|string','work_types'=>'nullable|array','minimum_salary'=>'nullable|integer|min:0','exclude_keywords'=>'nullable|string','skills'=>'nullable|string']);
        $profile = CandidateProfile::findOrFail(1); $csv = fn ($value) => collect(explode(',', (string) $value))->map(fn ($v) => trim($v))->filter()->values()->all();
        $profile->update(['name'=>$data['name'],'target_roles'=>$csv($data['target_roles']??''),'experience_years'=>$data['experience_years'],'preferred_locations'=>$csv($data['preferred_locations']??''),'work_types'=>$data['work_types']??[],'minimum_salary'=>$data['minimum_salary']??null,'exclude_keywords'=>$csv($data['exclude_keywords']??'')]);
        $profile->skills()->delete(); foreach ($csv($data['skills']??'') as $skill) { [$name,$weight] = array_pad(array_map('trim', explode(':', $skill, 2)), 2, 1); $profile->skills()->create(['skill'=>$name,'weight'=>(int) $weight ?: 1]); }
        return redirect()->route('jobs.index')->with('success', 'Profile updated.');
    }
}
