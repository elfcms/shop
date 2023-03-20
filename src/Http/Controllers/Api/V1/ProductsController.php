<?php

namespace Elfcms\Shop\Http\Controllers\Api\V1;

use Elfcms\Basic\Models\Setting;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemParameter;
use Elfcms\Shop\Models\ShopItemProperty;
use Elfcms\Shop\Models\ShopProduct;
use Elfcms\Shop\Models\ShopProductParameterValue;
use Elfcms\Shop\Models\ShopProductPropertyValue;
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

    public function setProduct(Request $request, bool|null $createParam = null, bool|null $duplicate = null)
    {
        if (empty($request->product)) {
            return $this->error(400, 'Empty data');
        }

        $productRequest = $request->product;

        $category = null;
        $item_number = null;
        $title = null;
        $slug = null;
        $image = null;
        $description = null;
        $unit = null;
        $brand = null;
        $vendor = null;
        $active = null;
        $meta_keywords = null;
        $meta_description = null;

        if (!empty($productRequest['title'])) {
            $title = $productRequest['title'];
        }

        if (empty($productRequest['slug'])) {
            $slug = Str::slug($productRequest['title']);
        }

        $existedProduct = ShopProduct::where('slug',$slug)->first();

        if ($duplicate === null && !empty($request->duplicate)) {
            if ($request->duplicate == 'true') {
                $duplicate = true;
            }
            elseif ($request->duplicate == 'false') {
                $duplicate = false;
            }
        }

        if (empty($existedProduct)) {
            if ($duplicate) {
                $productRequest['slug'] .= '-' . uniqid();
            }
            else {
                $this->error(400, 'Product already exists');
            }
        }

        if (!empty($productRequest['category'])) {
            $category = $this->setCategory($productRequest['category']);
        }

        if (!empty($productRequest['unit'])) {
            $unit = $this->setUnit($productRequest['unit']);
        }

        if (!empty($productRequest['brand'])) {
            $brand = $this->setBrand($productRequest['brand']);
        }

        if (!empty($productRequest['vendor'])) {
            $vendor = $this->setVendor($productRequest['vendor']);
        }

        if (!empty($productRequest['image'])) {
            $image = $this->setFileByLink($productRequest['image']);
        }

        if (!empty($productRequest['description'])) {
            $description = $productRequest['description'];
        }

        if (!empty($productRequest['item_number'])) {
            $item_number = $productRequest['item_number'];
        }

        if (!empty($productRequest['meta_keywords'])) {
            $meta_keywords = $productRequest['meta_keywords'];
        }

        if (!empty($productRequest['meta_description'])) {
            $meta_description = $productRequest['meta_description'];
        }

        if (isset($productRequest['active']) && ($productRequest['active'] === 0 || $productRequest['active'] === '0' || $productRequest['active'] === false || $productRequest['active'] === 'false')) {
            $active = 0;
        }
        else {
            $active = 1;
        }

        $productData = [
            'item_number' => $item_number,
            'title' => $title,
            'slug' => $slug,
            'image' => $image,
            'description' => $description,
            'vendor_id' => $vendor->id ?? null,
            'brand_id' => $brand->id ?? null,
            'unit_id' => $unit->id ?? null,
            'active' => $active,
            'meta_keywords' => $meta_keywords,
            'meta_description' => $meta_description,
        ];


        $validated = Validator::make($productData,[
            'title' => 'required',
            'slug' => 'required|unique:Elfcms\Shop\Models\ShopProduct,slug',
            'image' => 'nullable|max:1024',
            'unit_id' => 'required',
        ]);
        if ($validated->fails()) {
            $errorsData = $validated->errors()->messages();
            $errorsMessages = [];
            foreach ($errorsData as $message) {
                $errorsMessages[] = $message[0];
            }
            $errors = implode(' ', $errorsMessages);

            return $this->error(400, $errors);
        }

        $product = ShopProduct::create($productData);

        if ($product) {

            if (!empty($category) && !empty($category->id)) {
                $product->categories()->attach($category->id);
            }

            if ($createParam === null && !empty($request->createParam)) {
                if ($request->createParam == 'true') {
                    $createParam = true;
                }
                elseif ($request->createParam == 'false') {
                    $createParam = false;
                }
            }

            /* Parameters */
            if (!empty($request->product['parameters'])) {
                foreach ($request->product['parameters'] as $parameterItem) {

                    if (empty($parameterItem['value'])) {
                        continue;
                    }

                    $parameter = $this->setParameter($parameterItem, $createParam);

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
                        $parameterItem['value'] = null;
                        if (!empty($parameterItem['value'])) {
                            $color = $this->setColor($parameterItem['value']);
                            if ($color) {
                                $parameterItem['value'] = $color->id;
                            }
                        }
                    }
                    if ($parameter->data_type->code == 'file' || $parameter->data_type->code == 'image') {
                        $parameterItem['value'] = $this->setFileByLink($parameterItem['value'], Setting::value('shop_parameter_path') ?? '/shop/item/parameters');
                    }

                    $parameterValue = ShopProductParameterValue::updateOrCreate(
                        ['product_id' => $product->id, 'parameter_id' => $parameter->id],
                        [$parameter->data_type->code . '_value' => $parameterItem['value']]
                    );
                }
            }
            /* /Parameters */

            /* Properties */
            if (!empty($request->product['properties'])) {
                foreach ($request->product['properties'] as $propertyItem) {

                    if (empty($propertyItem['value'])) {
                        continue;
                    }

                    $property = $this->setProperty($propertyItem, $createParam);

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
                        //continue;
                        $propertyItem['value'] = null;
                        if (!empty($propertyItem['value'])) {
                            $color = $this->setColor($propertyItem['value']);
                            if ($color) {
                                $propertyItem['value'] = $color->id;
                            }
                        }
                    }
                    if ($property->data_type->code == 'file' || $property->data_type->code == 'image') {
                        $propertyItem['value'] = $this->setFileByLink($propertyItem['value'], Setting::value('shop_property_path') ?? '/shop/item/properties');
                    }

                    $propertyValue = ShopProductPropertyValue::updateOrCreate(
                        ['product_id' => $product->id, 'property_id' => $property->id],
                        [$property->data_type->code . '_value' => $propertyItem['value']]
                    );
                }
            }
            /* /Properties */

            if (!empty($request->product['items'])) {
                foreach($request->product['items'] as $itemData) {
                    if (!is_array($itemData)) {
                        continue;
                    }
                    $item = $product->items()->create($itemData);
                }
            }

        }

        $product->with('categories');

        return $product;

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
