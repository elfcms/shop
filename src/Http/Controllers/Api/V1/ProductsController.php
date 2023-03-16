<?php

namespace Elfcms\Shop\Http\Controllers\Api\V1;

use Elfcms\Shop\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductsController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request)
    {
        //$request->header('Accept','application/json');
        try {
            $productObject = new ShopProduct();
            $fillable = $productObject->getFillable();

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
            if (!empty($request->page)) {
                $offset = (intval($request->page) - 1) * $limit;
            }
            if (empty($offset)) {
                $offset = 0;
            }
            //$search = $request->search ?? '';

            //$products = ShopProduct::with('items')->orderBy($order, $trend)->offset($offset)->limit($count)->get();
            $query = ShopProduct::orderBy($order, $trend)->offset($offset)->limit($limit);
            if (!empty($request->items) && $request->items == 'true') {
                $query = $query->with('items');
            }

            $products = $query->get();

            //$request->headers->set('Accept', 'application/json');

            $result = $this->empty;

            $resultCount = $products->count();


            //if ($products && $resultCount > 0) {
                $result['message'] = $resultCount . ' elements were found';
                $result['count'] = $resultCount;
                $result['data'] = $products;
                $result['offset'] = $offset;
                $result['limit'] = $limit;
            //}

            //dd($products->count);

            return $result;
        }
        catch (\Exception $e) {
            //dd($e->getMessage());
            //throw new HttpException(500, $e->getMessage());
            //$result = $this->error;
            return response()->json($this->error, 500);
        }

    }

    public function getProduct($id)
    {
        $product = ShopProduct::find($id);


        if ($product) {
            $result = $this->empty;
            $result['message'] = 'Element was found';
            $result['count'] = 1;
            $result['data'] = $product;
        }
        else {
            $result = $this->notFound();
        }

        return $result;
    }

    public function setProduct(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->product['slug'] ?? $request->product['title'] ?? ''),
        ]);
        $validated = Validator::make($request->all(),[
            'title' => 'required',
            'slug' => 'required|unique:Elfcms\Shop\Models\ShopProduct,slug',
            'category_id' => 'required',
        ]);
        if ($validated->fails()) {
            $errorsData = $validated->errors()->messages();
            $errorsMessages = [];
            foreach ($errorsData as $message) {
                $errorsMessages[] = $message[0];
            }
            $errors = implode('. ', $errorsMessages);

            return $this->error(400, $errors);
        }

        $productData = $request->product;
    }

    public function setNewBrand(Request $request)
    {
        if (!empty($request->brand)) {
            return $this->setBrand($request->brand);
        }
        return $request->all();
    }

    public function setNewUnit(Request $request)
    {
        if (!empty($request->unit)) {
            return $this->setUnit($request->unit);
        }
        return $request->all();
    }

    public function setNewCategory(Request $request)
    {
        if (!empty($request->category)) {
            return $this->setCategory($request->category);
        }
        return $request->all();
    }
}
