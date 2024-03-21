<?php

namespace App\Services;

use App\Repositories\ParsingDataTypeRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use function PHPUnit\Framework\isEmpty;

class ParsingDataTypeService
{
    private ParsingDataTypeRepository $repository;

    public function __construct(ParsingDataTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $data
     * @param string $type
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getParsingData(array $data, string $type)
    {
        if (self::type($type)) {
            return $this->mapParsData($this->repository->index($type), $data);
        }

        throw new Exception('undefined type');
    }

    /**
     * @param array $data
     * @param string $type
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getNewParsingData(array $data, string $type)
    {
        if (self::type($type)) {
            if (count($this->repository->index($type))) {
                return $this->mapNewParsData($this->repository->index($type), $data);
            }
            return [];
        }

        throw new Exception('undefined type');
    }

    /**
     * @param Collection $collection
     * @param array $data
     * @return array|Collection|\Illuminate\Support\Collection
     * @throws Exception
     */
    public function mapNewParsData(Collection $collection, array $data)
    {
        if (isset($data['title']) && !empty($data['title'])) {
            if (isset($data['month']) && !empty($data['month'])) {
                $getTitle = $this->getNewTitleMonthData($collection, $data['title'], $data['month']);

                return $getTitle ?? [];
            }
        }
        return [];
    }


    /**
     * @param Collection $collection
     * @param array $data
     * @return array|Collection|\Illuminate\Support\Collection
     * @throws Exception
     */
    public function mapParsData(Collection $collection, array $data)
    {
        if (isset($data['title']) && !empty($data['title'])) {
            if (isset($data['month']) && !empty($data['month'])) {
                $getTitle = $this->getTitleMonthData($collection, $data['title'], $data['month']);

                if ($getTitle) {
                    return $getTitle;
                } else {
                    throw new Exception('month не найден', 500);
                }
            }

            return $this->getTitleMonth($collection, $data['title']);
        } else {
            return $this->getTitle($collection);
        }
    }

    /**
     * @param Collection $collection
     * @return Collection|\Illuminate\Support\Collection
     * @throws Exception
     */
    public function getTitle(Collection $collection)
    {
        try {

            return $collection->map(function ($item) {

                $toChart = [];

                foreach (json_decode($item['months']) as $month) {

                    $chartKey = '';

                    foreach ($month->table_titles as $titleKey => $tableTitle) {

                        if ($tableTitle == 'Производство (1000 МТ)') {
                            $chartKey = $titleKey;
                        }
                    }

                    foreach ($month->table_data as $titleDataKey => $tableData) {

                        if (empty($tableData) || count($tableData) < 14) {
                            continue;
                        }

                        if (!empty($chartKey)) {

                            if ($titleDataKey == 0) {
                                $toChart[] = [
                                    'month' => $month->title,
                                    'value' => $tableData[$chartKey]
                                ];

                            } else {
                                break;
                            }
                        }
                    }
                }

                return [
                    'title' => $item['title'],
                    'value' => $toChart
                ];
            });

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }


    /**
     * @param Collection $collection
     * @param string $titleData
     * @return array
     * @throws Exception
     */
    public function getTitleMonth(Collection $collection, string $titleData): array
    {
        $data = [];

        try {

            $collection = $collection->where('title', $titleData);

            if (empty($collection->toArray())) {
                throw new Exception('title не найден', 500);
            }

            $collection->map(function ($item) use (&$data) {

                $chartKey = '';
                $toChart = null;

                foreach (json_decode($item['months']) as $month) {

                    if (empty($month->table_data)) {
                        return $data;
                    }

                    foreach ($month->table_titles as $titleKey => $tableTitle) {

                        if ($tableTitle == 'Производство (1000 МТ)') {
                            $chartKey = $titleKey;
                        }
                    }

                    foreach ($month->table_data as $titleDataKey => $tableData) {

                        if (empty($tableData) || count($tableData) < 14) {
                            continue;
                        }

                        if (!empty($chartKey)) {

                            if ($titleDataKey == 0) {
                                $toChart = $tableData[$chartKey];

                            } else {
                                break;
                            }
                        }
                    }

                    $data[] = [
                        'title' => $month->title,
                        'value' => $toChart
                    ];
                }

            });

            return $data;

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    /**
     * @param Collection $collection
     * @param string $titleData
     * @param string $monthData
     * @return array
     * @throws Exception
     */
    public function getTitleMonthData(Collection $collection, string $titleData, string $monthData): array
    {
        $data = [];

        try {
            $collection = $collection->where('title', $titleData);

            if (empty($collection->toArray())) {
                throw new Exception('title не найден', 500);
            }

            $collection->map(function ($item) use (&$data, $monthData) {
                foreach (json_decode($item['months']) as $month) {
                    if ($monthData == $month->title) {
                        foreach ($month->table_titles as $titleKey => $tableTitle) {
                            if ($titleKey == 0) {
                                continue;
                            }
                            $dataKey[$titleKey] = $tableTitle;
                        }

                        foreach ($month->table_data as $titleDataKey => $tableData) {
                            if (empty($tableData) || count($tableData) < 14) {
                                continue;
                            }

                            $data[$titleDataKey]['parent_title'] = $tableData[0];

                            foreach ($tableData as $ttKey => $tableDatum) {
                                if ($ttKey > 0) {
                                    $data[$titleDataKey]['items'][] = [
                                        'title' => $dataKey[$ttKey],
                                        'value' => $tableDatum,
                                    ];
                                }
                            }
                        }
                    }
                }
            })->toArray();

            return array_values($data);

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    /**
     * @param Collection $collection
     * @param string $titleData
     * @param string $monthData
     * @return array
     * @throws Exception
     */
    public function getNewTitleMonthData(Collection $collection, string $titleData, string $monthData): array
    {
        $data = [];

        try {
            $collection = $collection->where('title', $titleData);

            if (empty($collection->toArray())) {
                return [];
            }

            $collection->map(function ($item) use (&$data, $monthData) {
                foreach (json_decode($item['months']) as $month) {
                    if ($monthData == $month->title) {
                        foreach ($month->table_titles as $titleKey => $tableTitle) {
                            if ($titleKey == 0) {
                                continue;
                            }
                            $dataKey[$titleKey] = $tableTitle;
                        }

                        foreach ($month->table_data as $titleDataKey => $tableData) {
                            if (empty($tableData) || count($tableData) < 14) {
                                continue;
                            }

                            $data[$titleDataKey]['parent_title'] = $tableData[0];

                            foreach ($tableData as $ttKey => $tableDatum) {
                                if ($ttKey > 0) {
                                    $data[$titleDataKey]['items'][] = [
                                        'title' => $dataKey[$ttKey],
                                        'value' => $tableDatum,
                                    ];
                                }
                            }
                        }
                    }
                }
            })->toArray();

            return array_values($data);

        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function getParsingDataTable(string $type)
    {
        if (self::type($type)) {
            return $this->mapParsDataTable($this->repository->index($type));
        }

        throw new Exception('undefined type');
    }

    public function mapParsDataTable(Collection $collection)
    {
        return $collection->map(function ($item) {
            return [
                'title' => $item['title'],
                'table' => json_decode($item['months'], true)
            ];
        });
    }

    /**
     * @param array $data
     * @param string $type
     * @return bool
     * @throws Exception
     */
    public function addParsingData(array $data, string $type): bool
    {
        if (self::type($type)) {
            foreach ($data as $item) {
                $this->repository->create([
                    'title' => $item['title'],
                    'months' => json_encode($item['months']),
                    'type' => $type
                ]);
            }

            return true;
        }

        throw new Exception('undefined type');
    }

    public function addParsingChartData(array $data, string $type): bool
    {
        if (self::type($type)) {
            foreach ($data as $item) {
                $this->repository->create([
                    'title' => $item['title'],
                    'months' => json_encode($item['months']),
                    'type' => $type
                ]);
            }

            return true;
        }

        throw new Exception('undefined type');
    }

    /**
     * @param string $data
     * @return bool
     */
    public function type(string $data): bool
    {
        if (!empty($data)) {
            return true;
        }

        return false;
    }
}
