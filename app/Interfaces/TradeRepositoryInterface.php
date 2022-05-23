<?php

namespace App\Interfaces;

interface TradeRepositoryInterface {

    public function tradeItems($survivor_id, $other_survivor, $wantItems, $giveItems);
    public function getSumAmountItemsByItemId($item_id);
    public function getItemsByInfectedSurvivors();
}
