<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnityController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReplaceController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\EmailConfigController;
use App\Http\Controllers\GuidingValueController;
use App\Http\Controllers\AnalysisOrderController;
use App\Http\Controllers\TemplateEmailController;
use App\Http\Controllers\AnalysisMatrixController;
use App\Http\Controllers\AnalysisResultController;
use App\Http\Controllers\CampaignStatusController;
use App\Http\Controllers\GeodeticSystemController;
use App\Http\Controllers\SampleAnalysisController;
use App\Http\Controllers\ParameterMethodController;
use App\Http\Controllers\PlanActionLevelController;
use App\Http\Controllers\GuidingParameterController;
use App\Http\Controllers\AnalysisParameterController;
use App\Http\Controllers\EnvironmentalAreaController;
use App\Http\Controllers\ParameterAnalysisController;
use App\Http\Controllers\PreparationMethodController;
use App\Http\Controllers\AnalysisResultFileController;
use App\Http\Controllers\ProjectPointMatrixController;
use App\Http\Controllers\CalculationVariableController;
use App\Http\Controllers\EnvironmentalAgencyController;
use App\Http\Controllers\PointIdentificationController;
use App\Http\Controllers\CalculationParameterController;
use App\Http\Controllers\GuidingParameterValueController;
use App\Http\Controllers\ParameterAnalysisGroupController;
use App\Http\Controllers\GuidingParameterRefValueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/now', function () {
    dd(Carbon\Carbon::now());

})->name('now');

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function() {


    Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
        #return view('dashboard');
        return redirect()->route('users.index');
    })->name('dashboard');


    /** USERS */
    Route::resource('usuarios', UserController::class, [
        'names' => 'users'])->parameters([
        'usuarios' => 'user'
    ]);

    Route::prefix('usuarios')->name('users.')->group(function(){
        Route::post('/filter', [UserController::class, 'filter'])->name('filter');
        Route::post('/forgot-password/{user}', [UserController::class, 'forgotPassword'])->name('forgot-password');
    });

    Route::prefix('config')->name('config.')->group(function(){
        Route::prefix('emails')->name('emails.')->group(function(){
            Route::get('/', [EmailConfigController::class, 'index'])->name('index');
            Route::post('/store', [EmailConfigController::class, 'store'])->name('store');
            Route::resource('templates', TemplateEmailController::class);
            Route::get('templates/mail-preview/{template}', [TemplateEmailController::class, 'show'])->name("templates.mail-preview");
        });
    });

    /** DEPARTMENT */
    Route::resource('departamentos', DepartmentController::class, [
        'names' => 'departments'])->parameters([
        'departamentos' => 'department'
    ]);

    Route::prefix('departamentos')->name('departments.')->group(function(){
        Route::post('/filter', [DepartmentController::class, 'filter'])->name('filter');
    });

    /** OCCUPATION */
    Route::resource('cargos', OccupationController::class, [
        'names' => 'occupations'])->parameters([
        'cargos' => 'occupation'
    ]);

    Route::prefix('cargos')->name('occupations.')->group(function(){
        Route::post('/filter', [OccupationController::class, 'filter'])->name('filter');
    });
});


