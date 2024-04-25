<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Aux\Image;
use Elfcms\Shop\Http\Requests\Admin\ShopItemStoreRequest;
use Elfcms\Shop\Http\Requests\Admin\ShopItemUpdateRequest;
use Elfcms\Shop\Models\Shop;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemPicture;
use Elfcms\Shop\Models\ShopImageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopItemPictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ShopItem $item)
    {
        /* if (empty($item->preview)) {
            $item->preview = '/elfcms/admin/modules/gallery/images/empty_270.png';
        }
        else {
            $item->preview = Image::cropCache($item->preview,270,270);
        } */
        if ($request->ajax()) {
            return view('elfcms::admin.shop.item_pictures.content.index',[
                'page' => [
                    'title' => __('shop::default.items'),
                    'current' => url()->current(),
                ],
                'item' => $item,
            ]);
        }
        return view('elfcms::admin.shop.item_pictures.index',[
            'page' => [
                'title' => __('shop::default.items'),
                'current' => url()->current(),
            ],
            'item' => $item,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, ShopItem $item)
    {
        $maxPosition = ShopItemPicture::where('item_id',$item->id)->max('position');
        $position = empty($maxPosition) && $maxPosition !== 0 ? 0 : $maxPosition + 1;

        $params = ShopImageSetting::getParams();
        if ($request->ajax()) {
            return view('elfcms::admin.shop.item_pictures.content.create',[
                'page' => [
                    'title' => __('shop::default.create_item'),
                    'current' => url()->current(),
                ],
                'item' => $item,
                'position' => $position,
                'params' => $params,
            ]);
        }
        return view('elfcms::admin.shop.item_pictures.create',[
            'page' => [
                'title' => __('shop::default.create_item'),
                'current' => url()->current(),
            ],
            'item' => $item,
            'position' => $position,
            'params' => $params,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Elfcms\Shop\Http\Requests\Admin\ShopItemStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopItemStoreRequest $request, ShopItem $item)
    {
        $request->validated();

        $validated = $request->all();

        $validated['item_id'] = $item->id;

        $position = $request->position;
        if (empty($position)) {
            $maxPosition = ShopItemPicture::where('item_id',$item->id)->max('position');
            $position = empty($maxPosition) && $maxPosition !== 0 ? 0 : $maxPosition + 1;
        }
        $validated['position'] = $position;

        $itemPicture = ShopItemPicture::create($validated);

        if (!empty($request->tags)) {
            foreach ($request->tags as $tagId) {
                $itemPicture->tags()->attach($tagId);
            }
        }

        if ($request->ajax()) {
            $data = $itemPicture->toArray();
            if ($data['thumbnail']) $data['thumbnail'] = file_path($data['thumbnail']);
            if ($data['preview']) $data['preview'] = file_path($data['preview']);
            if ($data['image']) $data['image'] = file_path($data['image']);
            return [
                'result' => 'success',
                'message' => __('shop::default.item_edited_successfully'),
                'data' => $data,
            ];
        }
        else {
            return redirect(route('admin.shop.items.pictures',$item))->with('elementsuccess',__('shop::default.item_created_successfully'));
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopItemPicture  $itemPicture
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ShopItem $item, ShopItemPicture $itemPicture)
    {
        if ($request->ajax()) {
            return view('elfcms::admin.shop.item_pictures.content.item',[
                'item' => $item,
                'itemPicture' => $itemPicture,
            ]);
        }
        return redirect(route('admin.shop.item_pictures.edit',['item'=>$item,'galleryItem'=>$itemPicture]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopItemPicture  $itemPicture
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ShopItem $item, ShopItemPicture $itemPicture)
    {
        $params = ShopImageSetting::getParams();
        if ($request->ajax()) {
            return view('elfcms::admin.shop.item_pictures.content.edit',[
                'page' => [
                    'title' => __('shop::default.edit_item'),
                    'current' => url()->current(),
                ],
                'item' => $item,
                'itemPicture' => $itemPicture,
                'params' => $params,
            ]);
        }
        return view('elfcms::admin.shop.item_pictures.edit',[
            'page' => [
                'title' => __('shop::default.edit_item'),
                'current' => url()->current(),
            ],
            'item' => $item,
            'itemPicture' => $itemPicture,
            'params' => $params,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Elfcms\Shop\Http\Requests\Admin\ShopItemUpdateRequest  $request
     * @param  \App\Models\ShopItemPicture  $itemPicture
     * @return \Illuminate\Http\Response
     */
    public function update(ShopItemUpdateRequest $request, ShopItem $item, ShopItemPicture $itemPicture)
    {
        if ($request->notedit && $request->notedit == 1) {
            $itemPicture->active = empty($request->active) ? 0 : 1;

            $itemPicture->save();

            if ($request->ajax()) {
                return [
                    'result' => 'success',
                    'message' => __('shop::default.item_edited_successfully'),
                    'data' => [
                        'id' => $itemPicture->id,
                        'image' => $itemPicture->image,
                        'position' => $itemPicture->position,
                    ]
                ];
            }

            return redirect(route('admin.gallery.items'))->with('elementsuccess',__('shop::default.item_edited_successfully'));
        }
        elseif ($request->posedit && $request->posedit == 1) {
            $itemPicture->position = $request->position ?? 0;

            $itemPicture->save();

            if ($request->ajax()) {
                return [
                    'result' => 'success',
                    'message' => __('shop::default.item_edited_successfully'),
                    'data' => [
                        'id' => $itemPicture->id,
                        'image' => $itemPicture->image,
                        'position' => $itemPicture->position,
                    ],
                ];
            }

            return redirect(route('admin.gallery.items'))->with('elementsuccess',__('shop::default.gallery_edited_successfully'));
        }
        else {
            $request->validated();

            $validated = $request->all();

            $validated['position'] = intval($request->position);

            $existTags = $itemPicture->tags->toArray();

            $newTags = $request->tags ? $request->tags : [];

            if (!empty($existTags)) {
                foreach ($existTags as $existTag) {
                    if (!in_array($existTag['id'],$newTags)) {
                        $itemPicture->tags()->detach($existTag['id']);
                    }
                    else {
                        $key = array_search($existTag['id'],$newTags);
                        unset($newTags[$key]);
                    }
                }
            }
            if (!empty($newTags)) {
                foreach ($newTags as $tagId) {
                    $itemPicture->tags()->attach($tagId);
                }
            }

            $itemPicture->update($validated);

            if ($request->ajax()) {
                return [
                    'result' => 'success',
                    'message' => __('shop::default.item_edited_successfully'),
                    'data' => $itemPicture->toArray(),
                ];
            }

            return redirect(route('admin.gallery.items',$item))->with('elementsuccess',__('shop::default.gallery_edited_successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopItem  $itemPicture
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopItemPicture $itemPicture)
    {
        //
    }
}
