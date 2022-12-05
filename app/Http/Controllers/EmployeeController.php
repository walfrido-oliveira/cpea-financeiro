<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Direction;
use App\Models\Department;
use App\Models\Occupation;
use App\Models\WorkingDay;
use App\Models\WorkRegime;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\OccupationType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Validate form
     *
     *@param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validating(Request $request) {
        $request->validate([
            'occupation_id' => ['required', 'exists:occupations,id'],
            'direction_id' => ['required', 'exists:directions,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'manager_id' => ['required', 'exists:employees,id'],
            'occupation_type_id' => ['required', 'string', 'exists:occupation_types,id'],
            'employee_id' => ['required', 'string'],
            'admitted_at' => ['required', 'date'],
            'working_day_id' => ['required', 'exists:working_days,id'],
            'hour_cost' => ['nullable', 'numeric'],
            'month_cost' => ['nullable', 'numeric'],
            'work_regime_id' => ['nullable', 'exists:work_regimes,id'],
        ]);

    }

    /**
    * Display a listing of the user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employees =  Employee::filter($request->all());
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'employee_id';

        return view('employees.index', compact('employees', 'ascending', 'orderBy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $occupations  = Occupation::all()->pluck('name', 'id');
        $directions = Direction::all()->pluck('name', 'id');
        $departments = Department::all()->pluck('name', 'id');
        $employees = Employee::all()->pluck('name', 'id');
        $occupationTypes = OccupationType::all()->pluck('name', 'id');
        $workingDays = WorkingDay::all()->pluck('full_description', 'id');
        $workRegimes = WorkRegime::all()->pluck('name', 'id');

        return view('employees.create', compact('occupations', 'directions', 'departments', 'employees', 'occupationTypes', 'workingDays', 'workRegimes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validating($request);

        $input = $request->all();

       Employee::create([
            'occupation_id' => $input['occupation_id'],
            'direction_id' => $input['direction_id'],
            'department_id' => $input['department_id'],
            'name' => $input['name'],
            'manager_id' => $input['manager_id'],
            'occupation_type_id' => $input['occupation_type_id'],
            'employee_id' => $input['employee_id'],
            'admitted_at' => $input['admitted_at'],
            'working_day_id' => $input['working_day_id'],
            'hour_cost' => $input['hour_cost'],
            'month_cost' => $input['month_cost'],
            'work_regime_id' => $input['work_regime_id'],
        ]);

        $resp = [
            'message' => __('Colaborador Cadastrado com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('employees.index')->with($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $occupations  = Occupation::all()->pluck('name', 'id');
        $directions = Direction::all()->pluck('name', 'id');
        $departments = Department::all()->pluck('name', 'id');
        $employees = Employee::all()->pluck('name', 'id');
        $occupationTypes = OccupationType::all()->pluck('name', 'id');
        $workingDays = WorkingDay::all()->pluck('full_description', 'id');
        $workRegimes = WorkRegime::all()->pluck('name', 'id');

        return view('employees.edit', compact('employee', 'occupations', 'directions', 'departments', 'employees', 'occupationTypes', 'workingDays', 'workRegimes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $department = Employee::findOrFail($id);

        $this->validating($request);

        $input = $request->all();

        $department->update([
            'occupation_id' => $input['occupation_id'],
            'direction_id' => $input['direction_id'],
            'department_id' => $input['department_id'],
            'name' => $input['name'],
            'manager_id' => $input['manager_id'],
            'occupation_type_id' => $input['occupation_type_id'],
            'employee_id' => $input['employee_id'],
            'admitted_at' => $input['admitted_at'],
            'working_day_id' => $input['working_day_id'],
            'hour_cost' => $input['hour_cost'],
            'month_cost' => $input['month_cost'],
            'work_regime_id' => $input['work_regime_id'],
        ]);

        $resp = [
            'message' => __('Colaborador Atualizado com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('employees.index')->with($resp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Employee::findOrFail($id);

        $department->delete();

        return response()->json([
            'message' => __('Colaborador Apagado com Sucesso!!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Filter department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $employees = Employee::filter($request->all());
        $employees = $employees->setPath('');
        $orderBy = $request->get('order_by');
        $ascending = $request->get('ascending');
        $paginatePerPage = $request->get('paginate_per_page');

        return response()->json([
            'filter_result' => view('employees.filter-result', compact('employees', 'orderBy', 'ascending'))->render(),
            'pagination' => view('layouts.pagination', [
                'models' => $employees,
                'order_by' => $orderBy,
                'ascending' => $ascending,
                'paginate_per_page' => $paginatePerPage,
                ])->render(),
            ]);
    }


    /**
     * Import Resource
     *
     * * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:4096',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $inputs = $request->all();

        if($request->file)
        {

            $spreadsheet = IOFactory::load($request->file->path());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $notImport = [];
            $totalImported=0;

            foreach($rows as $key => $value)
            {
                if($key < 1) continue;

                $workRegime = WorkRegime::where('name', $value[0])->first();
                $departament = Department::where('name', $value[4])->first();
                $occupation = Occupation::where('name', $value[5])->first();
                $occupationType = OccupationType::where('name', $value[22])->first();

                if(!$workRegime)
                {
                    $workRegime = WorkRegime::create([
                        'name' => $value[0]
                    ]);
                }

                if(!$departament)
                {
                    Department::create([
                        'name' => $value[4]
                    ]);
                }

                if(!$occupation)
                {
                    $occupation = Occupation::create([
                        'name' => $value[5]
                    ]);
                }

                if(!$occupationType)
                {
                    $occupationType = OccupationType::create([
                        'name' => $value[22]
                    ]);
                }

                $date = explode("/", $value[1]);

                $validator = Validator::make([
                    'name' => $value[3],
                    'work_regime_id' => $workRegime ? $workRegime->id : null,
                    'admitted_at' => \Carbon\Carbon::create($date[2], $date[1], $date[0]),
                    'employee_id' => $value[2],
                    'department_id' => $departament ? $departament->id : null,
                    'occupation_id' => $occupation ? $occupation->id : null,
                    'month_cost' => Str::replace(",", "", $value[21]),
                    'occupation_type_id' => $occupationType ? $occupationType->id : null,
                    'hour_cost' => Str::replace(",", "", $value[24]),
                ],
                [
                    'name' => ['string', 'max:255', 'required'],
                    'occupation_id' => ['required', 'exists:occupations,id'],
                    'department_id' => ['required', 'exists:departments,id'],
                    'occupation_type_id' => ['required', 'exists:occupation_types,id'],
                    'employee_id' => ['required'],
                    'admitted_at' => ['required', 'date'],
                    'hour_cost' => ['nullable', 'numeric'],
                    'month_cost' => ['nullable', 'numeric'],
                    'work_regime_id' => ['nullable', 'exists:work_regimes,id'],
                ]);

                if ($validator->fails())
                {
                    return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
                }

                if (!$validator->fails())
                {
                    $employee = Employee::firstOrCreate([
                        'employee_id' => $value[2],
                    ]);
                   // dd(\Carbon\Carbon::create($date[2] * 100, $date[1], $date[0])->format("Y-m-d"));
                    $employee->update([
                        'name' => $value[3],
                        'work_regime_id' => $workRegime ? $workRegime->id : null,
                        'admitted_at' => \Carbon\Carbon::create($date[2] , $date[1], $date[0])->format("Y-m-d"),
                        'employee_id' => $value[2],
                        'department_id' => $departament ? $departament->id : null,
                        'occupation_id' => $occupation ? $occupation->id : null,
                        'month_cost' => Str::replace(",", "", $value[21]),
                        'occupation_type_id' => $occupationType ? $occupationType->id : null,
                        'hour_cost' => Str::replace(",", "", $value[24]),
                    ]);

                    $totalImported++;
                }else{
                  $notImport[] = $value[0];
                }
            }

            return response()->json([
                'message' => __('Arquivo importado com sucesso!'),
                'alert-type' => 'success',
                'not_imported' => $notImport,
                'total_imported' =>  $totalImported
            ]);
        }

        return response()->json([
            'message' => __('Arquivo não importado!'),
            'alert-type' => 'error'
        ]);
    }
}
