<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Basic\Models\Setting;
use Elfcms\Shop\Models\ShopBrand;
use Elfcms\Shop\Models\ShopCategory;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemProperty;
use Elfcms\Shop\Models\ShopProduct;
use Elfcms\Shop\Models\ShopUnit;
use Elfcms\Shop\Models\ShopVendor;
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
                    $file = Storage::put('/public' . $path . '/' . $filename, $context);
                    if ($file) {
                        $file_path = '/storage' . $path . '/' . $filename;
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
