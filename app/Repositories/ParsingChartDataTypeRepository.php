<?php

namespace App\Repositories;

use App\Models\ParsingChartDataType;

class ParsingChartDataTypeRepository
{
    private ParsingChartDataType $model;

    public function __construct(ParsingChartDataType $model)
    {
        $this->model = $model;
    }

    /**
     * @throws \Exception
     */
    public function index(string $type)
    {
        try {
            return $this->model->query()
                ->where('type', $type)
                ->get();
        } catch (\Exception $exception) {
            $responseData = $exception->getMessage();
            throw new \Exception($responseData);
        }
    }

    /**
     * @throws \Exception
     */
    public function getAllTypes()
    {
        try {
            return $this->model->query()
                ->distinct()
                ->get(['type']);
        } catch (\Exception $exception) {
            $responseData = $exception->getMessage();
            throw new \Exception($responseData);
        }
    }

    /**
     * @param array $values
     * @return mixed
     * @throws \Exception
     */
    public function create(array $values = [])
    {
        try {
            return $this->model->create($values);
        } catch (\Exception $exception) {
            $responseData = $exception->getMessage();
            throw new \Exception($responseData);
        }
    }
}
