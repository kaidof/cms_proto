<?php

namespace modules\news3\controllers;

class NewsApiController
{

    public function newsList()
    {
        $list = \modules\news3\models\NewsModel::all();

        return response()->json([
            'status' => 'ok',
            'message' => 'News list',
            'data' => $list->toArray(),
        ]);
    }

    public function newsById($id)
    {
        $item = \modules\news3\models\NewsModel::find($id);
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
