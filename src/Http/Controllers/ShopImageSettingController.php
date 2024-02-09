<?php

namespace Elfcms\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopImageSetting;
use Illuminate\Http\Request;

class ShopImageSettingController extends Controller
{
    public function show() {
        //dd(ini_get('upload_max_filesize'));
        $settings = ShopImageSetting::orderBy('id','desc')->first() ?? new ShopImageSetting();
        return view('elfcms::admin.shop.image-settings',[
            'page' => [
                'title' => __('shop::default.image-settings'),
                'current' => url()->current(),
            ],
            'settings' => $settings,
            'filesize' =>  ini_get('upload_max_filesize') . 'B',
        ]);
    }

    public function save(Request $request) {
        $result = false;

        $data = $request->all();
        $data['is_preview'] = $data['is_preview'] ?? 0;
        $data['is_thumbnail'] = $data['is_thumbnail'] ?? 0;
        $data['is_watermark'] = $data['is_watermark'] ?? 0;

        $image_path = $data['watermark_path'];
        if (!empty($request->file()['watermark'])) {
            $image = $request->file()['watermark']->store('public/elfcms/shop/watermarks');
            $image_path = str_ireplace('public/','/storage/',$image);
        }
        $data['watermark'] = $image_path;

        $position = explode(',',$request->watermark_position ?? '');
        if (empty($position[0]) || !in_array(trim($position[0]),['left','center','right'])) {
            $position[0] = 'center';
        }
        if (empty($position[1]) || !in_array(trim($position[1]),['top','center','bottom'])) {
            $position[1] = 'center';
        }

        $data['watermark_position'] = implode(',',[trim($position[0]),trim($position[1])]);
        $data['watermark_size'] = $request->watermark_size ?? 50;
        $data['watermark_indent_h'] = $request->watermark_indent_h ?? 0;
        $data['watermark_indent_v'] = $request->watermark_indent_v ?? 0;

        $settings = ShopImageSetting::orderBy('id','desc')->first();

        if ($settings) {
            $result = $settings->update($data);
        }
        else {
            $result = ShopImageSetting::create($data);
        }

        if ($result) {
            return redirect(route('admin.shop.image-settings.show'))->with('success',__('elfcms::default.settings_edited_successfully'));
        }

        return redirect(route('admin.shop.image-settings.show'))->withErrors(['error'=>__('elfcms::default.error_saving_data')]);
    }
}
