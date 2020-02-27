<?php

namespace App\Http\Controllers;

use App\Service\CitiesService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CitiesController extends Controller
{
    /** @var CitiesService */
    private $citiesService;

    public function __construct(CitiesService $citiesService)
    {
        $this->citiesService = $citiesService;
    }

    public function index(Request $request): Response
    {
        $params = $request->all();
        $cities = $this->citiesService->getCititesPaginated($params);
        $cities->appends($request->query());

        return response()->view('cities', compact('cities'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', $request->get('\q', ''));
        if(empty($query)) {
            return redirect()->route('cities');
        }
        $cities = $this->citiesService->searchCities($query, $request->all());
        $cities->appends($request->query());

        return response()->view('cities', compact('cities'));
    }
}
