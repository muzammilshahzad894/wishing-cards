<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemplateZoneController extends Controller
{
    /**
     * Save zones for a template. Replaces all existing zones.
     * POST /admin/templates/{id}/zones
     */
    public function store(Request $request, Template $template): JsonResponse
    {
        $validated = $request->validate([
            'zones' => ['required', 'array'],
            'zones.*.type' => ['required', 'string', 'in:photo,text'],
            'zones.*.shape' => ['required', 'string', 'in:rect,circle,oval'],
            'zones.*.x' => ['required', 'numeric'],
            'zones.*.y' => ['required', 'numeric'],
            'zones.*.width' => ['required', 'numeric', 'min:1'],
            'zones.*.height' => ['required', 'numeric', 'min:1'],
            'zones.*.angle' => ['nullable', 'numeric'],
            'zones.*.scaleX' => ['nullable', 'numeric', 'min:0.01'],
            'zones.*.scaleY' => ['nullable', 'numeric', 'min:0.01'],
            'zones.*.meta' => ['nullable', 'array'],
        ]);

        $template->zones()->delete();

        foreach ($validated['zones'] as $zoneData) {
            $template->zones()->create([
                'type' => $zoneData['type'],
                'shape' => $zoneData['shape'],
                'x' => (float) $zoneData['x'],
                'y' => (float) $zoneData['y'],
                'width' => (float) $zoneData['width'],
                'height' => (float) $zoneData['height'],
                'angle' => isset($zoneData['angle']) ? (float) $zoneData['angle'] : 0,
                'scale_x' => isset($zoneData['scaleX']) ? (float) $zoneData['scaleX'] : 1,
                'scale_y' => isset($zoneData['scaleY']) ? (float) $zoneData['scaleY'] : 1,
                'meta' => $zoneData['meta'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Zones saved successfully.',
            'count' => $template->zones()->count(),
        ]);
    }
}
