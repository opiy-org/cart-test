<?php
/**
 * Created by PhpStorm.
 * User: opiy
 * Date: 03.11.2017
 * Time: 20:49
 */

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use App\References\CartReference;
use App\Services\JResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController
{

    /**
     * Cart content
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $token = $request->attributes->get('token');

        /** @var Cart $cart */
        $cart = new Cart($token);
        $items = $cart->getItems();

        $cart_items = collect();
        foreach ($items as $product_id => $quantity) {
            $product = Product::where('id', $product_id)->first();
            $cart_items->push([
                'id' => $product_id,
                'quantity' => $quantity,
                'sum' => $product->price * $quantity,
            ]);
        }

        $data = [
            'total_sum' => $cart_items->sum('sum'),
            'products_count' => $cart_items->sum('quantity'),
            'products' => $cart_items,
        ];

        return JResponseService::data($data);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $token = $request->attributes->get('token');

        $validator = Validator::make(
            $request->all(),
            CartReference::RULES
        );
        if ($validator->fails()) {
            return JResponseService::validation_errors($validator);
        }

        $product_id = (int)$request->get('product_id');
        $quantity = (int)$request->get('quantity');

        /** @var Cart $cart */
        $cart = new Cart($token);
        $cart_items = $cart->getItems();

        $current_qnt = array_get($cart_items, $product_id, 0);
        if ($current_qnt > 0) {
            $quantity += $current_qnt;
        }

        $cart_items[$product_id] = $quantity;

        $cart->setItems($cart_items);

        return $this->index($request);
    }


    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id, Request $request): JsonResponse
    {
        $token = $request->attributes->get('token');

        $validator = Validator::make(
            ['product_id' => $id],
            ['product_id' => 'integer|required|exists:products,id']
        );
        if ($validator->fails()) {
            return JResponseService::validation_errors($validator);
        }

        /** @var Cart $cart */
        $cart = new Cart($token);
        $cart_items = $cart->getItems();

        $current_qnt = array_get($cart_items, $id, null);
        if ($current_qnt === null) {
            return JResponseService::error([
                'type' => 'invalid_param_error',
                'message' => 'No such product in the cart',
            ], 400);
        }


        if ($current_qnt > 1) {
            $quantity = $current_qnt - 1;
            $cart_items[$id] = $quantity;
        } else {
            unset($cart_items[$id]);
        }

        $cart->setItems($cart_items);

        return $this->index($request);
    }


}