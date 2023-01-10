<?php

namespace Elfcms\Shop\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        if (!empty($request->count)) {
            $count = intval($request->count);
        }
        if (empty($count)) {
            $count = 30;
        }
        $search = $request->search ?? '';

        if (!empty($search)) {
            $categories = ShopCategory::where('title','like',"%{$search}%")->orderBy($order, $trend)->paginate($count);
        }
        else {
            $categories = ShopCategory::flat(trend: $trend, order: $order, count: $count, search: $search);
        }

        return view('shop::admin.shop.category.index',[
            'page' => [
                'title' => __('shop::elf.categories'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ShopCategory::all();
        return view('shop::admin.shop.category.create',[
            'page' => [
                'title' => __('shop::elf.create_category'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->slug),
        ]);
        $validated = $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:Elfcms\Shop\Models\ShopCategory,slug',
            'image' => 'nullable|file|max:1024',
        ]);

        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image = $request->file()['image']->store('public/shop/category/image');
            $image_path = str_ireplace('public/','/storage/',$image);
        }

        $validated['image'] = $image_path;
        $validated['description'] = $request->description;
        $validated['active'] = empty($request->active) ? 0 : 1;
        $validated['parent_id'] = $request->parent_id;
        $validated['meta_keywords'] = $request->meta_keywords;
        $validated['meta_description'] = $request->meta_description;

        $category = ShopCategory::create($validated);

        return redirect(route('admin.shop.category'))->with('action_result',__('shop::elf.category_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopCategory  $shopCategory
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, ShopCategory $shopCategory)
    {
        if ($request->ajax()) {
            return $shopCategory;
        }
        else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopCategory  $shopCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopCategory $shopCategory)
    {
        $shopCategory->created = '';
        $shopCategory->updated = '';
        if (!empty($shopCategory->created_at)) {
            $shopCategory->created = date('d.m.Y H:i:s',strtotime($shopCategory->created_at));
        }
        if (!empty($shopCategory->updated_at)) {
            $shopCategory->updated = date('d.m.Y H:i:s',strtotime($shopCategory->updated_at));
        }
        $exclude = ShopCategory::childrenid($shopCategory->id,true);
        $categories = ShopCategory::whereNotIn('id',$exclude)->get();

        return view('shop::admin.shop.category.edit',[
            'page' => [
                'title' => __('shop::elf.edit_category') . ' #' . $shopCategory->id,
                'current' => url()->current(),
            ],
            'category' => $shopCategory,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopCategory  $shopCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopCategory $shopCategory)
    {
        if ($request->notedit && $request->notedit == 1) {
            $shopCategory->active = empty($request->active) ? 0 : 1;

            $shopCategory->save();

            return redirect(route('admin.shop.category'))->with('action_result',__('shop::elf.category_edited_successfully'));
        }
        else {
            $request->merge([
                'slug' => Str::slug($request->slug),
            ]);
            $validated = $request->validate([
                'title' => 'required',
                'slug' => 'required',
                'image' => 'nullable|file|max:1024',
            ]);
            if (ShopCategory::where('slug',$request->slug)->where('id','<>',$shopCategory->id)->first()) {
                return redirect(route('admin.shop.category.edit'))->withErrors([
                    'slug' => 'Category already exists'
                ]);
            }
            $image_path = $request->image_path;
            if (!empty($request->file()['image'])) {
                $image = $request->file()['image']->store('public/shop/categories/image');
                $image_path = str_ireplace('public/','/storage/',$image);
            }

            $shopCategory->title = $validated['title'];
            $shopCategory->slug = $validated['slug'];
            $shopCategory->parent_id = $request->parent_id;
            $shopCategory->image = $image_path;
            $shopCategory->description = $request->description;
            $shopCategory->active = empty($request->active) ? 0 : 1;
            $shopCategory->meta_keywords = $request->meta_keywords;
            $shopCategory->meta_description = $request->meta_description;

            $shopCategory->save();

            return redirect(route('admin.shop.category.edit',$shopCategory))->with('action_result',__('shop::elf.category_edited_successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopCategory  $shopCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopCategory $shopCategory)
    {
        if (!$shopCategory->delete()) {
            return redirect(route('admin.shop.category'))->withErrors(['categorydelerror'=>__('shop::elf.error_of_category_deleting')]);
        }

        return redirect(route('admin.shop.category'))->with('action_result',__('shop::elf.category_deleted_successfully'));
    }
}
