<?php

namespace App\Interfaces;

interface ItemRepositoryInterface {
    public function getItems();
    public function getItemById($item_id);
    public function getItemByIds($item_ids);
}
