<?php
/**
 * Created by PhpStorm.
 * User: opiy
 * Date: 03.11.2017
 * Time: 20:49
 */

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\References\ProductReference;
use App\Services\JResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController
{


    /**
     * Products list
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        /** @var Product $items */
        $items = Product::all();

        return JResponseService::data($items);
    }


    /**
     *  View product by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function view(int $id): JsonResponse
    {
        $item = Product::where('id', '=', $id)
            ->first();

        if (!$item) {
            return JResponseService::error([
                'type' => 'item_not_found',
                'message' => 'product id: ' . $id . ' not found',
            ]);
        }

        return JResponseService::data($item);
    }


    /**
     *  Create new product
     *  request must contain name,price and optionally description
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        /** @var Validator $validator */
        $validator = Validator::make($request->all(), ProductReference::RULES);
        if ($validator->fails()) {
            return JResponseService::validation_errors($validator);
        }


        try {
            $item = Product::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'price' => $request->get('price'),
            ]);
        } catch (\Exception $exception) {
            return JResponseService::error([
                'type' => 'invalid_param_error',
                'message' => 'Invalid data parameters',
                'params' => $exception->getMessage(),
            ],400);
        }

        return JResponseService::data($item);
    }

    /**
     *  Update product by id
     *  request must contain name,price and optionally description
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        /** @var Validator $validator */
        $validator = Validator::make($request->all(), ProductReference::RULES);
        if ($validator->fails()) {
            return JResponseService::validation_errors($validator);
        }

        /** @var Product $item */
        $item = Product::where('id', '=', $id)
            ->first();

        if (!$item) {
            return JResponseService::error([
                'type' => 'item_not_found',
                'message' => 'product id: ' . $id . ' not found',
            ]);
        }

        try {
            $item->update([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'price' => $request->get('price'),
            ]);
        } catch (\Exception $exception) {
            return JResponseService::error([
                'type' => 'invalid_param_error',
                'message' => 'Invalid data parameters',
                'params' => $exception->getMessage(),
            ],400);
        }

        return JResponseService::data($item);
    }


    /**
     *  Delete product by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $item = Product::where('id', '=', $id)
            ->first();

        if (!$item) {
            return JResponseService::error([
                'type' => 'item_not_found',
                'message' => 'product id: ' . $id . ' not found',
            ]);
        }

        try {
            $item->delete();
        } catch (\Exception $exception) {
            return JResponseService::error([
                'type' => 'invalid_param_error',
                'message' => 'Invalid data parameters',
                'params' => $exception->getMessage(),
            ],400);
        }

        return JResponseService::data(null);
    }


}