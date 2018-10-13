<?php

namespace App\Utilities;

use App\Models\Tag;

class Tagger
{
    public static function tagCafe($cafe, $tags, $userId)
    {
        // 遍历标签数据，分别存储每个标签，并建立其余咖啡店的关联
        foreach ($tags as $tag) {
            $name = trim($tag);
            // 如果标签已经存在则直接获取其实例
            $newCafeTag = Tag::firstOrNew(array('tag' => $name));
            $newCafeTag->tag = $name;
            $newCafeTag->save();
            // 将标签和咖啡店关联起来
            $cafe->tags()->syncWithoutDetaching([$newCafeTag->id => ['user_id' => $userId]]);
        }
    }
}