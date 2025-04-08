<?php

namespace App\Service;

class CountService
{
public function countTotalPrice($products){
    $count = 0;
    foreach($products as $product){
        $count += $product->getPrice();
    }
    return $count;
}
}