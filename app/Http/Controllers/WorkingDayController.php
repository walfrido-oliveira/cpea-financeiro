<?php

namespace App\Http\Controllers;

use App\Models\WorkingDay;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkingDayController extends Controller
{
    /**
     * Validate form
     *
     *@param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validating(Request $request)
    {
        $request->validate([
            'description' => ['required', 'string'],
            'day_of_the_week' => ['required', 'array'],
            'start' => ['required', 'date_format:H:i'],
            'end' => ['required', 'date_format:H:i'],
            'obs' => ['string', 'nullable']
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
        $workingDays =  WorkingDay::filter($request->all());
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'description';

        return view('check-points.working-days.index', compact('workingDays', 'ascending', 'orderBy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $daysOfTheWeek  = daysOfWeek();

        return view('check-points.working-days.create', compact('daysOfTheWeek'));
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
        if(!$input['day_of_the_week'][0]) array_shift($input['day_of_the_week']);

       WorkingDay::create([
            'description' => $input['description'],
            'day_of_the_week' => $input['day_of_the_week'],
            'start' => $input['start'],
            'end' => $input['end'],
            'obs' => $input['obs'],
        ]);

        $resp = [
            'message' => __('Atividade Cadastrada com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('check-points.working-days.index')->with($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workingDay = WorkingDay::findOrFail($id);
        return view('check-points.working-days.show', compact('workingDay'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $workingDay = WorkingDay::findOrFail($id);
        $daysOfTheWeek  = daysOfWeek();

        return view('check-points.working-days.edit', compact('workingDay', 'daysOfTheWeek'));
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
        $workingDay = WorkingDay::findOrFail($id);

        $this->validating($request);

        $input = $request->all();
        if(!$input['day_of_the_week'][0]) array_shift($input['day_of_the_week']);

        $workingDay->update([
            'description' => $input['description'],
            'day_of_the_week' => $input['day_of_the_week'],
            'start' => $input['start'],
            'end' => $input['end'],
            'obs' => $input['obs'],
        ]);

        $resp = [
            'message' => __('Atividade Atualizada com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('check-points.working-days.index')->with($resp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $workingDay = WorkingDay::findOrFail($id);

        $workingDay->delete();

        return response()->json([
            'message' => __('Atividade Apagada com Sucesso!!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Filter workingDay
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $workingDays = WorkingDay::filter($request->all());
        $workingDays = $workingDays->setPath('');
        $orderBy = $request->get('order_by');
        $ascending = $request->get('ascending');
        $paginatePerPage = $request->get('paginate_per_page');

        return response()->json([
            'filter_result' => view('check-points.working-days.filter-result', compact('workingDays', 'orderBy', 'ascending'))->render(),
            'pagination' => view('layouts.pagination', [
                'models' => $workingDays,
                'order_by' => $orderBy,
                'ascending' => $ascending,
                'paginate_per_page' => $paginatePerPage,
                ])->render(),
            ]);
    }
}
