<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Elfcms\Models\Setting;
use Elfcms\Shop\Models\ShopBrand;
use Elfcms\Shop\Models\ShopCategory;
use Elfcms\Shop\Models\ShopColor;
use Elfcms\Shop\Models\ShopCurrency;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemParameter;
use Elfcms\Shop\Models\ShopItemParameterValue;
use Elfcms\Shop\Models\ShopItemProperty;
use Elfcms\Shop\Models\ShopItemPropertyValue;
use Elfcms\Shop\Models\ShopPriceType;
use Elfcms\Shop\Models\ShopProduct;
use Elfcms\Shop\Models\ShopTax;
use Elfcms\Shop\Models\ShopUnit;
use Elfcms\Shop\Models\ShopVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public array|null $lastError = null;

    public function __construct()
    {
        $this->lastError = null;
    }

    public function setColor(array|string|int $color, bool $create = true)
    {
        $result = null;
        $name = null;
        $code = null;
        $picture = null;
        $transparent = null;
        $storagePath = '/shop/colors';
        if (is_array($color)) {
            if (!empty($color['id'])) {
                $result = ShopColor::find($color['id']);
            }
            if (!empty($color['code'])) {
                $code = $color['code'];
                if (empty($result)) {
                    $result = ShopColor::where('code', $color['code'])->first();
                }
            }
            if (!empty($color['name'])) {
                $name = $color['name'];
                if (empty($result)) {
                    $result = ShopColor::where('name', $color['name'])->first();
                }
            }
            if (!empty($color['picture'])) {
                $picture = $this->setFileByLink($color['picture'], $storagePath);
            }
            if (!empty($color['transparent'])) {
                $transparent = $color['transparent'];
            }
        }
        elseif (is_numeric($color)) {
            $result = ShopColor::find($color);
        } elseif (is_string($color)) {
            $name = $color;
            $result = ShopColor::where('name', $color)->first();
        }

        if ($create && empty($result) && !empty($name)) {
            if (empty($code)) {
                $code = Str::slug($name);
            }
            try {
                $result = ShopColor::create([
                    'name' => $name,
                    'code' => $code,
                    'picture' => $picture,
                    'transparent' => !empty($transparent) ? 1 : 0,
                ]);
            }
            catch (\Exception $e) {
                $this->lastError = [
                    'message' => $e->getMessage(),
                    'exception' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]
                ];
            }
        }

        return $result;
    }

    public function setCurrency(array|string|int $currency, bool $create = true)
    {
        $result = null;
        $name = null;
        $code = null;
        $symbol = null;
        $symbol_code = null;
        $symbol_html = null;
        if (is_array($currency)) {
            if (!empty($currency['id'])) {
                $result = ShopCurrency::find($currency['id']);
            }
            if (!empty($currency['code'])) {
                $code = $currency['code'];
                if (empty($result)) {
                    $result = ShopCurrency::where('code', $currency['code'])->first();
                }
            }
            if (!empty($currency['name'])) {
                $name = $currency['name'];
                if (empty($result)) {
                    $result = ShopCurrency::where('name', $currency['name'])->first();
                }
            }
            if (!empty($currency['symbol'])) {
                $symbol = $currency['symbol'];
            }
            if (!empty($currency['symbol_code'])) {
                $symbol_code = $currency['symbol_code'];
            }
            if (!empty($currency['symbol_html'])) {
                $symbol_html = $currency['symbol_html'];
            }
        }
        elseif (is_numeric($currency)) {
            $result = ShopCurrency::find($currency);
        } elseif (is_string($currency)) {
            $name = $currency;
            $result = ShopCurrency::where('name', $currency)->first();
        }

        if ($create && empty($result) && !empty($name)) {
            if (empty($code)) {
                $code = Str::slug($name);
            }
            try {
                $result = ShopCurrency::create([
                    'name' => $name,
                    'code' => $code,
                    'symbol' => $symbol,
                    'symbol_code' => $symbol_code,
                    'symbol_html' => $symbol_html,
                ]);
            }
            catch (\Exception $e) {
                $this->lastError = [
                    'message' => $e->getMessage(),
                    'exception' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]
                ];
            }
        }

        return $result;
    }

    public function setPriceType(array|string|int $priceType, bool $create = true)
    {
        $result = null;
        $code = null;
        $name = null;
        $tax = null;
        if (is_array($priceType)) {
            if (!empty($priceType['id'])) {
                $result = ShopPriceType::find($priceType['id']);
            }
            if (!empty($priceType['code'])) {
                $code = $priceType['code'];
                if (empty($result)) {
                    $result = ShopPriceType::where('code', $priceType['code'])->first();
                }
            }
            if (!empty($priceType['name'])) {
                $name = $priceType['name'];
                if (empty($result)) {
                    $result = ShopPriceType::where('name', $priceType['name'])->first();
                }
            }
        } elseif (is_numeric($priceType)) {
                $result = ShopPriceType::find($priceType);
        } elseif (is_string($priceType)) {
            $name = $priceType;
            $result = ShopPriceType::where('code', $priceType)->orWhere('name', $priceType)->first();
        }

        if ($create && empty($result) && !empty($name)) {
            if (empty($code)) {
                $code = Str::slug($name);
            }

            if (!empty($priceType['tax'])) {
                $tax = $this->setTax($priceType['tax']);
            }

            try {
                $result = ShopPriceType::create([
                    'name' => $name,
                    'code' => $code,
                    'tax_id' => $tax->id ?? null
                ]);
            }
            catch (\Exception $e) {
                $this->lastError = [
                    'message' => $e->getMessage(),
                    'exception' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]
                ];
            }

        }
    }

    public function setTax(array|string|int $tax, bool $create = true, $byValue = false)
    {
        $result = null;
        $name = null;
        $value = null;
        $title = null;
        $description = null;
        if (is_array($tax)) {
            if (!empty($tax['id'])) {
                $result = ShopTax::find($tax['id']);
            }
            if (!empty($tax['name'])) {
                $name = $tax['name'];
                if (empty($result)) {
                    $result = ShopTax::where('name', $tax['name'])->first();
                }
            }
            if (!empty($tax['value'])) {
                $value = $tax['value'];
                if (empty($result)) {
                    $result = ShopTax::where('value', $tax['value'])->first();
                }
            }
            if (!empty($tax['title'])) {
                $title = $tax['title'];
            }
            if (!empty($tax['description'])) {
                $description = $tax['description'];
            }
        }
        elseif (is_numeric($tax)) {
            if ($byValue) {
                $result = ShopTax::where('value', $tax)->first();
            }
            else {
                $result = ShopTax::find($tax);
            }
        } elseif (is_string($tax)) {
            $name = $tax;
            $result = ShopTax::where('name', $tax)->first();
        }

        if ($create && empty($result) && !empty($name) && !empty($value)) {
            try {
                $result = ShopTax::create([
                    'name' => $name,
                    'value' => $value,
                    'title' => $title,
                    'description' => $description
                ]);
            }
            catch (\Exception $e) {
                $this->lastError = [
                    'message' => $e->getMessage(),
                    'exception' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]
                ];
            }
        }

        return $result;
    }

    public function setItem(array $itemRequest, int|ShopProduct $product, bool|null $createParam = null)
    {

        if (is_numeric($product)) {
            $product = ShopProduct::find($product);
        }
        elseif (is_string($product)) {
            $product = ShopProduct::where('slug',$product)->orWhere('title',$product)->first();
        }
        if (!is_object($product) || !($product instanceof ShopProduct)) {
            $product = null;
        }

        if (empty($product)) {
            return $this->notFound('Product not found');
        }

        //return $itemRequest;

        if (empty($itemRequest)) {
            return $this->error(400, 'Item data not found');
        }

        $validated = Validator::make($itemRequest, [
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

        $itemData = $itemRequest;

        $itemData['image'] = !empty($itemRequest['image']) ? $this->setFileByLink($itemRequest['image'], Setting::value('shop_image_path') ?? '/shop/item/images') : null;

        $item =  $product->items()->create($itemData);

        if ($item) {


            /* Prices */
            $defaultPriceType = ShopPriceType::defaultOrFirst();

            $defaultCurrency = ShopCurrency::defaultOrFirst();

            if (!empty($itemRequest['prices'])) {
                $prices = [];
                if (!empty($itemRequest['prices']['value'])) {
                    $prices[] = $itemRequest['prices'];
                } elseif (!empty($itemRequest['prices'][0])) {
                    $prices = $itemRequest['prices'];
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
                            $priceType = $this->setPriceType($price['priceType'],false);
                        }
                        if (empty($priceType)) {
                            $priceType = $defaultPriceType;
                        }
                        //get currency
                        $currency = null;
                        if (!empty($price['currency'])) {
                            $currency = $this->setCurrency($price['currency'],false);
                        }
                        if (empty($currency)) {
                            $currency = $defaultCurrency;
                        }
                        if (empty($currency) || empty($currency->id) || empty($priceType) || empty($priceType->id)) {
                            continue;
                        }
                        $priceData = [
                            'value' => intval(floatval(str_replace(',', '.', $price['value'])) * 100),
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

                    } catch (\Exception $e) {
                        $this->lastError = [
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

            /* if ($createParam === null && !empty($request->createParam)) {
                if ($request->createParam == 'true') {
                    $createParam = true;
                }
                elseif ($request->createParam == 'false') {
                    $createParam = false;
                }
            } */

            /* Parameters */
            if (!empty($itemRequest['parameters'])) {
                foreach ($itemRequest['parameters'] as $parameterItem) {

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

                    $parameterValue = ShopItemParameterValue::updateOrCreate(
                        ['item_id' => $item->id, 'parameter_id' => $parameter->id],
                        [$parameter->data_type->code . '_value' => $parameterItem['value']]
                    );
                }
            }
            /* /Parameters */

            /* Properties */
            if (!empty($itemRequest['properties'])) {
                foreach ($itemRequest['properties'] as $propertyItem) {

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

                    $propertyValue = ShopItemPropertyValue::updateOrCreate(
                        ['item_id' => $item->id, 'property_id' => $property->id],
                        [$property->data_type->code . '_value' => $propertyItem['value']]
                    );
                }
            }
            /* /Properties */

        }

        return $item;
    }

    public function setProperty(array|string|int $property, bool $create = true)
    {
        $result = null;
        $data_type = null;
        $name = null;
        $code = null;
        $description = null;
        $is_filter = 0;
        $multiple = 0;
        $options = null;

        if(is_numeric($property)) {
            $result = ShopItemProperty::find($property);
        }
        elseif (is_string($property)) {
            $result = ShopItemProperty::where('code',$property)->orWhere('name',$property)->first();
        }
        elseif (is_array($property)) {
            if (!empty($property['name'])) {
                $name = $property['name'];
            }
            if (!empty($property['code'])) {
                $code = $property['code'];
            }

            if (!empty($code) || !empty($name)) {
                $result = ShopItemProperty::where('code',$code)->orWhere('name',$name)->first();
            }

            if (empty($result) && $create) {
                if (!empty($property['data_type'])) {
                    if (is_numeric($property['data_type'])) {
                        $data_type = DataType::find($property['data_type']);
                    }
                    elseif (is_string($property['data_type'])) {
                        $data_type = DataType::where('code',$property['data_type'])->orWhere('name',$property['data_type'])->first();
                    }
                }
                if (!empty($property['description'])) {
                    $description = $property['description'];
                }
                if (!empty($property['is_filter'])) {
                    $is_filter = 1;
                }
                if (!empty($property['options'])) {
                    $options = 1;
                }
                if (!empty($property['options'])) {
                    $options = $property['options'];
                    if (is_array($options)) {
                        $options = json_encode($options);
                    }
                }

                if (!empty($data_type) && !empty($data_type->id) && !empty($name)) {
                    if (empty($code)) {
                        $code = Str::slug($name);
                    }
                    $propertyData = [
                        'data_type_id' => $data_type->id,
                        'name' => $name,
                        'code' => $code,
                        'description' => $description,
                        'is_filter' => $is_filter,
                        'multiple' => $multiple,
                        'options' => $options
                    ];

                    try {
                        $result = ShopItemProperty::create($propertyData);
                    }
                    catch (\Exception $e) {
                        $this->lastError = [
                            'message' => $e->getMessage(),
                            'exception' => [
                                'code' => $e->getCode(),
                                'message' => $e->getMessage(),
                                'file' => $e->getFile(),
                                'line' => $e->getLine(),
                                'trace' => $e->getTraceAsString()
                            ]
                        ];
                    }
                }
            }
        }

        return $result;
    }

    public function setParameter(array|string|int $parameter, bool $create = true)
    {
        $result = null;
        $data_type = null;
        $name = null;
        $code = null;
        $description = null;
        $is_filter = 0;
        $multiple = 0;
        $options = null;

        if(is_numeric($parameter)) {
            $result = ShopItemParameter::find($parameter);
        }
        elseif (is_string($parameter)) {
            $result = ShopItemParameter::where('code',$parameter)->orWhere('name',$parameter)->first();
        }
        elseif (is_array($parameter)) {
            if (!empty($parameter['name'])) {
                $name = $parameter['name'];
            }
            if (!empty($parameter['code'])) {
                $code = $parameter['code'];
            }

            if (!empty($code) || !empty($name)) {
                $result = ShopItemParameter::where('code',$code)->orWhere('name',$name)->first();
            }

            if (empty($result) && $create) {
                if (!empty($parameter['data_type'])) {
                    if (is_numeric($parameter['data_type'])) {
                        $data_type = DataType::find($parameter['data_type']);
                    }
                    elseif (is_string($parameter['data_type'])) {
                        $data_type = DataType::where('code',$parameter['data_type'])->orWhere('name',$parameter['data_type'])->first();
                    }
                }
                if (!empty($parameter['description'])) {
                    $description = $parameter['description'];
                }
                if (!empty($parameter['is_filter'])) {
                    $is_filter = 1;
                }
                if (!empty($parameter['options'])) {
                    $options = 1;
                }
                if (!empty($parameter['options'])) {
                    $options = $parameter['options'];
                    if (is_array($options)) {
                        $options = json_encode($options);
                    }
                }

                if (!empty($data_type) && !empty($data_type->id) && !empty($name)) {
                    if (empty($code)) {
                        $code = Str::slug($name);
                    }
                    $parameterData = [
                        'data_type_id' => $data_type->id,
                        'name' => $name,
                        'code' => $code,
                        'description' => $description,
                        'is_filter' => $is_filter,
                        'multiple' => $multiple,
                        'options' => $options
                    ];

                    try {
                        $result = ShopItemParameter::create($parameterData);
                    }
                    catch (\Exception $e) {
                        $this->lastError = [
                            'message' => $e->getMessage(),
                            'exception' => [
                                'code' => $e->getCode(),
                                'message' => $e->getMessage(),
                                'file' => $e->getFile(),
                                'line' => $e->getLine(),
                                'trace' => $e->getTraceAsString()
                            ]
                        ];
                    }
                }
            }
        }

        return $result;
    }

    public function setUnit(string|array $unit)
    {
        $result = null;
        $name = null;
        $abbr = null;
        $name_plural =null;
        $lang_name = null;
        $lang_name_plural = null;
        $step = null;
        $min = null;
        $max = null;
        if (is_array($unit)) {
            if (!empty($unit['name'])) {
                $name = $unit['name'];
            }
            if (!empty($unit['abbr'])) {
                $abbr = $unit['abbr'];
            }
            if (!empty($unit['name_plural'])) {
                $name_plural = $unit['name_plural'];
            }
            if (!empty($unit['lang_name'])) {
                $lang_name = $unit['lang_name'];
            }
            if (!empty($unit['lang_name_plural'])) {
                $lang_name_plural = $unit['lang_name_plural'];
            }
            if (!empty($unit['step'])) {
                $step = floatval(str_replace(',','.',$unit['step']));
            }
            if (!empty($unit['min'])) {
                $min = floatval(str_replace(',','.',$unit['min']));
            }
            if (!empty($unit['max'])) {
                $max = floatval(str_replace(',','.',$unit['max']));
            }
            if (empty($abbr) && !empty($name)) {
                $abbr = $name;
            }
            elseif (!empty($abbr) && empty($name)) {
                $name = $abbr;
            }
        }
        elseif (is_string($unit)) {
            $name = $abbr = $unit;
        }

        if (!empty($name) && !empty($abbr)) {
            $result = ShopUnit::where('name',$name)->orWhere('abbr',$abbr)->first();

            if(empty($result)) {
                $unitData = [
                    'name' => $name,
                    'name_plural' => $name_plural,
                    'abbr' => $abbr,
                    'lang_name' => $lang_name,
                    'lang_name_plural' => $lang_name_plural,
                    'min' => $min,
                    'max' => $max,
                ];
                if (!empty($step)) {
                    $unitData['step'] = $step;
                }
                $result = ShopUnit::create($unitData);
            }
        }

        return $result;
    }

    public function setVendor(string|array $vendor)
    {
        $result = null;
        $name = null;
        if (is_array($vendor) && !empty($vendor['name'])) {
            $name = $vendor['name'];
        }
        elseif (is_string($vendor)) {
            $name = $vendor;
        }
        if (!empty($name)) {
            $result = ShopVendor::where('name',$name)->first();
            if (empty($result)) {
                $result = ShopVendor::create([
                    'name' => $name
                ]);
            }
        }

        return $result;
    }

    public function setBrand(string|array $brand)
    {
        $result = null;
        $name = null;
        $slug = null;
        $logo = null;
        $image = null;
        $company = null;
        $description = null;
        $storagePath = '/shop/brand/logo'; //Setting::value('shop_image_path')  ?? '/shop/item/images';

        $query = ShopBrand::limit(1);

        if (is_array($brand)) {
            if (!empty($brand['slug'])) {
                $slug = $brand['slug'];
                $query->where('slug', $slug)->orWhere('slug', Str::slug($slug));
            } elseif (!empty($brand['name'])) {
                $name = $brand['name'];
                $slug = Str::slug($name);
                $query->where('name', $name);
            }
            if (!empty($brand['logo'])) {
                //$logo = $brand['logo'];
                $logo = $this->setFileByLink($brand['logo'], $storagePath);
            }
            if (!empty($brand['image'])) {
                //$image = $brand['image'];
                $image = $this->setFileByLink($brand['image'], $storagePath);
            }
            if (!empty($brand['company'])) {
                $company = $brand['company'];
            }
            if (!empty($brand['description'])) {
                $description = $brand['description'];
            }
        } elseif (is_string($brand)) {
            $name = $brand;
            $slug = Str::slug($name);
            $query->where('slug', $brand)->orWhere('name', $brand);
        }

        $result = $query->first();

        if (empty($result)) {

            if (!empty($name) && !empty($slug)) {
                $result = ShopBrand::create([
                    'name' => $name,
                    'slug' => $slug,
                    'logo' => $logo,
                    'image' => $image,
                    'company' => $company,
                    'description' => $description,
                ]);
            }
        }

        return $result;
        //return $result;

    }


    public function setCategory(string|array $category)
    {
        $result = null;
        $title = null;
        $slug = null;
        $parent = null;
        $image = null;
        $description = null;
        $active = null;
        $meta_keywords = null;
        $meta_description = null;
        $storagePath = '/shop/category/images'; //Setting::value('shop_image_path')  ?? '/shop/item/images';

        $query = ShopCategory::limit(1);

        if (is_array($category)) {
            if (!empty($category['slug'])) {
                $slug = $category['slug'];
                $query->where('slug', $slug)->orWhere('slug', Str::slug($slug));
            } elseif (!empty($category['title'])) {
                $title = $category['title'];
                $slug = Str::slug($title);
                $query->where('title', $title);
            }
            if (!empty($category['parent'])) {
                $parent = $this->setCategory($category['parent']);
            }
            if (!empty($category['image'])) {
                $image = $this->setFileByLink($category['image'], $storagePath);
            }
            if (!empty($category['active'])) {
                $active = $category['active'];
            }
            if (!empty($category['description'])) {
                $description = $category['description'];
            }
            if (!empty($category['meta_keywords'])) {
                $meta_keywords = $category['meta_keywords'];
            }
            if (!empty($category['meta_description'])) {
                $meta_description = $category['meta_description'];
            }
        } elseif (is_string($category)) {
            $title = $category;
            $slug = Str::slug($category);
            $query->where('slug', $category)->orWhere('title', $category);
        }

        $result = $query->first();

        if (empty($result)) {

            if (!empty($title) && !empty($slug)) {
                $result = ShopCategory::create([
                    'title' => $title,
                    'slug' => $slug,
                    'parent_id' => $parent->id ?? null,
                    'image' => $image,
                    'description' => $description,
                    'active' => !empty($active) ? 1 : 0,
                    'meta_keywords' => $meta_keywords,
                    'meta_description' => $meta_description,
                ]);
            }
        }

        /* return [
            'result' => $result,
            'error' => $this->lastError
        ]; */
        return $result;

    }

    public function setFileByLink(string $link, string|null $path = null)
    {
        $file = null;
        $file_path = null;

        if (!empty($link)) {
            try {
                $context = file_get_contents($link);
                if ($context) {
                    $path =  $path ?? '/shop/files';
                    $pathinfo = pathinfo($link);
                    $filename = uniqid();
                    if (!empty($pathinfo['extension'])) {
                        $filename .= '.' . $pathinfo['extension'];
                    } else {
                        $filename .= '.jpg';
                    }
                    $file = Storage::put($path . '/' . $filename, $context);
                    if ($file) {
                        $file_path = $path . '/' . $filename;
                    }
                }
            } catch (\Exception $e) {
                $this->lastError = [
                    'message' => $e->getMessage(),
                    'exception' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]
                ];
            }
        }

        return $file_path;
    }
}
