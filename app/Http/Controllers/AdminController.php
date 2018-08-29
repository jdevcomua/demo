<?php

namespace App\Http\Controllers;

use App\Models\AnimalsRequest;
use Illuminate\Support\Facades\View;


class AdminController extends Controller
{
    public function __construct()
    {
        if (AnimalsRequest::where('processed', 0)->count()) {
            View::share('hasNewRequests', 1);
        }
    }
}
