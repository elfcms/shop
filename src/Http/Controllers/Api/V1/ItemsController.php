<?php

namespace Elfcms\Shop\Http\Controllers\Api\V1;

use Elfcms\Basic\Models\Setting;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopProduct;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            }
            elseif (!empty($request->page)) {
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
        }
        catch (\Exception $e) {
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
        dd(parse_url('http://dfdf.df/my/path/image.jpg'));
        if (!empty($request->byId) && $request->byId == 'true') {
            $item = ShopItem::find($id);
        }
        else {
            $item = ShopItem::where('item_number',$id)->first();
        }

        $result = $this->empty;


        if ($item) {
            $result['message'] = 'Element was found';
            $result['count'] = 1;
            $result['data'] = $item;
        }
        else {
            $result = $this->notFound();
        }


        return $result;
    }

    public function setItem(Request $request, int $id)
    {
        $product = ShopProduct::find($id);

        if (empty($product)) {
            return $this->notFound('Product not found');
        }

        //return $request->item;

        if (empty($request->item)) {
            return $this->error(400, 'Item data not found');
        }

        $validated = Validator::make($request->item,[
            'item_number' => 'required|unique:Elfcms\Shop\Models\ShopItem,item_number',
            //'product_id' => 'required',
            'image' => 'nullable|max:1024',
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


        $itemData = $request->item;

        $imageUploadError = [];

        $image = null;
        $image_path = '';
        /* Check image */

        if (!empty($request->item['image'])) {
            try {
                $file = file_get_contents($request->item['image']);
                if ($file) {
                    //return $file;
                    $path = Setting::value('shop_image_path') ?? '/shop/item/image';
                    //$image = Storage::putFile($path, new File($request->item['image']));
                    //return $image;
                    $pathinfo = pathinfo($request->item['image']);
                    $filename = uniqid();//time();//$pathinfo['basename'];
                    if (!empty($pathinfo['extension'])) {
                        $filename .= '.' . $pathinfo['extension'];
                    }
                    else {
                        $filename .= '.jpg';
                    }
                    //return $path . '/' . $filename;
                    //$uploaded_file = new UploadedFile(urlencode($request->item['image']), $filename);
                    //return $uploaded_file;
                    $image = Storage::put('/public' . $path . '/' . $filename , $file);
                    //Storage::put('/public' . $path . '/' . $filename, file_get_contents($request->item['image']));
                    if ($image){
                        $image_path = '/storage' . $path . '/' . $filename;
                    }
                }
            }
            catch (\Exception $e) {
                $imageUploadError = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ];
                //$itemData['errorMessage'] = 'File "' . $request->item['image'] . '" could not be uploaded';
            }
        }

        $itemData['image'] = $image_path;

        $item =  $product->items()->create($itemData);



        return $item;

        /* $itemData = [
            'item_number' => $request->item->item_number,
            'product_id' => $request->item->product_id,
            'description' => $request->item->description,
            'active' => empty($request->item->active) ? 0 : 1,
            'unit_id' => (int)$request->item->unit_id,
            'brand_id' => $request->item->brand_id,
            'vendor_id' => $request->item->vendor_id,
            'meta_keywords' => $request->item->meta_keywords,
            'meta_description' => $request->item->meta_description,
            'vendor_item_number' => $request->item->vendor_item_number,
            'additional_name' => $request->item->additional_name,
            'alternate_name' => $request->item->alternate_name,
            'note' => $request->item->note,
            'balance' => $request->item->balance ?? 0,
            'image' => $image_path,
        ]; */

        return $itemData;

    }
}
