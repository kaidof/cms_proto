<?php

namespace modules\news\controllers;

use modules\news\models\NewsModel;

class NewsApiController
{

    public function newsList()
    {
        $list = NewsModel::all();

        return response()->json([
            'data' => $list->toArray(),
        ]);
    }

    public function newsById($id)
    {
        $item = NewsModel::find($id);
        if ($item->id) {
            return response()->json([
                'id' => $item->id,
                'title' => $item->title ?? null,
                'content' => $item->content ?? null,
                'created_at' => $item->created_at ?? null,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'News item not found',
            ], 404);
        }
    }
}
