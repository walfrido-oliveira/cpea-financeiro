<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Activity;
use App\Models\CheckPoint;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CheckPointController extends Controller
{
    /**
    * Display a listing of the CheckPoint.
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
    * Display a listing of the CheckPointadmin.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function admin(Request $request)
    {
        $maxYear = CheckPoint::all()->max('start') ? CheckPoint::all()->max('start')->format('Y') : now()->format("y");
        $maxMonth = CheckPoint::all()->max('start') ? CheckPoint::all()->max('start')->format('m') : now()->format("m");

        $years = CheckPoint::whereYear('start', '>=', 2021)
        ->whereYear('start', '<=', 3000)
        ->select(DB::raw("DATE_FORMAT(start, '%Y') AS y"))
        ->pluck('y', 'y')
         ->all();

        if(!$years)  $years = [2022 => 2022];

        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'user_id';

        $checkPoints =  CheckPoint::whereMonth('start', $request->has('month') ? $request->get('month') : $maxMonth)
        ->whereYear('start', $request->has('year') ? $request->get('year') : $maxYear)
        ->groupBy("user_id")
        ->get();

        return view('check-points.admin', compact('checkPoints', 'ascending', 'orderBy', 'maxYear', 'maxMonth', 'years'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_id' => ['exists:activities,id', 'nullable'],
            'project_id' => ['nullable', 'string', 'max:191'],
            'start' => ['date', 'required'],
            'end' => ['date', 'required'],
            'description' => ['required', 'string']
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

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
