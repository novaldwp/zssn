<?php

namespace App\Repositories;

use App\Interfaces\ContaminationSurvivorRepositoryInterface;
use App\Models\ContaminationSurvivor;

class ContaminationSurvivorRepository implements ContaminationSurvivorRepositoryInterface {

    private $model;

    public function __construct(ContaminationSurvivor $model)
    {
        $this->model = $model;
    }

    public function getContaminationSurvivorById($survivor_id)
    {
        $result = $this->model::where('survivor_id', $survivor_id)->get();

        return $result;
    }

    public function getContaminationSurvivorByIdAndReportBy($survivor_id, $report_by)
    {
        $result = $this->model::where([['survivor_id', $survivor_id], ['report_by', $report_by]])->first();

        return $result;
    }

    public function createContaminationSurvivor($data)
    {
        $result = $this->model::create($data);

        return $result;
    }
}
