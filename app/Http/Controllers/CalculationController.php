<?php

namespace App\Http\Controllers;

use App\DTO\CustomCalculation\Formula;
use App\Matex\Exception;
use App\Service\CustomCalculationsService;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    /** @var CustomCalculationsService */
    private $customCalculationsService;

    public function __construct(CustomCalculationsService $customCalculationsService)
    {
        $this->customCalculationsService = $customCalculationsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $calculations = $this->customCalculationsService->fetch();

        return response()->view('calculation.index', compact('calculations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $availableVariables = $this->customCalculationsService->getAvailableVariables();

        return response()->view('calculation.form', compact('availableVariables'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->customCalculationsService->create(Formula::fromRequest($request));

            return response()->redirectToRoute('calculations.index');
        } catch (Exception $e) {
            $errors = [$e->getMessage()];

            return redirect()->back()->withErrors($errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
