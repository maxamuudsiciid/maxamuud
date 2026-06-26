<?php

namespace App\Http\Controllers;

use App\Models\BloodTest;
use App\Models\BloodCollection;
use Illuminate\Http\Request;

class BloodTestController extends Controller
{
    public function dashboard()
    {
        $totalTests = BloodTest::count();
        $positiveResults = BloodTest::where('hiv_result', 'Positive')
            ->orWhere('hbv_result', 'Positive')
            ->orWhere('hcv_result', 'Positive')
            ->orWhere('syphilis_result', 'Positive')
            ->count();
        $negativeResults = $totalTests - $positiveResults;
        $recentReports = BloodTest::with('bloodCollection.donor')->latest()->take(5)->get();

        return view('blood-tests.dashboard', compact('totalTests', 'positiveResults', 'negativeResults', 'recentReports'));
    }

    public function index(Request $request)
    {
        $query = BloodTest::with('bloodCollection.donor');

        if ($request->filled('date_from')) {
            $query->whereDate('test_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('test_date', '<=', $request->date_to);
        }
        
        if ($request->filled('test_type') && $request->filled('result')) {
            $query->where($request->test_type, $request->result);
        } elseif ($request->filled('overall_result')) {
            if ($request->overall_result == 'Safe') {
                $query->where('hiv_result', 'Negative')
                      ->where('hbv_result', 'Negative')
                      ->where('hcv_result', 'Negative')
                      ->where('syphilis_result', 'Negative');
            } else {
                $query->where(function($q) {
                    $q->where('hiv_result', 'Positive')
                      ->orWhere('hbv_result', 'Positive')
                      ->orWhere('hcv_result', 'Positive')
                      ->orWhere('syphilis_result', 'Positive');
                });
            }
        }

        $tests = $query->latest()->paginate(10)->withQueryString();
        return view('blood-tests.index', compact('tests'));
    }

    public function create()
    {
        // Get blood collections that don't have a test yet
        $collections = BloodCollection::doesntHave('bloodTest')->get();
        return view('blood-tests.create', compact('collections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'blood_collection_id' => 'required|exists:blood_collections,id|unique:blood_tests,blood_collection_id',
            'test_date' => 'required|date',
            'hiv_result' => 'required|in:Negative,Positive',
            'hbv_result' => 'required|in:Negative,Positive',
            'hcv_result' => 'required|in:Negative,Positive',
            'syphilis_result' => 'required|in:Negative,Positive',
            'notes' => 'nullable|string',
        ]);

        $test = BloodTest::create($request->all());

        // Automatically update the screening_result on BloodCollection
        $collection = BloodCollection::findOrFail($request->blood_collection_id);
        if ($test->hiv_result == 'Positive' || $test->hbv_result == 'Positive' || $test->hcv_result == 'Positive' || $test->syphilis_result == 'Positive') {
            $collection->screening_result = 'Unsafe';
        } else {
            $collection->screening_result = 'Safe';
        }
        $collection->save();

        return redirect()->route('blood-tests.index')->with('success', 'Blood test recorded successfully.');
    }

    public function show(BloodTest $blood_test)
    {
        return view('blood-tests.show', compact('blood_test'));
    }

    public function edit(BloodTest $blood_test)
    {
        return view('blood-tests.edit', compact('blood_test'));
    }

    public function update(Request $request, BloodTest $blood_test)
    {
        $request->validate([
            'test_date' => 'required|date',
            'hiv_result' => 'required|in:Negative,Positive',
            'hbv_result' => 'required|in:Negative,Positive',
            'hcv_result' => 'required|in:Negative,Positive',
            'syphilis_result' => 'required|in:Negative,Positive',
            'notes' => 'nullable|string',
        ]);

        $blood_test->update($request->all());

        // Update screening_result
        $collection = $blood_test->bloodCollection;
        if ($blood_test->hiv_result == 'Positive' || $blood_test->hbv_result == 'Positive' || $blood_test->hcv_result == 'Positive' || $blood_test->syphilis_result == 'Positive') {
            $collection->screening_result = 'Unsafe';
        } else {
            $collection->screening_result = 'Safe';
        }
        $collection->save();

        return redirect()->route('blood-tests.index')->with('success', 'Blood test updated successfully.');
    }

    public function destroy(BloodTest $blood_test)
    {
        $blood_test->delete();
        return redirect()->route('blood-tests.index')->with('success', 'Blood test deleted successfully.');
    }
}
