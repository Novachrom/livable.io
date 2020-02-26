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

    public function index(): Response
    {
        $cities = $this->citiesService->getCititesPaginated();

        return response()->view('cities', compact('cities'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $cities = $this->citiesService->searchCities($query);
        $cities->appends(['q' => $query]);

        return response()->view('cities', compact('cities'));
    }
}
