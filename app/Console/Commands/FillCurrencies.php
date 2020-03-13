<?php

namespace App\Console\Commands;

use App\Service\CurrencyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FillCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches currencies';

    /** @var CurrencyService */
    private $currencyService;

    /**
     * Create a new command instance.
     *
     * @param CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        parent::__construct();
        $this->currencyService = $currencyService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->currencyService->fillCurrencyRates();
            $this->info("Success");
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->error("Error: ".$exception->getTraceAsString());
        }
    }
}
