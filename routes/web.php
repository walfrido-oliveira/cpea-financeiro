<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnityController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\FormulaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReplaceController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\WithdrawalController;
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
use App\Http\Controllers\AccountingControlController;
use App\Http\Controllers\AnalysisParameterController;
use App\Http\Controllers\EnvironmentalAreaController;
use App\Http\Controllers\ParameterAnalysisController;
use App\Http\Controllers\PreparationMethodController;
use App\Http\Controllers\AnalysisResultFileController;
use App\Http\Controllers\ProjectPointMatrixController;
use App\Http\Controllers\AccountingAnalyticsController;
use App\Http\Controllers\CalculationVariableController;
use App\Http\Controllers\EnvironmentalAgencyController;
use App\Http\Controllers\PointIdentificationController;
use App\Http\Controllers\CalculationParameterController;
use App\Http\Controllers\GuidingParameterValueController;
use App\Http\Controllers\ParameterAnalysisGroupController;
use App\Http\Controllers\AccountingClassificationController;
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

    /** PRODUCT */
    Route::resource('produtos', ProductController::class, [
        'names' => 'products'])->parameters([
        'produtos' => 'product'
    ]);

    Route::prefix('produtos')->name('products.')->group(function(){
        Route::post('/filter', [ProductController::class, 'filter'])->name('filter');
    });

    /** CUSTOMER */
    Route::resource('clientes', CustomerController::class, [
        'names' => 'customers'])->parameters([
        'clientes' => 'customer'
    ]);

    Route::prefix('clientes')->name('customers.')->group(function(){
        Route::post('/filter', [CustomerController::class, 'filter'])->name('filter');
    });

    /** Accounting Classification */
    Route::resource('classificacao-contabeis', AccountingClassificationController::class, [
        'names' => 'accounting-classifications'])->parameters([
        'classificacao-contabeis' => 'accounting-classification'
    ]);

    Route::prefix('classificacao-contabeis')->name('accounting-classifications.')->group(function(){
        Route::post('/filter', [AccountingClassificationController::class, 'filter'])->name('filter');
    });

    /** Accounting Control */
    Route::resource('controle-contabeis', AccountingControlController::class, [
        'names' => 'accounting-controls'])->parameters([
        'controle-contabeis' => 'accounting-control'
    ]);

    Route::prefix('controle-contabeis')->name('accounting-controls.')->group(function(){
        Route::post('/filter', [AccountingControlController::class, 'filter'])->name('filter');
        Route::post('/import', [AccountingControlController::class, 'import'])->name('import');
    });

    /** Accounting Analytics */
    Route::resource('analitico-contabil', AccountingAnalyticsController::class, [
        'names' => 'accounting-analytics'])->parameters([
        'analitico-contabil' => 'accounting-analytics'
    ]);

    Route::prefix('analitico-contabil')->name('accounting-analytics.')->group(function(){
        Route::post('/filter', [AccountingAnalyticsController::class, 'filter'])->name('filter');
    });

    /** Formula */
    Route::resource('formulas', FormulaController::class, [
        'names' => 'formulas'])->parameters([
        'formulas' => 'formula'
    ]);

    Route::prefix('formulas')->name('formulas.')->group(function(){
        Route::post('/filter', [FormulaController::class, 'filter'])->name('filter');
    });

    /** Withdrawal */
    Route::resource('retiradas', WithdrawalController::class, [
        'names' => 'withdrawals'])->parameters([
        'retiradas' => 'withdrawal'
    ]);

    Route::prefix('retiradas')->name('withdrawals.')->group(function(){
        Route::post('/filter', [WithdrawalController::class, 'filter'])->name('filter');
    });
});


