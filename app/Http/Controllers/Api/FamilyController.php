<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FamilyResource;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index()
    {
        $family = new FamilyResource(Family::query()->get());

        return response()->json($family);
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
