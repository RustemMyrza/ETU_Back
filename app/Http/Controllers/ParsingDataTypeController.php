<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParsingDataIndexFormRequest;
use App\Http\Requests\ParsingDataTypeFormRequest;
use App\Services\ParsingChartDataTypeService;
use App\Services\ParsingDataTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParsingDataTypeController extends Controller
{
    private ParsingDataTypeService $service;
    private ParsingChartDataTypeService $chartService;

    public function __construct(ParsingDataTypeService $service, ParsingChartDataTypeService $chartService)
    {
        $this->service = $service;
        $this->chartService = $chartService;
    }

    /**
     * @param ParsingDataIndexFormRequest $request
     * @param $type
     * @return JsonResponse
     */
    public function index(ParsingDataIndexFormRequest $request, $type): JsonResponse
    {
        try {
            return $this->response(200, $this->service->getParsingData($request->all(), $type));
        } catch (\Exception $e) {
            return $this->response(500, [], $e->getMessage());
        }
    }

    public function newTypeChart(ParsingDataIndexFormRequest $request, $type)
    {
        try {
            return $this->response(200, [
                'table' => $this->service->getNewParsingData($request->all(), $type),
                'pieChart' => $this->chartService->getParsingPieChartDataTable($request->all(), $type),
                'barChart' => $this->chartService->getParsingBarChartDataTable($request->all(), $type)
            ], $type);
        } catch (\Exception $e) {
            return $this->response(500, [], $e->getMessage());
        }
    }


    public function typeChart(ParsingDataIndexFormRequest $request, $type)
    {
        try {
            return $this->response(200, [
                'data1' => $this->service->getParsingData($request->all(), $type),
                'data2' => $this->chartService->getParsingDataTable($type)
            ], $type);
        } catch (\Exception $e) {
            return $this->response(500, [], $e->getMessage());
        }
    }

    public function table($type): JsonResponse
    {
        try {
            return $this->response(200, $this->service->getParsingDataTable($type));
        } catch (\Exception $e) {
            return $this->response(500, [], $e->getMessage());
        }
    }

    /**
     * @param ParsingDataTypeFormRequest $request
     * @param $type
     * @return JsonResponse
     */
    public function store(ParsingDataTypeFormRequest $request, $type): JsonResponse
    {
        $requestData = $request->validated();

        try {
            return $this->response(200, $this->service->addParsingData($requestData, $type));
        } catch (\Exception $e) {
            return $this->response(500, [], $e->getMessage());
        }
    }

    public function chartStore(ParsingDataTypeFormRequest $request, $type): JsonResponse
    {
        $requestData = $request->validated();

        try {
            return $this->response(200, $this->chartService->addParsingChartData($requestData, $type));
        } catch (\Exception $e) {
            return $this->response(500, [], $e->getMessage());
        }
    }

    public function chart($type): JsonResponse
    {
        try {
            return $this->response(200, $this->chartService->getParsingDataTable($type));
        } catch (\Exception $e) {
            return $this->response(500, [], $e->getMessage());
        }
    }

    public function countries(): JsonResponse
    {
        try {
            return $this->response(200, $this->chartService->getCountriesDataTable());
        } catch (\Exception $e) {
            return $this->response(500, [], $e->getMessage());
        }
    }
}
