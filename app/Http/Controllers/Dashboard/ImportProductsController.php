<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\ImportProducts;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;

class ImportProductsController extends Controller
{
    use Dispatchable;
    public function create()
    {
        return view('dashboard.products.import');
    }
    public function store(Request $request)
    {  // if we do that without queue it will take time to finish the request

        $job = new ImportProducts($request->post('count'));
        $job->onQueue('import')->delay(now()->addSeconds(5));
        dispatch($job);

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Import is runing...');

    }
}
