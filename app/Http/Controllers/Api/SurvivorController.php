<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survivor\CreateContaminationSurvivorRequest;
use App\Http\Requests\Survivor\CreateSurvivorRequest;
use App\Http\Requests\Survivor\UpdateLastLocationSurvivorRequest;
use App\Services\SurvivorService;
use Exception;

class SurvivorController extends Controller
{
    private $survivorService;

    public function __construct(SurvivorService $survivorService)
    {
        $this->survivorService = $survivorService;
    }

    public function getSurvivor($survivor_id)
    {
        try {
            $result = $this->survivorService->getSurvivor($survivor_id);

            return $this->success("Successfully get survivor", 200, $result);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function createSurvivor(CreateSurvivorRequest $request)
    {
        try {
            $result = $this->survivorService->createSurvivor($request);

            return $this->success("Successfully create a new Survivor", 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

    }

    public function updateLastLocationSurvivor(UpdateLastLocationSurvivorRequest $request, $survivor_id)
    {
        try {
            $result = $this->survivorService->updateLastLocationSurvivor($request, $survivor_id);

            return $this->success("Successfully updating last location", 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function createContaminationSurvivor(CreateContaminationSurvivorRequest $request)
    {
        try {
            $result = $this->survivorService->createContaminationSurvivor($request);

            return $this->success("Successfully create new contamination survivor", 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
