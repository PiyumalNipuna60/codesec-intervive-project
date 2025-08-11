<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItineraryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Itinerary::query();

        if ($user->role === 'agent') {
            $query->whereHas('enquiry', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
        }

        return $query->paginate(15);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enquiry_id' => 'required|exists:enquiries,id',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'days' => 'required|array',
            'days.*.day' => 'required|integer|min:1',
            'days.*.location' => 'required|string|max:255',
            'days.*.activities' => 'required|array',
            'days.*.activities.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $enquiry = Enquiry::findOrFail($request->enquiry_id);
        $user = $request->user();

        // Check if user is authorized (admin or assigned agent)
        if (!$user->isAdmin() && $enquiry->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate days are sequential and unique
        $days = collect($request->days);
        $uniqueDays = $days->pluck('day')->unique();
        $sequential = $uniqueDays->sort()->values()->toArray() === range(1, $uniqueDays->count());

        if ($uniqueDays->count() !== $days->count() || !$sequential) {
            return response()->json(['message' => 'Days must be unique and sequential'], 422);
        }

        $itinerary = Itinerary::create([
            'enquiry_id' => $request->enquiry_id,
            'title' => $request->title,
            'notes' => $request->notes,
            'days' => $request->days,
        ]);

        return response()->json($itinerary, 201);
    }

    public function show($id)
    {
        $itinerary = Itinerary::findOrFail($id);
        $user = request()->user();

        // Check if user is authorized (admin or assigned agent)
        if (!$user->isAdmin() && $itinerary->enquiry->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($itinerary);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'notes' => 'nullable|string',
            'days' => 'sometimes|required|array',
            'days.*.day' => 'required|integer|min:1',
            'days.*.location' => 'required|string|max:255',
            'days.*.activities' => 'required|array',
            'days.*.activities.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $itinerary = Itinerary::findOrFail($id);
        $user = $request->user();

        // Check if user is authorized (admin or assigned agent)
        if (!$user->isAdmin() && $itinerary->enquiry->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->has('days')) {
            // Validate days are sequential and unique
            $days = collect($request->days);
            $uniqueDays = $days->pluck('day')->unique();
            $sequential = $uniqueDays->sort()->values()->toArray() === range(1, $uniqueDays->count());

            if ($uniqueDays->count() !== $days->count() || !$sequential) {
                return response()->json(['message' => 'Days must be unique and sequential'], 422);
            }
        }

        $itinerary->update($request->all());

        return response()->json($itinerary);
    }

    public function destroy($id)
    {
        $itinerary = Itinerary::findOrFail($id);
        $user = request()->user();

        // Check if user is authorized (admin or assigned agent)
        if (!$user->isAdmin() && $itinerary->enquiry->assigned_to !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $itinerary->delete();

        return response()->json(['message' => 'Itinerary deleted successfully']);
    }
}
