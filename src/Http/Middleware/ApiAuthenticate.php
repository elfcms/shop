<?php

namespace Elfcms\Shop\Http\Middleware;

use Closure;
use Elfcms\Shop\Models\ShopApiToken;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ApiAuthenticate //extends Middleware
{
    public function handle($request, Closure $next)
    {
        //$request->headers->set('Accept', 'application/json');

        $token = $request->query('api_token');
        if(empty($token)){
            $token = $request->input('api_token');
        }
        if(empty($token)){
            $token = $request->bearerToken();
        }

        $getToken = ShopApiToken::where('token',$token)->first();
        if ($getToken && !empty($getToken->code)) {
            return $next($request);
        }

        $request->headers->set('Accept', 'application/json');
        return response()->json(['result'=>'error','message' => 'Unauthenticated','status'=>'401'], 401);

    }
    /* protected function authenticate($request, array $guards)
    {
        //Даем пользователю возможность передать токен (api-ключ) разными способами
        //1. в адресе запроса
        $token = $request->query('api_token');
        if(empty($token)){
            //2. Через url-form-encoded поля POST запроса
            $token = $request->input('api_token');
        }
        if(empty($token)){
            //3. Через заголовок Authorization: Bearer ......
            $token = $request->bearerToken();
        }

        //Сравниваем токен с тем, что хранится в наших настройках. Здесь можно заменить логику на свою. Например сделать поиск токена в базе
        //if($token === config('apitokens')[0]) return;
        $getToken = ShopApiToken::where('token',$token)->first();
        if ($getToken && !empty($getToken->code)) {
            return;
        }
        //В случае неуспеха, вызываем метод сообщающий о статусе "Неавторизован"
        $request->headers->set('Accept', 'application/json');
        $this->unauthenticated($request, $guards);
    } */
}
