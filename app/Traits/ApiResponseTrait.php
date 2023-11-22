<?php

namespace App\Traits;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

trait ApiResponseTrait
{
    /**
     * @param null $data
     * @param null $message
     * @param int $code
     * @param null $is_paginate
     * @return ResponseFactory|Response
     */
    public function apiResponseData($data = null, $message = null, $code = 200,$is_paginate=null)
    {
        $array = [
            'status' =>  1,
            'message' => $message,
            'data'=>$data,
        ];
        if(isset($is_paginate)){
            $array['is_paginate']=$is_paginate;
        }

        return response($array, $code);
    }

    /**
     * @param $status
     * @param null $message
     * @param int $code
     * @return ResponseFactory|Response
     */
    public function apiResponseMessage( $status,$message = null,$code = 200)
    {
        $array = [
            'status' =>  $status,
            'message' => $message,
            'data'=>null,
        ];

        return response($array, $code);
    }


    /**
     * @param $data
     * @param $request
     * @param $resource
     * @return ResponseFactory|Response
     */
    public function getAllData($data,$request,$resource)
    {
        App::setLocale($request->header('lang'));
        $page=$request->page * 20;
        $is_paginate=$data->count()  - ( $page + 20)> 0 ? true : false;
//        $data=$data->skip($page)->get();
        $data=$data->get();
       /*  if(count($data)> 0 && isset($request->stock_id)){
            $data->map(function($item) use ($request){
                $item->stock_filter=$request->stock_id;
            });
        } */
//        return $this->apiResponseData($resource::collection($data),__('responseMessage.success'),200,$is_paginate);
        return $this->apiResponseData($resource::collection($data),__('responseMessage.success'),200);
    }

}
