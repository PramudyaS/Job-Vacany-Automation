<?php

namespace App\Http\Controllers;

use App\Domain\Jobs\Models\{Job, JobMatch, JobMatchStatus};
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $matches = JobMatch::with(['job','job.source'])->where('candidate_profile_id', 1)
            ->when($request->filled('min_score'), fn ($q) => $q->where('total_score', '>=', $request->integer('min_score')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('work_type'), fn ($q) => $q->whereHas('job', fn ($j) => $j->where('work_type', $request->string('work_type'))))
            ->when($request->filled('location'), fn ($q) => $q->whereHas('job', fn ($j) => $j->where('location', 'like', '%'.$request->string('location').'%')))
            ->orderByDesc('total_score')
            ->orderByDesc(Job::select('published_at')->whereColumn('vacancy_jobs.id', 'job_matches.job_id'))
            ->paginate(10)->withQueryString();
        return view('jobs.index', compact('matches'));
    }
    public function status(Request $request, JobMatch $match)
    {
        $request->validate(['status' => ['required','in:'.implode(',', array_column(JobMatchStatus::cases(), 'value'))]]);
        $match->update(['status' => $request->string('status')]);
        return back()->with('success', 'Job status updated.');
    }
}
