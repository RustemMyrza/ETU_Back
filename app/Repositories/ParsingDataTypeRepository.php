<?php

namespace App\Repositories;

use App\Models\ParsingDataType;

class ParsingDataTypeRepository
{
    private ParsingDataType $model;

    public function __construct(ParsingDataType $model)
    {
        $this->model = $model;
    }

    public function index(string $type)
    {
        try {
            return $this->model->query()
                ->where('type', $type)
                ->latest()
                ->get()
                ->unique('title');
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
