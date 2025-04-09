<?php

namespace App\Service;

class CountService
{
    public function countTotalPrice($productsitem)
    {
        $count = 0;
        foreach ($productsitem as $item) {
            $count += $item->getProduct()->getPrice() * $item->getQuantity();
        }
        return $count;
    }
    public function countArticleNumber($productsitem)
    {
        $count = 0;
        foreach ($productsitem as $item) {
            $count +=  $item->getQuantity();
        }
        return $count;
    }
}