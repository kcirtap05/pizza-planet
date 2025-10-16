<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Services\Menu\ToppingService;
use Illuminate\Http\Request;

class ToppingController extends Controller
{
    protected $service;

    public function __construct(ToppingService $service)
    {
        $this->service = $service;
    }

    public function grid($number_per_page = null) 
    {
        return $this->service->grid($number_per_page);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
