<?php

namespace App\Console\Commands\Numbeo;

use App\Service\CitiesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NumbeoFillCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'numbeo:fill_cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill cities from numbeo API';

    /** @var CitiesService */
    private $citiesService;

    /**
     * Create a new command instance.
     *
     * @param CitiesService $citiesService
     */
    public function __construct(CitiesService $citiesService)
    {
        parent::__construct();
        $this->citiesService = $citiesService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->citiesService->fillNumbeoCities();
            $this->info("Success");
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->error("Error filling cities from numbeo: ".$exception->getMessage());
        }
    }
}
