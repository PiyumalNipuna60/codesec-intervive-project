<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Quotation::query();

        if ($user->role === 'agent') {
            $query->whereHas('itinerary.enquiry', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
        }

        return $query->paginate(15);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'itinerary_id' => 'required|exists:itineraries,id',
            'title' => 'required|string|max:255',
            'price_per_person' => 'required|numeric|min:0',
            'currency' => 'sometimes|string|size:3',
            'notes' => 'nullable|string',
            'is_final' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $itinerary = Itinerary::findOrFail($request->itinerary_id);
        $user = $request->user();

        // Check if user is authorized (admin or assigned agent)
        if (!$user->isAdmin() && $itinerary->enquiry->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quotation = Quotation::create($request->all());

        return response()->json($quotation, 201);
    }

    public function show($id)
    {
        $quotation = Quotation::findOrFail($id);
        $user = request()->user();

        // Check if user is authorized (admin or assigned agent)
        if (!$user->isAdmin() && $quotation->itinerary->enquiry->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($quotation);
    }

    public function publicShow($uniqueId)
    {
        $quotation = Quotation::where('unique_id', $uniqueId)->firstOrFail();
        return response()->json($quotation);
    }
}
