<?php

namespace App\Repositories;

use App\Interfaces\TradeRepositoryInterface;
use App\Models\ItemSurvivor;
use Illuminate\Support\Facades\DB;

class TradeRepository implements TradeRepositoryInterface {
    private $model;

    public function __construct(ItemSurvivor $model)
    {
        $this->model = $model;
    }

    public function tradeItems($survivor_id, $other_survivor, $wantItems, $giveItems) {
        DB::beginTransaction();

        foreach ($wantItems as $key => $val) {
            $survivor = $this->model::where('survivor_id', $survivor_id)->where('item_id', $key)->first();
            if ($survivor) {
                    $survivor->update([
                        'amount' => $val + $survivor->amount,
                    ]);
            } else {
                $this->model::create([
                    'survivor_id' => $survivor_id,
                    'item_id'     => $key,
                    'amount'      => $val
                ]);
            }

            $otherSurvivor = $this->model::where('survivor_id', $other_survivor)->where('item_id', $key)->first();
            $otherSurvivor->update([
                'amount' => $otherSurvivor->amount - $val
            ]);
        }

        foreach ($giveItems as $key => $val) {
            $survivor = $this->model::where('survivor_id', $survivor_id)->where('item_id', $key)->first();
            $survivor->update([
                'amount' => $survivor->amount - $val
            ]);

            $otherSurvivor = $this->model::where('survivor_id', $other_survivor)->where('item_id', $key)->first();
            if ($otherSurvivor) {
                $otherSurvivor->update([
                    'amount' => $otherSurvivor->amount + $val
                ]);
            } else {
                $this->model::create([
                    'survivor_id' => $other_survivor,
                    'item_id'     => $key,
                    'amount'      => $val
                ]);
            }
        }

        DB::commit();

        return true;
    }

    public function getSumAmountItemsByItemId($item_id)
    {
        $result = $this->model::where('item_id', $item_id)->sum('amount');

        return $result;
    }

    public function getItemsByInfectedSurvivors()
    {
        $result = $this->model::with([
                'survivors' => function($q) {
                    $q->where('is_infected', '1');
                },
                'items'
            ])
            ->get();

        return $result;
    }
}
