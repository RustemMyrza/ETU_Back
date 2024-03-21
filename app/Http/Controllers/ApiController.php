<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HeaderNavBar;
use App\Http\Resources\HeaderNavBarResource;


use function Ramsey\Uuid\v1;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function headerNavBar ()
    {
        $headerNavBarData = HeaderNavBar::query()->with('getName')->get();
        $headerNavBarResourceData = HeaderNavBarResource::collection($headerNavBarData);
        return $headerNavBarResourceData;
    }
}
