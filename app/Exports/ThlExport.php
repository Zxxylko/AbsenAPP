<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class THLExport implements FromView
{
    protected $reports;
    protected $month;

    public function __construct($reports, $month)
    {
        $this->reports = $reports;
        $this->month = $month;
    }

    public function view(): View
    {
        return view('exports.thl-excel', [
            'reports' => $this->reports,
            'month' => $this->month
        ]);
    }
}
