<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DREController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormulaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\CheckPointController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\WorkingDayController;
use App\Http\Controllers\EmailConfigController;
use App\Http\Controllers\EmployeeLogController;
use App\Http\Controllers\TemplateEmailController;
use App\Http\Controllers\PointManagementController;
use App\Http\Controllers\AccountingConfigController;
use App\Http\Controllers\AccountingControlController;
use App\Http\Controllers\AccountingAnalyticsController;
use App\Http\Controllers\TotalStaticCheckPointController;
use App\Http\Controllers\AccountingClassificationController;

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

    /** employees */
    Route::resource('colacoradores', EmployeeController::class, [
        'names' => 'employees'])->parameters([
        'colacoradores' => 'employee'
    ]);

    Route::prefix('colacoradores')->name('employees.')->group(function(){
        Route::post('/filter', [EmployeeController::class, 'filter'])->name('filter');
        Route::post('/log/{employee_id}/{name}', [EmployeeLogController::class, 'listLog'])->name('log');
        Route::post('/import', [EmployeeController::class, 'import'])->name('import');
    });

    /** DIRECTION */
    Route::resource('diretorias', DirectionController::class, [
        'names' => 'directions'])->parameters([
        'diretorias' => 'direction'
    ]);

    Route::prefix('diretorias')->name('directions.')->group(function(){
        Route::post('/filter', [DirectionController::class, 'filter'])->name('filter');
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

    /** DRE */
    Route::prefix('dre')->name('dre.')->group(function(){
        Route::get('/', [DREController::class, 'index'])->name('index');
        Route::post('/create', [DREController::class, 'create'])->name('create');
        Route::post('/total', [DREController::class, 'total'])->name('total');
        Route::post('/nsr', [DREController::class, 'totalNSR'])->name('nsr');
        Route::post('/rl', [DREController::class, 'totalRL'])->name('rl');
        Route::post('/amount', [DREController::class, 'amount'])->name('amount');
        Route::delete('/destroy/{dre}', [DREController::class, 'destroy'])->name('destroy');
    });

    /** Check Point */
    Route::prefix('controle-de-ponto')->name('check-points.')->group(function(){

        Route::post('/filter', [CheckPointController::class, 'filter'])->name('filter');
        Route::get('/', [CheckPointController::class, 'index'])->name('index');
        Route::get('/admin', [CheckPointController::class, 'admin'])->name('admin');
        Route::get('/create', [CheckPointController::class, 'create'])->name('create');
        Route::get('/show/{user_id}', [CheckPointController::class, 'show'])->name('show');
        Route::get('/{check_point}/edit', [CheckPointController::class, 'edit'])->name('edit');
        Route::post('/store', [CheckPointController::class, 'store'])->name('store');
        Route::delete('/destroy/{check_point}', [CheckPointController::class, 'destroy'])->name('destroy');
        Route::post('/acao/{month}/{year}', [PointManagementController::class, 'action'])->name('action');

        /** ACTIVITYS */
        Route::resource('atividades', ActivityController::class, [
            'names' => 'activities'])->parameters([
            'atividades' => 'activity'
        ]);
        Route::prefix('atividades')->name('activities.')->group(function(){
            Route::post('/filter', [ActivityController::class, 'filter'])->name('filter');
        });

        /** Working Day */
        Route::resource('jornada-de-trabalho', WorkingDayController::class, [
            'names' => 'working-days'])->parameters([
            'jornada-de-trabalho' => 'working-day'
        ]);
        Route::prefix('jornada-de-trabalho')->name('working-days.')->group(function(){
            Route::post('/filter', [WorkingDayController::class, 'filter'])->name('filter');
        });

        /** STATIC */
        Route::prefix('total-horas-estatico')->name('total-static-check-point.')->group(function(){
        Route::get('/index', [TotalStaticCheckPointController::class, 'index'])->name('index');
        Route::post('/filter', [TotalStaticCheckPointController::class, 'filter'])->name('filter');
        Route::post('/import', [TotalStaticCheckPointController::class, 'import'])->name('import');
        Route::post('/update/{id}', [TotalStaticCheckPointController::class, 'update'])->name('update');
        });
    });

    /** Configs */
    Route::prefix('configuracoes')->name('accounting-configs.')->group(function(){
        Route::get('/', [AccountingConfigController::class, 'index'])->name('index');
        Route::post('/store', [AccountingConfigController::class, 'store'])->name('store');
        Route::post('/duplicate', [AccountingConfigController::class, 'duplicate'])->name('duplicate');
        Route::post('/filter', [AccountingConfigController::class, 'filter'])->name('filter');
        Route::post('/import-formula/{year}/{month}', [AccountingConfigController::class, 'importFormula'])->name('import-formula');
        Route::post('/add-classificacao/{year}/{month}', [AccountingConfigController::class, 'addClassification'])->name('add-Classification');
        Route::post('/add-formula/{year}/{month}', [AccountingConfigController::class, 'addFormula'])->name('add-formula');
        Route::delete('/delete-classificacao/{classification}/{config}', [AccountingConfigController::class, 'deleteClassification'])->name('delete-classification');
        Route::delete('/delete-classificacao/{config}', [AccountingConfigController::class, 'deleteClassifications'])->name('delete-classifications');
        Route::delete('/delete-formula/{formula}/{config}', [AccountingConfigController::class, 'deleteFormula'])->name('delete-formula');
        Route::delete('/delete-formula/{config}', [AccountingConfigController::class, 'deleteFormulas'])->name('delete-formulas');
    });
});


