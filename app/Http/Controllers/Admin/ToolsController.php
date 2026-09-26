<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\ItemsExport;
use App\Exports\TransactionsExport;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ToolsController extends Controller
{
    public function qrcode(): View
    {
        $url = URL::route('request.create');
        return view('admin.qrcode', ['url' => $url, 'qrCode' => QrCode::format('svg')->size(280)->margin(1)->generate($url)]);
    }

    public function exportItems(): Response
    {
        return Excel::download(new ItemsExport, 'items.xlsx');
    }

    public function exportTransactions(): Response
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }
}
