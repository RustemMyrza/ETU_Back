<?php

namespace App\Services;

use App\Repositories\ParsingChartDataTypeRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Lang;
use function PHPUnit\Framework\isEmpty;

class ParsingChartDataTypeService
{
    private ParsingChartDataTypeRepository $repository;

    public function __construct(ParsingChartDataTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function getParsingDataTable(string $type)
    {
        if (self::type($type)) {
            return $this->mapParsDataTable($this->repository->index($type));
        }

        throw new Exception('undefined type');
    }

    /**
     * @throws Exception
     */
    public function getCountriesDataTable()
    {
        return $this->mapParsCountriesDataTable($this->repository->getAllTypes());
    }

    /**
     * @param Collection $collection
     * @return Collection|\Illuminate\Support\Collection
     */
    public function mapParsDataTable(Collection $collection)
    {
        return $collection->map(function ($item) {
            return [
                'title' => $item['title'],
                'chart' => json_decode($item['months'], true)
            ];
        });
    }

    public function mapParsCountriesDataTable(Collection $collection)
    {
        return $collection->map(function ($item) {
            return [
                'label' => $this->getCountryNameByType($item['type']),
                'value' => $item['type']
            ];
        });
    }

    public function getCountryNameByType(string $type)
    {
        if (Lang::has('countries.' . $type)) {
            return Lang::get('countries.' . $type, [], 'ru');
        }
        return 'Страна не найдено';
    }

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
    public function getParsingPieChartDataTable(array $data, string $type)
    {
        if (self::type($type)) {
            if (count($this->repository->index($type))) {
                return $this->mapPieChartParsData($this->repository->index($type), $data, 'piechart');
            }
            return [];
        }

        throw new Exception('undefined type');
    }

    /**
     * @param Collection $collection
     * @param array $data
     * @param string $chart
     * @return array|Collection|\Illuminate\Support\Collection
     * @throws Exception
     */
    public function mapPieChartParsData(Collection $collection, array $data, string $chart)
    {
        if (isset($data['title']) && !empty($data['title'])) {
            if (isset($data['month']) && !empty($data['month'])) {
                $getTitle = $this->getTitleMonthData($collection, $data['title'], $data['month'], $chart);

                return $getTitle ?? [];
            }
        }
        return [];
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
     * @param string $chart
     * @return array
     * @throws Exception
     */
    public function getTitleMonthData(Collection $collection, string $titleData, string $monthData, string $chart): array
    {
        $data = [];

        try {
            $collection = $collection->where('title', $titleData);

            if (empty($collection->toArray())) {
                return array_values($data);
            }

            $collection->map(function ($item) use (&$data, $monthData, $chart) {
                foreach (json_decode($item['months']) as $month) {
                    if ($monthData == $month->title) {
                        if ($chart == 'piechart') {
                            if (count($month->piechart)) {
                                foreach ($month->piechart as $titleDataKey => $tableData) {
                                    $data[$titleDataKey] = [
                                        'country' => $tableData->goods,
                                        'litres' => $tableData->v,
                                    ];
                                }
                            }
                        } else {
                            if (count($month->barchart)) {
                                foreach ($month->barchart as $titleDataKey => $tableData) {
                                    $data[$titleDataKey] = [
                                        'country' => $tableData->c,
                                        'visits' => $tableData->v,
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
     * @throws Exception
     */
    public function getParsingBarChartDataTable(array $data, string $type)
    {
        if (self::type($type)) {
            if (count($this->repository->index($type))) {
                return $this->mapBarChartParsData($this->repository->index($type), $data, 'barchart');
            }
            return [];
        }

        throw new Exception('undefined type');
    }

    /**
     * @param Collection $collection
     * @param array $data
     * @param string $chart
     * @return array|Collection|\Illuminate\Support\Collection
     * @throws Exception
     */
    public function mapBarChartParsData(Collection $collection, array $data, string $chart)
    {
        if (isset($data['title']) && !empty($data['title'])) {
            if (isset($data['month']) && !empty($data['month'])) {
                $getTitle = $this->getTitleMonthData($collection, $data['title'], $data['month'], $chart);
                return $getTitle ?? [];
            }
        }
        return [];
    }
}
