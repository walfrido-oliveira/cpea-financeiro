<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\CheckPoint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckPointController extends Controller
{
    /**
    * Display a listing of the user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkPoints =  CheckPoint::where("user_id", auth()->user()->id)->get();
        $activities = Activity::all()->pluck('name', 'id');
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'start';

        return view('check-points.index', compact('checkPoints', 'ascending', 'orderBy', 'activities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => ['exists:activities,id', 'nullable'],
            'project_id' => ['nullable', 'string', 'max:191'],
            'start' => ['date', 'required'],
            'end' => ['date', 'required'],
            'description' => ['required', 'string']
        ]);

        $input = $request->all();

        CheckPoint::create([
            'user_id' => auth()->user()->id,
            'activity_id' => $input['activity_id'] ? $input['activity_id'] : null ,
            'project_id' => $input['project_id'] ? $input['project_id'] : null,
            'start' => $input['start'],
            'end' => $input['end'],
            'description' => $input['description'],
        ]);

        return response()->json([
            'message' => __('Ponto Cadastrado com Sucesso!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Filter activity
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $checkPoints = CheckPoint::filter($request->all());
        $checkPoints = $checkPoints->setPath('');
        $orderBy = $request->get('order_by');
        $ascending = $request->get('ascending');
        $paginatePerPage = $request->get('paginate_per_page');

        return response()->json([
            'filter_result' => view('check-points.filter-result', compact('checkPoints', 'orderBy', 'ascending'))->render(),
            'pagination' => view('layouts.pagination', [
                'models' => $checkPoints,
                'order_by' => $orderBy,
                'ascending' => $ascending,
                'paginate_per_page' => $paginatePerPage,
                ])->render(),
            ]);
    }
}
