<?php

namespace Elfcms\Shop\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Elfcms\Shop\Models\ShopProduct;
use Illuminate\Http\Request;

class ApiController extends Controller
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

    public function notFound()
    {
        $result = $this->error;
        $result['message'] = 'URL not found';
        $result['status'] = 404;

        return response()->json($result, 404);
    }

}
