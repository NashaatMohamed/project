<?php

namespace App\Factory;

class StockTypeFactory
{


    public static function getStockMovementType($type): ?string
    {
        if ($type == 1) {
            return "sale Invoice";
        } elseif ($type == 2) {
            return "purchase Invoice";
        } elseif ($type == 3) {
            return "returned";
        } elseif ($type == 4) {
            return "manual Edit";
        } elseif ($type == 5) {
            return "damage";
        }
        return null;
    }
}