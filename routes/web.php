<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\JudgeAuthController;
use Illuminate\Support\Facades\Route;

// Admin Auth
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Judge Auth
Route::get('/judge/login/{contest_uuid?}', [JudgeAuthController::class, 'showLogin'])->name('judge.login');
Route::post('/judge/login/{contest_uuid?}', [JudgeAuthController::class, 'login'])->name('judge.login.post');
Route::post('/judge/logout', [JudgeAuthController::class, 'logout'])->name('judge.logout');
Route::get('/judge/entry/{code}', [\App\Http\Controllers\Judge\ScoringController::class, 'entry'])->name('judge.entry');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->group(function () {
        Route::get('/contests', [\App\Http\Controllers\Admin\ContestController::class, 'index'])->name('contests');
        Route::get('/contests/create', [\App\Http\Controllers\Admin\ContestController::class, 'create'])->name('contests.create');
        Route::post('/contests', [\App\Http\Controllers\Admin\ContestController::class, 'store'])->name('contests.store');
        Route::get('/contests/{contest}/settings', [\App\Http\Controllers\Admin\ContestController::class, 'settings'])->name('contests.settings');
        Route::patch('/contests/{contest}', [\App\Http\Controllers\Admin\ContestController::class, 'update'])->name('contests.update');
        Route::get('/exposures', [\App\Http\Controllers\Admin\ExposureController::class, 'index'])->name('exposures');
        Route::get('/contests/{contest}/exposures', [\App\Http\Controllers\Admin\ExposureController::class, 'show'])->name('contests.exposures');
        Route::post('/contests/{contest}/exposures', [\App\Http\Controllers\Admin\ExposureController::class, 'store'])->name('contests.exposures.store');
        Route::patch('/contests/{contest}/exposures/{exposure}/connection', [\App\Http\Controllers\Admin\ExposureController::class, 'reconnect'])->name('contests.exposures.reconnect');
        Route::patch('/contests/{contest}/exposures/{exposure}/swap', [\App\Http\Controllers\Admin\ExposureController::class, 'swap'])->name('contests.exposures.swap');
        Route::delete('/contests/{contest}/exposures/{exposure}/detach', [\App\Http\Controllers\Admin\ExposureController::class, 'detach'])->name('contests.exposures.detach');
        Route::patch('/contests/{contest}/exposures/reorder', [\App\Http\Controllers\Admin\ExposureController::class, 'reorder'])->name('contests.exposures.reorder');
        Route::patch('/exposures/{exposure}/status', [\App\Http\Controllers\Admin\ExposureController::class, 'updateStatus'])->name('exposures.status');
        Route::get('/criteria', [\App\Http\Controllers\Admin\CriteriaController::class, 'index'])->name('criteria');
        Route::post('/exposures/{exposure}/criteria', [\App\Http\Controllers\Admin\CriteriaController::class, 'store'])->name('criteria.store');
        Route::patch('/criteria/{criterion}', [\App\Http\Controllers\Admin\CriteriaController::class, 'update'])->name('criteria.update');
        Route::delete('/criteria/{criterion}', [\App\Http\Controllers\Admin\CriteriaController::class, 'destroy'])->name('criteria.destroy');
        Route::get('/judges', [\App\Http\Controllers\Admin\JudgeController::class, 'index'])->name('judges');
        Route::get('/contests/{contest}/judges', [\App\Http\Controllers\Admin\JudgeController::class, 'show'])->name('contests.judges');
        Route::post('/contests/{contest}/judges', [\App\Http\Controllers\Admin\JudgeController::class, 'store'])->name('judges.store');
        Route::patch('/judges/{judge}', [\App\Http\Controllers\Admin\JudgeController::class, 'update'])->name('judges.update');
        Route::delete('/judges/{judge}', [\App\Http\Controllers\Admin\JudgeController::class, 'destroy'])->name('judges.destroy');
        Route::post('/judges/{judge}/regenerate', [\App\Http\Controllers\Admin\JudgeController::class, 'regenerate'])->name('judges.regenerate');
        Route::get('/contests/{contest}/judges/print', [\App\Http\Controllers\Admin\JudgeController::class, 'print'])->name('judges.print');
        Route::get('/contestants', [\App\Http\Controllers\Admin\ContestantController::class, 'index'])->name('contestants');
        Route::get('/contests/{contest}/contestants', [\App\Http\Controllers\Admin\ContestantController::class, 'show'])->name('contests.contestants');
        Route::post('/contests/{contest}/contestants', [\App\Http\Controllers\Admin\ContestantController::class, 'store'])->name('contests.contestants.store');
        Route::patch('/contestants/{contestant}/performance', [\App\Http\Controllers\Admin\ContestantController::class, 'updatePerformance'])->name('contestants.performance.update');
        Route::post('/contests/{contest}/contestants/import', [\App\Http\Controllers\Admin\ContestantController::class, 'import'])->name('contests.contestants.import');
        Route::get('/tabulation', [\App\Http\Controllers\Admin\TabulationController::class, 'index'])->name('tabulation');
        Route::get('/contests/{contest}/tabulation', [\App\Http\Controllers\Admin\TabulationController::class, 'show'])->name('contests.tabulation');
        Route::post('/contests/{contest}/tabulation/unlock', [\App\Http\Controllers\Admin\TabulationController::class, 'unlockBallot'])->name('contests.tabulation.unlock');
        Route::get('/results', [\App\Http\Controllers\Admin\ResultsController::class, 'index'])->name('results');
        Route::get('/results/{contest}', [\App\Http\Controllers\Admin\ResultsController::class, 'show'])->name('results.show');
        Route::get('/results/{contest}/print', [\App\Http\Controllers\Admin\ResultsController::class, 'print'])->name('results.print');
        Route::get('/results/{contest}/scoresheet', [\App\Http\Controllers\Admin\ResultsController::class, 'scoresheet'])->name('results.scoresheet');
        Route::get('/results/{contest}/rankings', [\App\Http\Controllers\Admin\ResultsController::class, 'rankings'])->name('results.rankings');
        Route::get('/results/{contest}/export', [\App\Http\Controllers\Admin\ResultsController::class, 'export'])->name('results.export');
        Route::get('/leaderboard', [\App\Http\Controllers\Admin\LeaderboardController::class, 'index'])->name('leaderboard');
        Route::get('/accounts', [\App\Http\Controllers\Admin\AdminAccountController::class, 'index'])->name('admin.accounts');
        Route::post('/accounts', [\App\Http\Controllers\Admin\AdminAccountController::class, 'store'])->name('admin.accounts.store');
        Route::patch('/accounts/{admin}', [\App\Http\Controllers\Admin\AdminAccountController::class, 'update'])->name('admin.accounts.update');
        Route::delete('/accounts/{admin}', [\App\Http\Controllers\Admin\AdminAccountController::class, 'destroy'])->name('admin.accounts.destroy');
        Route::get('/settings', function () { return view('admin.settings'); })->name('settings');
        Route::get('/guide', function () { return view('admin.guide'); })->name('guide');
    });
});

Route::get('/judge/entry/{code}', [\App\Http\Controllers\Judge\ScoringController::class, 'entry'])->name('judge.entry');

Route::middleware('judge.auth')->prefix('judge')->group(function () {
    Route::get('/scoring', [\App\Http\Controllers\Judge\ScoringController::class, 'redirect'])->name('judge.scoring.root');
    Route::get('/scoring/{contest_uuid}/{judge_slug}', [\App\Http\Controllers\Judge\ScoringController::class, 'index'])->name('judge.scoring');
    Route::post('/scoring', [\App\Http\Controllers\Judge\ScoringController::class, 'submit'])->name('judge.scoring.submit');
});
