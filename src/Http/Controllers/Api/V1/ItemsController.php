<?php

namespace Elfcms\Shop\Http\Controllers\Api\V1;

use Elfcms\Basic\Models\DataType;
use Elfcms\Basic\Models\Setting;
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

    public function setItem(Request $request, int|ShopProduct $id, bool|int $createParam = false)
    {
        $product = null;

        if (is_object($id) && ($id instanceof ShopProduct)) {
            $product = $id;
        }
        elseif (is_numeric($id)) {
            $product = ShopProduct::find($id);
        }

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
                    $path = Setting::value('shop_image_path') ?? '/shop/item/images';
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

        if ($item) {


            /* Prices */
            $defaultPriceType = ShopPriceType::defaultOrFirst();

            $defaultCurrency = ShopCurrency::defaultOrFirst();

            if (!empty($request->item['prices'])) {
                $prices = [];
                if (!empty($request->item['prices']['value'])) {
                    $prices[] = $request->item['prices'];
                }
                elseif (!empty($request->item['prices'][0])) {
                    $prices = $request->item['prices'];
                }

                $isOld = false;
                $isCurrent = false;
                foreach ($prices as $price) {

                    try {
                        if (empty($price['value'])) {
                            continue;
                        }
                        //get price type
                        $priceType = null;
                        if (!empty($price['priceType'])) {
                            if (is_array($price['priceType'])) {
                                if (!empty($price['priceType']['id'])) {
                                    $priceType = ShopPriceType::find($price['priceType']['id']);
                                }
                                if (empty($priceType) && !empty($price['priceType']['code'])) {
                                    $priceType = ShopPriceType::where('code',$price['priceType']['code'])->first();
                                }
                                if (empty($priceType) && !empty($price['priceType']['name'])) {
                                    $priceType = ShopPriceType::where('name',$price['priceType']['name'])->first();
                                }
                            }
                            else {
                                if (is_numeric($price['priceType'])) {
                                    $priceType = ShopPriceType::find($price['priceType']['id']);
                                }
                                else {
                                    $priceType = ShopPriceType::where('code',$price['priceType'])->first();
                                }
                                if (empty($priceType)) {
                                    $priceType = ShopPriceType::where('name',$price['priceType'])->first();
                                }
                            }
                        }
                        if (empty($priceType)) {
                            $priceType = $defaultPriceType;
                        }
                        //get currency
                        $currency = null;
                        if (!empty($price['currency'])) {
                            if (is_array($price['currency'])) {
                                if (!empty($price['currency']['id'])) {
                                    $currency = ShopCurrency::find($price['currency']['id']);
                                }
                                if (empty($currency) && !empty($price['currency']['code'])) {
                                    $currency = ShopCurrency::where('code',$price['currency']['code'])->first();
                                }
                                if (empty($currency) && !empty($price['currency']['name'])) {
                                    $currency = ShopCurrency::where('name',$price['currency']['name'])->first();
                                }
                            }
                            else {
                                if (is_numeric($price['currency'])) {
                                    $currency = ShopCurrency::find($price['currency']);
                                }
                                else {
                                    $currency = ShopCurrency::where('code',$price['currency'])->first();
                                }
                                if (empty($currency)) {
                                    $currency = ShopCurrency::where('name',$price['currency'])->first();
                                }
                            }
                        }
                        if (empty($currency)) {
                            $currency = $defaultCurrency;
                        }
                        if (empty($currency) || empty($currency->id) || empty($priceType) || empty($priceType->id)) {
                            continue;
                        }
                        $priceData = [
                            'value' => intval(floatval(str_replace(',','.',$price['value'])) * 100),
                            'price_type_id' => $priceType->id,
                            'currency_id' => $currency->id,
                            'current' => !empty($price['current']) && !$isCurrent ? 1 : 0,
                            'old' => !empty($price['old']) && !$isOld ? 1 : 0,
                        ];

                        if (!empty($price['current'])) {
                            $isCurrent = true;
                        }
                        if (!empty($price['old'])) {
                            $isOld = true;
                        }

                        $item->prices()->create($priceData);
                    }
                    catch (\Exception $e) {
                        //dd($e->getMessage());
                        //throw new HttpException(500, $e->getMessage());
                        //$result = $this->error;
                        //return response()->json($this->error, 500);
                        $priceSaveError = [
                            'code' => $e->getCode(),
                            'message' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString()
                        ];
                    }
                }
            }
            /* /Prices */

            /* Parameters */
            if (!empty($request->item['parameters'])) {
                foreach($request->item['parameters'] as $parameterItem) {

                    if (empty($parameterItem['value'])) {
                        continue;
                    }

                    if (empty($parameterItem['name'])) {
                        $parameterItem['name'] = null;
                    }
                    if (empty($parameterItem['code'])) {
                        $parameterItem['code'] = null;
                    }

                    $query = ShopItemParameter::with('data_type');

                    if (!empty($parameterItem['id'])) {
                        $query->where('id',$parameterItem['id']);
                    }
                    elseif (!empty($parameterItem['code'])) {
                        $query->where('code',$parameterItem['code']);
                    }
                    elseif (empty($parameterItem['id']) && empty($parameterItem['code'] && !empty($parameterItem['name']))) {
                        $code = Str::slug($parameterItem['name'] ?? '');
                        $query->where('code',$code);
                    }

                    $parameter = $query->first();
                    $dataType = null;

                    /* Create parameter */
                    if (empty($parameter) && empty($parameterItem['id']) && !empty($parameterItem['name']) && (!empty($createParam) || (!empty($request->createParam) || $request->createParam == 'true'))) {
                        $dataTypeId = null;
                        $dataTypeCode = null;
                        if (!empty($parameterItem['data_type']) || !empty($parameterItem['data_type_id'])) {
                            if (!empty($parameterItem['data_type_id'])) {
                                $dataTypeId = $parameterItem['data_type_id'];
                            }
                            elseif (is_numeric($parameterItem['data_type'])) {
                                $dataTypeId = $parameterItem['data_type'];
                            }
                            elseif (!empty($parameterItem['data_type']['id'])) {
                                $dataTypeId = $parameterItem['data_type']['id'];
                            }
                            if (!empty($dataTypeId)) {
                                $dataType = DataType::find($dataTypeId);
                            }
                            if (empty($dataType)) {
                                if (is_string($parameterItem['data_type'])) {
                                    $dataTypeCode = $parameterItem['data_type'];
                                }
                                elseif (!empty($parameterItem['data_type']['code'])) {
                                    $dataTypeCode = $parameterItem['data_type']['code'];
                                }
                                if (!empty($dataTypeCode)) {
                                    $dataType = DataType::where('code',$dataTypeCode)->first();
                                }
                            }
                        }
                        if (!empty($dataType)) {
                            $parameterData = [
                                'data_type_id' => $dataType->id,
                                'name' => $parameterItem['name'],
                                'code' => $parameterItem['code'] ?? Str::slug($parameterItem['name']),
                                'description' => $parameterItem['description'] ?? null,
                                'is_filter' => $parameterItem['is_filter'] ?? 0,
                                'multiple' => $parameterItem['multiple'] ?? 0,
                                'options' => $parameterItem['options'] ?? null,
                            ];
                            try {
                                $parameter = ShopItemParameter::create($parameterData);
                            }
                            catch(\Exception $e) {
                                //
                            }
                        }

                    }
                    /* /Create parameter */

                    if (empty($parameter) || !is_object($parameter) || !($parameter instanceof ShopItemParameter)) {
                        continue;
                    }

                    if ($parameter->data_type->code == 'list') {
                        if (empty($parameterItem['value'])) {
                            $parameterItem['value'] = [];
                        }
                        if (!is_array($parameterItem['value'])) {
                            $parameterItem['value'] = [$parameterItem['value']];
                        }
                        $parameterItem['value']['value'] = json_encode($parameterItem['value']);
                    }
                    if ($parameter->data_type->code == 'color') {
                        continue;
                        /* if ($parameterItem['value'] == 0) {
                            $parameterItem['value'] = null;
                        } */
                    }
                    if ($parameter->data_type->code == 'file' || $parameter->data_type->code == 'image')  {
                        $image_path = null;
                        try {
                            $file = file_get_contents($parameterItem['value']);
                            if ($file) {
                                //return $file;
                                $path = Setting::value('shop_parameter_path') ?? '/shop/item/parameters';
                                //$image = Storage::putFile($path, new File($parameterItem['value']));
                                //return $image;
                                $pathinfo = pathinfo($parameterItem['value']);
                                $filename = uniqid();//time();//$pathinfo['basename'];
                                if (!empty($pathinfo['extension'])) {
                                    $filename .= '.' . $pathinfo['extension'];
                                }
                                else {
                                    $filename .= '.jpg';
                                }
                                //return $path . '/' . $filename;
                                //$uploaded_file = new UploadedFile(urlencode($parameterItem['value']), $filename);
                                //return $uploaded_file;
                                $image = Storage::put('/public' . $path . '/' . $filename , $file);
                                //Storage::put('/public' . $path . '/' . $filename, file_get_contents($parameterItem['value']));
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
                        $parameterItem['value'] = $image_path;
                    }

                    $parameterValue = ShopItemParameterValue::updateOrCreate(
                        ['item_id' => $item->id, 'parameter_id' => $parameter->id],
                        [$parameter->data_type->code . '_value' => $parameterItem['value']]
                    );
                }
            }
            /* /Parameters */

            /* Properties */
            if (!empty($request->item['properties'])) {
                foreach($request->item['properties'] as $propertyItem) {

                    if (empty($propertyItem['value'])) {
                        continue;
                    }

                    if (empty($propertyItem['name'])) {
                        $propertyItem['name'] = null;
                    }
                    if (empty($propertyItem['code'])) {
                        $propertyItem['code'] = null;
                    }

                    $query = ShopItemProperty::with('data_type');

                    if (!empty($propertyItem['id'])) {
                        $query->where('id',$propertyItem['id']);
                    }
                    if (!empty($propertyItem['code'])) {
                        $query->where('code',$propertyItem['code']);
                    }
                    if (empty($propertyItem['id']) && empty($propertyItem['code'] && !empty($propertyItem['name']))) {
                        $code = Str::slug($propertyItem['name']);
                        $query->where('code',$code);
                    }

                    $property = $query->first();
                    $dataType = null;

                    /* Create property */
                    if (empty($property) && empty($propertyItem['id']) && !empty($propertyItem['name']) && (!empty($createParam) || (!empty($request->createParam) || $request->createParam == 'true'))) {
                        $dataTypeId = null;
                        $dataTypeCode = null;
                        if (!empty($propertyItem['data_type']) || !empty($propertyItem['data_type_id'])) {
                            if (!empty($propertyItem['data_type_id'])) {
                                $dataTypeId = $propertyItem['data_type_id'];
                            }
                            elseif (is_numeric($propertyItem['data_type'])) {
                                $dataTypeId = $propertyItem['data_type'];
                            }
                            elseif (!empty($propertyItem['data_type']['id'])) {
                                $dataTypeId = $propertyItem['data_type']['id'];
                            }
                            if (!empty($dataTypeId)) {
                                $dataType = DataType::find($dataTypeId);
                            }
                            if (empty($dataType)) {
                                if (is_string($propertyItem['data_type'])) {
                                    $dataTypeCode = $propertyItem['data_type'];
                                }
                                elseif (!empty($propertyItem['data_type']['code'])) {
                                    $dataTypeCode = $propertyItem['data_type']['code'];
                                }
                                if (!empty($dataTypeCode)) {
                                    $dataType = DataType::where('code',$dataTypeCode)->first();
                                }
                            }
                        }
                        if (!empty($dataType)) {
                            $propertyData = [
                                'data_type_id' => $dataType->id,
                                'name' => $propertyItem['name'],
                                'code' => $propertyItem['code'] ?? Str::slug($propertyItem['name']),
                                'description' => $propertyItem['description'] ?? null,
                                'is_filter' => $propertyItem['is_filter'] ?? 0,
                                'multiple' => $propertyItem['multiple'] ?? 0,
                                'options' => $propertyItem['options'] ?? null,
                            ];
                            try {
                                $property = ShopItemProperty::create($propertyData);
                            }
                            catch(\Exception $e) {
                                //
                            }
                        }

                    }
                    /* /Create property */

                    if (empty($property) || !is_object($property) || !($property instanceof ShopItemProperty)) {
                        continue;
                    }

                    if ($property->data_type->code == 'list') {
                        if (empty($propertyItem['value'])) {
                            $propertyItem['value'] = [];
                        }
                        if (!is_array($propertyItem['value'])) {
                            $propertyItem['value'] = [$propertyItem['value']];
                        }
                        $propertyItem['value']['value'] = json_encode($propertyItem['value']);
                    }
                    if ($property->data_type->code == 'color') {
                        continue;
                        /* if ($propertyItem['value'] == 0) {
                            $propertyItem['value'] = null;
                        } */
                    }
                    if ($property->data_type->code == 'file' || $property->data_type->code == 'image')  {
                        $image_path = null;
                        try {
                            $file = file_get_contents($propertyItem['value']);
                            if ($file) {
                                //return $file;
                                $path = Setting::value('shop_property_path') ?? '/shop/item/properties';
                                //$image = Storage::putFile($path, new File($propertyItem['value']));
                                //return $image;
                                $pathinfo = pathinfo($propertyItem['value']);
                                $filename = uniqid();//time();//$pathinfo['basename'];
                                if (!empty($pathinfo['extension'])) {
                                    $filename .= '.' . $pathinfo['extension'];
                                }
                                else {
                                    $filename .= '.jpg';
                                }
                                //return $path . '/' . $filename;
                                //$uploaded_file = new UploadedFile(urlencode($propertyItem['value']), $filename);
                                //return $uploaded_file;
                                $image = Storage::put('/public' . $path . '/' . $filename , $file);
                                //Storage::put('/public' . $path . '/' . $filename, file_get_contents($propertyItem['value']));
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
                        $propertyItem['value'] = $image_path;
                    }

                    $propertyValue = ShopItemPropertyValue::updateOrCreate(
                        ['item_id' => $item->id, 'property_id' => $property->id],
                        [$property->data_type->code . '_value' => $propertyItem['value']]
                    );
                }
            }
            /* /Properties */


            //$item->prices()->create()
        }


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
