<?php

namespace App\Interfaces;

interface ContaminationSurvivorRepositoryInterface {

    public function getContaminationSurvivorById($survivor_id);
    public function getContaminationSurvivorByIdAndReportBy($survivor_id, $report_by);
    public function createContaminationSurvivor($data);
}
