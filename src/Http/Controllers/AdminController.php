<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopCategory;
use Elfcms\Shop\Models\ShopProduct;
use Elfcms\Shop\Models\ShopItem;
use Elfcms\Shop\Models\ShopItemParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function csrf(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json(
                [
                    'result' => 'error',
                    'message' => 'Forbidden',
                    'data' => null
                ],
                403
            );
        }
        return [
            'result' => 'success',
            'message' => 'CSRF-token received successfully',
            'data' => [
                'token' => csrf_token()
            ]
        ];
    }

    public function shop()
    {
        //$param = ShopItemParameter::with('item_values')->find(1);
        //dd($param->item_values[0]->value);
        //$cat = ShopCategory::find(1);
        //dd($cat->product);
        //$g = ShopProduct::find(1);
        //dd($g->category);
        //$item = ShopItem::with('parameters')->find(1);
        //dd($item->parameters[0]->typeCode);
        /* $parentMenuData = config('elfcms.blog.menu');
        $menuData = [];
        foreach ($parentMenuData[0]['submenu'] as $key => $data) {
            $subdata = [];
            $text = '';

            if ($data['route'] == 'admin.blog.categories') {
                $categoriesCount = BlogCategory::count();
                $inactiveCategoriesCount = BlogCategory::where('active','<>',1)->count();
                $subdata[] = [
                    'title' => __('basic::elf.categories'),
                    'value' => $categoriesCount . ' (' . $inactiveCategoriesCount . ' ' . __('basic::elf.inactive') . ')'
                ];
            }

            if ($data['route'] == 'admin.blog.posts') {
                $postsCount = BlogPost::count();
                $inactivePostsCount = BlogPost::where('active','<>',1)->count();
                $subdata[] = [
                    'title' => __('basic::elf.posts'),
                    'value' => $postsCount . ' (' . $inactivePostsCount . ' ' . __('basic::elf.inactive') . ')'
                ];
            }

            if ($data['route'] == 'admin.blog.comments') {
                $commentsCount = BlogComment::count();
                $subdata[] = [
                    'title' => __('basic::elf.comments'),
                    'value' => $commentsCount
                ];
            }

            if ($data['route'] == 'admin.blog.likes') {
                $likesCount = BlogLike::count();
                $subdata[] = [
                    'title' => __('basic::elf.likes'),
                    'value' => $likesCount
                ];
            }

            if ($data['route'] == 'admin.blog.votes') {
                $votesCount = BlogLike::count();
                $subdata[] = [
                    'title' => __('basic::elf.votes'),
                    'value' => $votesCount
                ];
            }

            if ($data['route'] == 'admin.blog.tags') {
                $tagsCount = BlogTag::count();
                $subdata[] = [
                    'title' => __('basic::elf.tags'),
                    'value' => $tagsCount
                ];
            }
            $menuData[$key] = $data;

            $menuData[$key]['subdata'] = $subdata;
            $menuData[$key]['text'] = $text;

        } */
        return view('shop::admin.shop.index',[
            'page' => [
                'title' => 'Shop',
                'current' => url()->current(),
            ],
            //'menuData' => $menuData,
        ]);
    }

}
