<?php

namespace Elfcms\Shop\Http\Controllers\Api\V1;

use Elfcms\Elfcms\Models\DataType;
use Elfcms\Elfcms\Models\Setting;
use Elfcms\Shop\Models\ShopCurrency;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemParameter;
use Elfcms\Shop\Models\ShopItemParameterValue;
use Elfcms\Shop\Models\ShopItemProperty;
use Elfcms\Shop\Models\ShopItemPropertyValue;
use Elfcms\Shop\Models\ShopPriceType;
use Elfcms\Shop\Models\ShopProduct;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ItemsController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getItems(Request $request)
    {
        try {
            $itemObject = new ShopProduct();
            $fillable = $itemObject->getFillable();

            $trend = 'asc';
            $order = 'id';
            if (!empty($request->trend) && $request->trend == 'desc') {
                $trend = 'desc';
            }
            if (!empty($request->order) && in_array($request->order, $fillable)) {
                $order = $request->order;
            }
            if (!empty($request->limit)) {
                $limit = intval($request->limit);
            }
            if (empty($limit)) {
                $limit = 100;
            }
            if (!empty($request->offset)) {
                $offset = intval($request->offset);
            } elseif (!empty($request->page)) {
                $offset = (intval($request->page) - 1) * $limit;
            }
            if (empty($offset)) {
                $offset = 0;
            }
            //$search = $request->search ?? '';

            //$products = ShopProduct::with('items')->orderBy($order, $trend)->offset($offset)->limit($count)->get();
            $query = ShopItem::with('product')->orderBy($order, $trend)->offset($offset)->limit($limit);
            /* if (!empty($request->items) && $request->items == 'true') {
                $query = $query->with('items');
            } */

            $items = $query->get();

            //$request->headers->set('Accept', 'application/json');

            $result = $this->empty;

            $resultCount = $items->count();


            //if ($products && $resultCount > 0) {
            $result['message'] = $resultCount . ' elements were found';
            $result['count'] = $resultCount;
            $result['data'] = $items;
            $result['offset'] = $offset;
            $result['limit'] = $limit;
            //}

            //dd($products->count);

            return $result;
        } catch (\Exception $e) {
            //dd($e->getMessage());
            //throw new HttpException(500, $e->getMessage());
            //$result = $this->error;
            return response()->json($this->error, 500);
        }
    }

    /**
     * Display a item of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getItem(Request $request, $id)
    {
        if (!empty($request->byId) && $request->byId == 'true') {
            $item = ShopItem::find($id);
        } else {
            $item = ShopItem::where('item_number', $id)->first();
        }

        $result = $this->empty;


        if ($item) {
            $result['message'] = 'Element was found';
            $result['count'] = 1;
            $result['data'] = $item;
        } else {
            $result = $this->notFound();
        }


        return $result;
    }

    public function setNewItem(Request $request, int|ShopProduct $product, bool|null $createParam = null)
    {
        if (empty($request->item)) {
            return $this->error(400, 'Empty request data');
        }

        if ($createParam === null && !empty($request->createParam)) {
            if ($request->createParam == 'true') {
                $createParam = true;
            }
            elseif ($request->createParam == 'false') {
                $createParam = false;
            }
        }

        return $this->setItem($request->item, $product, $createParam);
    }


}
