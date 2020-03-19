<?php

namespace App\Console\Commands;

use App\Service\CitiesService;
use App\Utils\EchoOutput;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportTeleportPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teleport:import-photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import city photos from teleport';


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
            $output = new EchoOutput();
            $this->citiesService->importCityPhotos($output);
            $this->info("Success");
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->error("Error: ".$exception->getTraceAsString());
        }
    }
}
