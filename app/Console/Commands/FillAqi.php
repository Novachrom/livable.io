<?php

namespace App\Console\Commands;

use App\Service\CitiesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FillAqi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqi:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills data from aqicn api for each city';

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
            $this->citiesService->fillAqi();
            $this->info("Success");
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->error("Error: ".$exception->getMessage());
        }
    }
}
