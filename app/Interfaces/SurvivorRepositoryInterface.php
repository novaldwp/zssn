<?php

namespace App\Interfaces;

interface SurvivorRepositoryInterface {
    public function getSurvivors();
    public function getSurvivor($survivor_id);
    public function getInfectedHasItemsSurvivors();
    public function getInfectedSurvivors();
    public function getNotInfectedSurvivors();
    public function getSurvivorById($survivor_id);
    public function createSurvivor($data);
    public function updateLastLocationSurvivor($survivor_id, $data);
    public function updateIsInfectedSurvivor($survivor_id);
    public function getSurvivorItemsById($survivor_id);
}
