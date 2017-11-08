<?php
/**
 * Created by PhpStorm.
 * User: opiy
 * Date: 04.11.2017
 * Time: 0:45
 */

namespace App\References;


class CartReference
{

    const RULES = [
        'product_id' => 'integer|required|exists:products,id',
        'quantity' => "integer|required|min:1|max:10",
    ];

}