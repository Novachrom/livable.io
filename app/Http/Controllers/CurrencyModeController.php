<?php

namespace App\Http\Controllers;

use App\Service\CurrencyService;
use Illuminate\Http\Request;

class CurrencyModeController extends Controller
{
    /** @var CurrencyService */
    private $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function set(Request $request)
    {
        $mode = $request->get('currency_mode');
        if(!empty($mode)) {
            $this->currencyService->setCurrencyMode($mode);
        }

        return redirect()->back();
    }
}
