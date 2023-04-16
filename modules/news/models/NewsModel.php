<?php

declare(strict_types=1);

namespace modules\news\models;

use core\models\BaseModel;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $is_active
 * @property string $created_at
 */
class NewsModel extends BaseModel
{
    protected $table = 'news';
}
