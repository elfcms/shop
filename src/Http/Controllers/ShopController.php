<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Basic\Models\Setting;
use Elfcms\Shop\Models\ShopBrand;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemProperty;
use Elfcms\Shop\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    private array|null $lastError = null;

    public function __construct()
    {
        $this->lastError = null;
    }

    public function setPropertyValue(ShopItem $item, ShopItemProperty|null $property = null, bool $create = false)
    {
        //
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
        $storagePath = '/storage/shop/brand/logo';//Setting::value('shop_image_path')  ?? '/shop/item/images';

        $query = ShopBrand::limit(1);

        if (is_array($brand)) {
            if (!empty($brand['slug'])) {
                $slug = $brand['slug'];
                $query->where('slug',$slug)->orWhere('slug',Str::slug($slug));
            }
            elseif (!empty($brand['name'])) {
                $name = $brand['name'];
                $slug = Str::slug($name);
                $query->where('name',$name);
            }
            if (!empty($brand['logo'])) {
                //$logo = $brand['logo'];
                $logo = $this->setFileByLink($brand['logo'],$storagePath);
            }
            if (!empty($brand['image'])) {
                //$image = $brand['image'];
                $image = $this->setFileByLink($brand['image'],$storagePath);
            }
            if (!empty($brand['company'])) {
                $company = $brand['company'];
            }
            if (!empty($brand['description'])) {
                $description = $brand['description'];
            }
        }
        elseif (is_string($brand)) {
            $name = $brand;
            $slug = Str::slug($name);
            $query->where('slug',$brand)->orWhere('name',$brand);
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

        return [
            'result' => $result,
            'error' => $this->lastError
        ];
        //return $result;

    }

    public function setFileByLink(string $link, string|null $path = null)
    {
        $file = null;
        $file_path = null;

        if (!empty($link)) {
            try {
                $context = file_get_contents($link);
                if ($context) {
                    $path = Setting::value('shop_image_path') ?? '/shop/item/images';
                    $pathinfo = pathinfo($link);
                    $filename = uniqid();
                    if (!empty($pathinfo['extension'])) {
                        $filename .= '.' . $pathinfo['extension'];
                    }
                    else {
                        $filename .= '.jpg';
                    }
                    $file = Storage::put('/public' . $path . '/' . $filename , $context);
                    if ($file){
                        $file_path = '/storage' . $path . '/' . $filename;
                    }
                }
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

        return $file_path;
    }
}
