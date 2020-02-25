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

        return response()->view('index', compact('cities'));
    }
}
