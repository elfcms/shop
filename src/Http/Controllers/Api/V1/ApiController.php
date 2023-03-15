<?php

namespace Elfcms\Shop\Http\Controllers\Api\V1;

use Elfcms\Shop\Http\Controllers\ShopController;
use Elfcms\Shop\Models\ShopProduct;
use Illuminate\Http\Request;

class ApiController extends ShopController
{

    protected $empty = [
        'result' => 'success',
        'message' => 'No data found',
        'count' => 0,
        'data' => null,
        'status' => 200,
    ];

    protected $error = [
        'result' => 'error',
        'message' => 'Server error',
        'status' => 500,
    ];

    public function notFound($message = null)
    {
        $result = $this->error;
        $result['message'] = $message ?? 'URL not found';
        $result['status'] = 404;

        return response()->json($result, 404);
    }

    public function error($status, $message = '')
    {
        if (!empty($message)) {
            $this->error['message'] =  $message;
            $this->error['status'] =  $status;
        }

        return response()->json($this->error, $status);
    }

    public function pageNotFound()
    {
        return $this->notFound('URL not found');
    }

    public function health()
    {
        return response()->json(['result'=>'success','message'=>'OK','status'=>200], 200);
    }

    public function version()
    {
        return response()->json(['result'=>'success','message'=>'1.0','data'=>'Version 1.0','status'=>200], 200);
    }

}
