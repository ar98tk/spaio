<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Inertia\Inertia;
use Inertia\Response;

class MarketingController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Marketing/Home', [
            'plans' => Plan::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function pricing(): Response
    {
        return Inertia::render('Marketing/Pricing', [
            'plans' => Plan::query()->orderBy('sort_order')->get(),
        ]);
    }
}
