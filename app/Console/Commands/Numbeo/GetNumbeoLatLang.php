<?php

namespace App\Console\Commands\Numbeo;

use App\Service\CitiesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetNumbeoLatLang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'numbeo:get_lat_lang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill geo data for each city';

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
            $this->citiesService->importNumbeoLatLang();
            $this->info("Success");
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->error("Error filling cities from numbeo: ".$exception->getMessage());
        }
    }
}
