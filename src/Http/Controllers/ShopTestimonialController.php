<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Http\Controllers\Sellings\CustomerController;
use Elfcms\Shop\Models\ShopProduct;
use Elfcms\Shop\Models\ShopTestimonial;
use Elfcms\Shop\Models\ShopTestimonialLike;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShopTestimonialController extends Controller
{
    public function add(ShopProduct $product, Request $request, $successCallback = null)
    {
        $customer = new CustomerController();
        $rating = intval($request->rating ?? 0);
        if ($rating > 5) {
            $rating = 5;
        }
        if ($rating < 1) {
            $rating = 1;
        }
        $testimonial = ShopTestimonial::create([
            'customer_id' => $customer->customer->id ?? null,
            'product_id' => $product->id,
            'item_id' => $request->item_id ?? null,
            'text' => $request->text ?? null,
            'rating' => $rating,
            'useful' => 0,
            'useless' => 0,
        ]);
        if ($request->ajax()) {
            return [
                'status' => $testimonial ? 'success' : 'error',
                'message' => $testimonial ? __('shop::default.testimonial_added_successfully') : __('shop::default.failed_to_add_testimonial'),
                'data' => []
            ];
        }
        if ($testimonial) {
            if (!empty($successCallback) && is_callable($successCallback)) {
                $successCallback();
            }
            return back()->with('success', __('shop::default.testimonial_added_successfully'));
        } else {
            return back()->with('error', __('shop::default.failed_to_add_testimonial'));
        }
    }

    public function edit(ShopTestimonial $testimonial, Request $request)
    {
        $customer = new CustomerController;
        if ($customer->customer->id != $testimonial->customer_id) {
            if ($request->ajax()) {
                return [
                    'status' => 'error',
                    'message' => null,
                    'data' => []
                ];
            }
            return back();
        }
        $testimonial->text = $request->text ?? null;
        if (!empty($request->rating)) {
            $rating = intval($request->rating ?? 0);
            if ($rating > 5) {
                $rating = 5;
            }
            if ($rating < 1) {
                $rating = 1;
            }
            $testimonial->rating = $rating;
        }
        $result = $testimonial->save();
        if ($request->ajax()) {
            return [
                'status' => $result ? 'success' : 'error',
                'message' => null,
                'data' => []
            ];
        }
        return back();
    }

    public function delete(ShopTestimonial $testimonial, Request $request)
    {
        $customer = new CustomerController;
        if ($customer->customer->id != $testimonial->customer_id) {
            if ($request->ajax()) {
                return [
                    'status' => 'error',
                    'message' => null,
                    'data' => []
                ];
            }
            return back();
        }
        $result = $testimonial->delete();
        if ($request->ajax()) {
            return [
                'status' => $result ? 'success' : 'error',
                'message' => null,
                'data' => []
            ];
        }
        return back();
    }

    public function setUseful(ShopTestimonial $testimonial, Request $request)
    {
        $customer = new CustomerController;
        $result = false;
        $like = null;
        if ($customer->customer->id != $testimonial->customer_id) {
            $like = ShopTestimonialLike::where('testimonial_id', $testimonial->id);
            if ($customer->customer && !empty($customer->customer->id)) {
                $like->where('customer_id', $customer->customer->id);
                if (!empty($customer->tmp_user)) {
                    $like->orWhere('tmp_user', $customer->tmp_user->uuid);
                }
            } elseif (!empty($customer->tmp_user)) {
                $like->where('tmp_user', $customer->tmp_user->uuid);
            } else {
                $like = null;
            }
            $like = $like->first() ?? null;
            if ($like) {
                if (!empty($request->liked)) {
                    $like->useful = 0;
                    $like->useless = 0;
                    $result = $like->save();
                } else {
                    $like->useful = 1;
                    $like->useless = 0;
                    $result = $like->save();
                }
            } else {
                $result = $testimonial->likes()->create([
                    'customer_id' => $customer->customer->id ?? null,
                    'tmp_user' => $customer->tmp_user->uuid ?? null,
                    'useful' => 1,
                    'useless' => 0,
                ]);
            }
        }
        if ($request->ajax()) {
            return [
                'status' => $result ? 'success' : 'error',
                'message' => null,
                'data' => [
                    'testimonial_id' => $testimonial->id,
                    'state' => $like->useful ?? null,
                    'usefulCount' => $testimonial->usefulCount,
                    'uselessCount' => $testimonial->uselessCount,
                ]
            ];
        }
        return back();
    }

    public function setUseless(ShopTestimonial $testimonial, Request $request)
    {
        $customer = new CustomerController;
        $result = false;
        $like = null;
        if ($customer->customer->id != $testimonial->customer_id) {
            $like = ShopTestimonialLike::where('testimonial_id', $testimonial->id);
            if ($customer->customer && !empty($customer->customer->id)) {
                $like->where('customer_id', $customer->customer->id);
                if (!empty($customer->tmp_user)) {
                    $like->orWhere('tmp_user', $customer->tmp_user->uuid);
                }
            } elseif (!empty($customer->tmp_user)) {
                $like->where('tmp_user', $customer->tmp_user->uuid);
            } else {
                $like = null;
            }
            $like = $like->first() ?? null;
            if ($like) {
                if (!empty($request->liked)) {
                    $like->useful = 0;
                    $like->useless = 0;
                    $result = $like->save();
                } else {
                    $like->useful = 0;
                    $like->useless = 1;
                    $result = $like->save();
                }
            } else {
                $result = $testimonial->likes()->create([
                    'customer_id' => $customer->customer->id ?? null,
                    'tmp_user' => $customer->tmp_user->uuid ?? null,
                    'useful' => 0,
                    'useless' => 1,
                ]);
            }
        }
        if ($request->ajax()) {
            return [
                'status' => $result ? 'success' : 'error',
                'message' => null,
                'data' => [
                    'testimonial_id' => $testimonial->id,
                    'state' => $like->useless ?? null,
                    'usefulCount' => $testimonial->usefulCount,
                    'uselessCount' => $testimonial->uselessCount,
                ]
            ];
        }
        return back();
    }
}
