<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function timeline()
    {
        // 每页多少条
        $limit = rq('limit') ?: 10;
        // 页码，从第limit条开始
        $skip = (rq('page') ? rq('page')-1 : 0) * $limit;

        $questions = question_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->get();

        $answers = answer_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($question->toArray(), $answer->toArray());
        $data = $questions->merge($answers);
        $data = $data->sortByDesc(function ($item) {
            return $item->created_at;
        });
        $data = $data -> values()->all();
        return suc(['data' => $data]);
    }
}
