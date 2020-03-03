<?php

namespace App\Http\Controllers;

use App\Repository\CountryRepository;
use App\Service\CitiesService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CountriesController extends Controller
{
    /** @var CountryRepository */
    private $countryRepository;

    /** @var CitiesService */
    private $citiesService;

    public function __construct(CountryRepository $countryRepository, CitiesService $citiesService)
    {
        $this->countryRepository = $countryRepository;
        $this->citiesService = $citiesService;
    }

    public function index(): Response
    {
        $countries = $this->countryRepository->getAllCountries();

        return \response()->view('countries', with(compact('countries')));
    }

    public function show($name): Response
    {
        $country = $this->countryRepository->getCountryByName($name);

        return response()->view('country-details', compact('country'));
    }

    public function cities($name, Request $request): Response
    {
        $cities = $this->citiesService->getCitiesForCountry($name, $request->all());
        $cities->appends($request->query());

        return response()->view('cities', compact('cities'));
    }
}
