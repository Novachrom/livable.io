<?php

namespace App\Console\Commands;

use App\Service\CountriesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FillOedcBliData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'country:fill_oecd_bli';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills bli data from oecd api for each country';

    /** @var CountriesService */
    private $countriesService;

    /**
     * Create a new command instance.
     *
     * @param CountriesService $countriesService
     */
    public function __construct(CountriesService $countriesService)
    {
        parent::__construct();
        $this->countriesService = $countriesService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->countriesService->fillOecdBliData();
            $this->info("Success");
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->error("Error: ".$exception->getTraceAsString());
        }
    }
}
