<?php

namespace App\Repositories;

use App\Interfaces\ItemRepositoryInterface;
use App\Models\Item;

class ItemRepository implements ItemRepositoryInterface {

    private $model;

    public function __construct(Item $model)
    {
        $this->model = $model;
    }

    public function getItems()
    {
        $result = $this->model::get();

        return $result;
    }

    public function getItemById($item_id)
    {
        $result = $this->model::where('item_id', $item_id)->first();

        return $result;
    }

    public function getItemByIds($item_ids)
    {
        $result = $this->model->whereIn('id', $item_ids)->get();

        return $result;
    }
}
