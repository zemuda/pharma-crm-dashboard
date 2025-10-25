<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Core\Services\MeasurementUnitService;

class MeasurementUnitController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'units' => MeasurementUnitService::all()->values(),
            'categories' => [
                'weight' => MeasurementUnitService::getWeightUnits()->values(),
                'volume' => MeasurementUnitService::getVolumeUnits()->values(),
                'units' => MeasurementUnitService::getUnitTypes()->values(),
                'concentration' => MeasurementUnitService::getConcentrationUnits()->values(),
            ]
        ]);
    }

    public function convert(Request $request): JsonResponse
    {
        $request->validate([
            'value' => 'required|numeric',
            'from_unit' => 'required|string',
            'to_unit' => 'required|string',
            'precision' => 'sometimes|integer|min:0|max:6',
        ]);

        try {
            $converted = MeasurementUnitService::convertWithPrecision(
                $request->value,
                $request->from_unit,
                $request->to_unit,
                $request->precision
            );

            return response()->json([
                'original' => $request->value . ' ' . $request->from_unit,
                'converted' => $converted . ' ' . $request->to_unit,
                'formatted' => MeasurementUnitService::formatValue($converted, $request->to_unit, $request->precision),
                'conversion_valid' => true,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'conversion_valid' => false,
            ], 422);
        }
    }

    public function checkCompatibility(Request $request): JsonResponse
    {
        $request->validate([
            'from_unit' => 'required|string',
            'to_unit' => 'required|string',
        ]);

        $compatible = MeasurementUnitService::validateConversion(
            $request->from_unit,
            $request->to_unit
        );

        return response()->json([
            'from_unit' => $request->from_unit,
            'to_unit' => $request->to_unit,
            'compatible' => $compatible,
        ]);
    }
}
