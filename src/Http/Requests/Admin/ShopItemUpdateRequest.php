<?php

namespace Elfcms\Shop\Http\Requests\Admin;

use Elfcms\Elfcms\Aux\Image;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ShopItemUpdateRequest extends FormRequest
{
    private $imageConfig;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'image' => __('elfcms::default.image'),
            'preview' => __('elfcms::default.preview'),
            'thumbnail' => __('elfcms::default.thumbnail'),
            'position' => __('elfcms::default.position'),
            'active' => __('elfcms::default.active'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'image' => 'nullable|file|max:' . ($this->imageConfig['image']['size'] ?? 10240),
            'preview' => 'nullable|file|max:' . ($this->imageConfig['preview']['size'] ?? 5120),
            'thumbnail' => 'nullable|file|max:' . ($this->imageConfig['thumbnail']['size'] ?? 2560),
            'position' => 'nullable',
            'active' => 'nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $imageConfig = config('elfcms.shop.images');
        $imageConfig['image']['size'] = $imageConfig['image']['size'] ?? 10240;
        $imageConfig['preview']['size'] = $imageConfig['preview']['size'] ?? 5120;
        $imageConfig['preview']['width'] = $imageConfig['preview']['width'] ?? 800;
        $imageConfig['preview']['height'] = $imageConfig['preview']['height'] ?? 800;
        $imageConfig['thumbnail']['size'] = $imageConfig['thumbnail']['size'] ?? 2560;
        $imageConfig['thumbnail']['width'] = $imageConfig['thumbnail']['width'] ?? 400;
        $imageConfig['thumbnail']['height'] = $imageConfig['thumbnail']['height'] ?? 400;

        $this->imageConfig = $imageConfig;

        $this->merge([
            'active' => empty($this->active) ? 0 : 1,
        ]);
    }

    protected function passedValidation ()
    {
        $image_path = $this->image_path;
        if (!empty($this->file()['image'])) {
            $image_path = $this->file()['image']->store('elfcms/shop/items/image');
        }
        $preview_path = $this->preview_path;
        if (!empty($this->file()['preview'])) {
            $preview_path = $this->file()['preview']->store('elfcms/shop/items/preview');
        }
        elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['preview']['auto']) || $imageConfig['preview']['auto'] === true)) {
            $preview_path = Image::resize($image,'elfcms/shop/items/preview/',$this->imageConfig['preview']['width'],$this->imageConfig['preview']['height']);
        }
        $thumbnail_path = $this->thumbnail_path;
        if (!empty($this->file()['thumbnail'])) {
            $thumbnail_path = $this->file()['thumbnail']->store('elfcms/shop/items/thumbnail');
        }
        elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['thumbnail']['auto']) || $imageConfig['thumbnail']['auto'] === true)) {
            $thumbnail_path = Image::resize($image,'elfcms/shop/items/thumbnail/',$this->imageConfig['thumbnail']['width'],$this->imageConfig['thumbnail']['height']);
        }
        $this->files->remove('image');
        $this->convertedFiles = null;
        $this->replace([
            'image' => $image_path,
            'preview' => $preview_path,
            'thumbnail' => $thumbnail_path,
            'position' => intval($this->position),
            'active' => $this->active,
        ]);
    }
}
