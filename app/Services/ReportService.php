<?php

namespace App\Services;

use App\Interfaces\SurvivorRepositoryInterface;
use App\Interfaces\TradeRepositoryInterface;
use App\Repositories\ItemRepository;

class ReportService {
    private $itemRepository;
    private $survivorRepository;
    private $tradeRepository;

    public function __construct(ItemRepository $itemRepository, SurvivorRepositoryInterface $survivorRepository, TradeRepositoryInterface $tradeRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->survivorRepository = $survivorRepository;
        $this->tradeRepository = $tradeRepository;
    }

    public function getReports()
    {
        $countSurvivors             = $this->survivorRepository->getSurvivors()->count();
        $countInfectedSurvivors     = $this->survivorRepository->getInfectedSurvivors()->count();
        $countNotInfectedSurvivors  = $this->survivorRepository->getNotInfectedSurvivors()->count();

        $percentageInfectedSurvivors    = round(($countInfectedSurvivors / $countSurvivors) * 100, 1);
        $percentageNotInfectedSurvivors = round(($countNotInfectedSurvivors / $countSurvivors) * 100, 1);

        $survivors = [
            'infectedSurvivors'     => $percentageInfectedSurvivors . "%",
            'notInfectedSurvivors'  => $percentageNotInfectedSurvivors . "%"
        ];

        $items = $this->itemRepository->getItems();
        $avgItems = $items->mapWithKeys(function($item) use($countSurvivors) {

            if ($countSurvivors == 0) {
                $avgQuantityItem = 0;
            } else {
                $totalQuantityItem = $this->tradeRepository->getSumAmountItemsByItemId($item->id);
                $avgQuantityItem = round(($totalQuantityItem/$countSurvivors), 1);
            }

            return [$item['name'] => $avgQuantityItem];
        });

        $infectedSurvivors = $this->survivorRepository->getInfectedHasItemsSurvivors();
        $sumInfectedPoint  = $infectedSurvivors->reduce(function ($carry, $infectedSurvivor) {
            foreach ($infectedSurvivor->items as $row) {
                $carry += ($row->point * $row->pivot->amount);
            }

            return $carry;
        }, 0);

        $items = [
            'averageItem'      => $avgItems,
            'sumInfectedPoint' => $sumInfectedPoint
        ];

        $data = [
            'survivors' => $survivors,
            'items'     => $items
        ];


        return $data;
    }
}
