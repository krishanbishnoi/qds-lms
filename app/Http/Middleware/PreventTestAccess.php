<?php

namespace App\Http\Middleware;

use App\Model\Test;
use App\Model\TestParticipants;
use App\Model\TestResult;
use Closure;
use Auth;
use Illuminate\Http\Request;

class PreventTestAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
    {

        // $userId = Auth::id();
        // $testId = $request->route('id'); // Assuming 'id' is the route parameter for test ID

        // // Get the total number of attempts allowed for this test
        // $test = Test::find($testId);
        // if (!$test) {
        //     abort(404); // Test not found
        // }

        // $allowedAttempts = $test->number_of_attempts;

        // $testResult = TestResult::where('user_id', $userId)
        //     ->where('test_id', $testId)
        //     ->first();

        // $testSubmitted = TestParticipants::where('test_id', $testId)
        //     ->where('trainee_id', $userId)->first();
        // if ($testResult && $testResult->result == 'Passed' || $testSubmitted->status == 1 || $testSubmitted->user_attempts >= $allowedAttempts) {
        //     // User has exhausted their attempts, redirect to the test result page
        //     return redirect()->route('userTest.Submitted.ThanksPage');
        // }


        return $next($request);
    }





}
