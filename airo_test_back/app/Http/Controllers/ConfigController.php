<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Config;

class ConfigController extends Controller
{
    /**
     * Get the platform configuration, age ranges, load percentages and fixed term.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $config = Config::firstOrFail();

        return response()->json([
            'status' => 'success',
            'config' => $config
        ]);
    }
}