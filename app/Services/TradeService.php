<?php

namespace App\Services;

use App\Interfaces\ItemRepositoryInterface;
use App\Interfaces\SurvivorRepositoryInterface;
use App\Interfaces\TradeRepositoryInterface;
use Exception;

class TradeService {
    private $itemRepository;
    private $survivorRepository;
    private $tradeRepository;

    public function __construct(ItemRepositoryInterface $itemRepository, SurvivorRepositoryInterface $survivorRepository, TradeRepositoryInterface $tradeRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->survivorRepository = $survivorRepository;
        $this->tradeRepository = $tradeRepository;
    }

    public function createTrade($request)
    {
        if ($request->survivor_id == $request->other_survivor) {
            throw new Exception("You can't trade with yourself.", 400);
        }

        // survivor who want to trade
        $tSurvivor = $this->survivorRepository->getSurvivorItemsById($request->survivor_id);

        // other survivor
        $oSurvivor = $this->survivorRepository->getSurvivorItemsById($request->other_survivor);

        // check survivor and other survivor is infected
        if ($tSurvivor->is_infected == 1 || $oSurvivor->is_infected == 1) {
            throw new Exception("Failed to make an exchange, because one of both survivor are infected", 400);
        }

        // set default variable
        $tSurvivorItems    = [];
        $oSurvivorItems    = [];
        $tSurvivorAmounts  = [];
        $oSurvivorAmounts  = [];

        foreach ($oSurvivor->items as $row) {
            array_push($oSurvivorItems, $row->pivot->item_id);
            array_push($oSurvivorAmounts, $row->pivot->amount);
        }

        // check what items the other survivors don't have
        $oItemDiff = array_diff($request->want, $oSurvivorItems);

        // if there are items that other survivors don't have
        if (count($oItemDiff) > 0) {
            // throw error
            throw new Exception("Other survivors don't have one or more of the items you want", 400);
        }

        foreach ($tSurvivor->items as $row) {
            array_push($tSurvivorItems, $row->pivot->item_id);
            array_push($tSurvivorAmounts, $row->pivot->amount);
        }

        $tItemDiff = array_diff($request->give, $tSurvivorItems);

        // if there are items that the survivors don't have
        if (count($tItemDiff) > 0) {
            // throw error
            throw new Exception("You don't have one or more of the items you want to give", 400);
        }

        // merge duplicate item and amount
        $mergeDuplicateWantItem = $this->_mergeDuplicateItemAmount($request->want, $request->wantamount);
        $mergeDuplicateGiveItem = $this->_mergeDuplicateItemAmount($request->give, $request->giveamount);

        // set variable
        $data['want']       = $mergeDuplicateWantItem['item'];
        $data['give']       = $mergeDuplicateGiveItem['item'];
        $data['wantamount'] = $mergeDuplicateWantItem['amount'];
        $data['giveamount'] = $mergeDuplicateGiveItem['amount'];

        // combine array, id => amount
        $oItemAmounts = array_combine($oSurvivorItems, $oSurvivorAmounts);
        $tItemAmounts = array_combine($tSurvivorItems, $tSurvivorAmounts);
        $wantAmounts  = array_combine($data['want'], $data['wantamount']);
        $giveAmounts  = array_combine($data['give'], $data['giveamount']);

        // check amount of items survivors want from amount of other survivor items
        foreach ($wantAmounts as $key => $val) {
            // if amount of items the survivor wants is more than amount of items for other survivors
            if ($wantAmounts[$key] > $oItemAmounts[$key]) {
                // throw error
                throw new Exception("Survivors want more items than other survivors have", 400);
            }
        }

        // check amount of items the survivor wants to give
        foreach ($giveAmounts as $key => $val) {
            // if amount of items survivor want to give is more than the amount survivor have
            if ($giveAmounts[$key] > $tItemAmounts[$key]) {
                // throw error
                throw new Exception("Amount of items that you want to give is more than amount of items you have", 400);
            }
        }

        // merge item id
        $itemIds  = array_unique(array_merge($data['want'], $data['give']));

        // set default point variable
        $tSurvivorPoint = 0;
        $oSurvivorPoint = 0;

        // get items by merged item id
        $getItems       = $this->itemRepository->getItemByIds($itemIds);

        // return $getItems;
        foreach ($getItems as $row) {
            // sum total point from survivor
            if (array_key_exists($row->id, $wantAmounts)) {
                $tSurvivorPoint += ($row->point * $wantAmounts[$row->id]);
            }

            // sum total point from other survivor
            if (array_key_exists($row->id, $giveAmounts)) {
                $oSurvivorPoint += ($row->point * $giveAmounts[$row->id]);
            }
        }

        // check if survivor dont have a same sum point amount of items
        if ($tSurvivorPoint != $oSurvivorPoint) {
            throw new Exception("Exchange failed, amounts of points of the two survivors is not the same.", 400);
        }

        try {
            $result = $this->tradeRepository->tradeItems($request->survivor_id, $request->other_survivor, $wantAmounts, $giveAmounts);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }

        return $result;
        return $tSurvivorPoint;
        return $getItems;

        return $wantAmounts;
    }

    public function _mergeDuplicateItemAmount($item, $amount) {
        // get duplicate array give
        $duplicate = array_diff_key($item, array_unique($item));

        foreach ($duplicate as $key => $val) {
            // get key who have duplicate value
            $keyItems = array_search($duplicate[$key], $item);

            // sum original array value with duplicate array value
            $amount[$keyItems] += $amount[$key];

            // unset the dupplicate array
            unset($item[$key]);
            unset($amount[$key]);
        }

        // combine array with comma as separator
        $toString = implode(",", $amount);

        // explode to change all data type value of array to int
        $amount = array_map('intval', explode(',', $toString));

        $data = [
            'item'   => $item,
            'amount' => $amount
        ];

        return $data;
    }
}
