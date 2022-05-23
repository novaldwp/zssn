<?php

namespace App\Repositories;

use App\Interfaces\SurvivorRepositoryInterface;
use App\Models\Survivor;

class SurvivorRepository implements SurvivorRepositoryInterface {
    private $model;

    public function __construct(Survivor $model)
    {
        $this->model = $model;
    }

    public function getSurvivors()
    {
        $result = $this->model::all();

        return $result;
    }

    public function getSurvivor($survivor_id)
    {
        $result = $this->model::with(['items'])
            ->where('id', $survivor_id)->first();

        return $result;
    }

    public function getInfectedHasItemsSurvivors()
    {
        $result = $this->model::with(['items'])
            ->has('items')
            ->where('is_infected', "1")
            ->get();

        return $result;
    }

    public function getInfectedSurvivors()
    {
        $result = $this->model::where('is_infected', "1")
            ->get();

        return $result;
    }

    public function getNotInfectedSurvivors()
    {
        $result = $this->model::where('is_infected', "0")->get();

        return $result;
    }

    public function getSurvivorById($survivor_id)
    {
        $result = $this->model::find($survivor_id);

        return $result;
    }

    public function createSurvivor($data)
    {
        $result = $this->model::create($data['survivor']);

        for ($i = 0; $i < count($data['items']); $i++) {
            $result->items()->attach(1, ['item_id' => $data['items'][$i], 'amount' => $data['amounts'][$i]]);
        }

        return $result;
    }

    public function updateLastLocationSurvivor($survivor_id, $data)
    {
        $result = $this->model::FindOrFail($survivor_id);
        $result->update($data);

        return true;
    }

    public function updateIsInfectedSurvivor($survivor_id)
    {
        $result = $this->model::where('id', $survivor_id)->update(['is_infected' => "1"]);

        return $result;
    }

    public function getSurvivorItemsById($survivor_id)
    {
        $result = $this->model::select('id', 'is_infected')
            ->with(['items'])
            ->where('id', $survivor_id)
            ->first();

        return $result;
    }
}
