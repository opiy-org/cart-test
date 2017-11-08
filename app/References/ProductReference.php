<?php
/**
 * Created by PhpStorm.
 * User: opiy
 * Date: 04.11.2017
 * Time: 0:45
 */

namespace App\References;


class ProductReference
{

    const RULES = [
        'name' => 'required|string',
        'price' => "required|regex:/^\d*(\.\d{1,2})?$/",
    ];

}