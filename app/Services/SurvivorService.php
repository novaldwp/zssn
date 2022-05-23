<?php

namespace App\Services;

use App\Interfaces\ContaminationSurvivorRepositoryInterface;
use App\Interfaces\SurvivorRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SurvivorService {
    private $survivorRepository;
    private $contaminationRepository;

    public function __construct(SurvivorRepositoryInterface $survivorRepository, ContaminationSurvivorRepositoryInterface $contaminationRepository)
    {
        $this->survivorRepository = $survivorRepository;
        $this->contaminationRepository = $contaminationRepository;
    }

    public function getSurvivor($survivor_id)
    {
        try {
            $result = $this->survivorRepository->getSurvivor($survivor_id);
        } catch (Exception $e) {
            throw new Exception("Unable to get survivor", 404);
        }

        return $result;
    }

    public function createSurvivor($request)
    {
        // set inserted data
        $data['survivor'] = [
            'name'      => $request->name,
            'age'       => $request->age,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'gender_id' => $request->gender_id
        ];

        $data['items']  = $request->items;
        $data['amounts'] = $request->amounts;

        // get dupplicate array
        $duplicateArr = array_diff_key($request->items, array_unique($request->items));

        foreach ($duplicateArr as $key => $val) {
            // get key who have duplicate value
            $keyItems = array_search($duplicateArr[$key], $data['items']);

            // sum original array value with duplicate array value
            $data['amounts'][$keyItems] += $data['amounts'][$key];

            // unset the dupplicate array
            unset($data['items'][$key]);
            unset($data['amounts'][$key]);
        }

        // combine array with comma as separator
        $toString = implode(",", $data['amounts']);

        // explode to change all data type value of array to int
        $data['amounts'] = array_map('intval', explode(',', $toString));

        try {
            $result = $this->survivorRepository->createSurvivor($data);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }

        return $request;
    }

    public function updateLastLocationSurvivor($request, $survivor_id)
    {
        // validate survivor
        $this->_checkExistingSurvivor($survivor_id);

        // set inserted data
        $data = [
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude
        ];

        try {
            $result = $this->survivorRepository->updateLastLocationSurvivor($survivor_id, $data);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Unable to update last location survivor", 500);
        }

        return $result;
    }

    public function updateIsInfectedSurvivor($survivor_id)
    {
        // validate survivor
        $this->_checkExistingSurvivor($survivor_id);

        try {
            $result = $this->survivorRepository->updateIsInfectedSurvivor($survivor_id);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Unable to update is infected survivor", 500);
        }

        return $result;
    }

    public function createContaminationSurvivor($request)
    {
        // validate survivor and reporter
        if ($request->survivor_id == $request->report_by) {
            throw new Exception("You can't report yourself", 500);
        }

        // get data survivor
        $survivor = $this->survivorRepository->getSurvivorById($request->survivor_id);

        if ($survivor->is_infected) {
            throw new Exception("Can't proceed report, because survivor already infected", 500);
        }

        // check existing survivor_id and report_by to avoid double report
        $eSurvivor = $this->contaminationRepository->getContaminationSurvivorByIdAndReportBy($request->survivor_id, $request->report_by);
        if ($eSurvivor && $request->survivor_id == $eSurvivor->survivor_id && $request->report_by == $eSurvivor->report_by) {
            throw new Exception("Reporter already report this survivor", 500);
        }

        // get reporter survivor
        $rSurvivor = $this->survivorRepository->getSurvivorById($request->report_by);

        // check is repoter already infected
        if ($rSurvivor->is_infected) {
            throw new Exception("You can't report other survivor, because you already infected", 500);
        }

        // count how many times this survivor get reported if he contamined
        $cSurvivor = $this->contaminationRepository->getContaminationSurvivorById($request->survivor_id)->count();

        // set inserted data
        $data = [
            'survivor_id' => $request->survivor_id,
            'report_by'   => $request->report_by
        ];

        DB::beginTransaction();

        try {
            $result = $this->contaminationRepository->createContaminationSurvivor($data);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Unable to create new contamination Survivor", 500);
        }

        if ($result) {
            $cSurvivor++;
        }

        if ($cSurvivor >= 3) {
            try {
                $result = $this->survivorRepository->updateIsInfectedSurvivor($request->survivor_id);
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception("Unable to update is infected value to Survivor", 500);
            }
        }

        DB::commit();

        return true;
    }

    public function _checkExistingSurvivor($survivor_id)
    {
        $survivor = $this->survivorRepository->getSurvivorById($survivor_id);

        if (!$survivor) {
            throw new Exception("Survivor Not Found", 404);
        }
    }
}
