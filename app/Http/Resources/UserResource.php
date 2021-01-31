<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // switch ($this->status){
        //     case -1:
        //         $this->status = '已删除';
        //         break;
        //     case 0:
        //         $this->status = '正常';
        //         break;
        //     case 1:
        //         $this->status = '冻结';
        //         break;
        // }
        return [
            'id'=>$this->id,
            'name' => $this->name,
            // 'status' => $this->status,
            'created_at'=>(string)$this->created_at,
            // 'updated_at'=>(string)$this->updated_at
        ];
    }
}