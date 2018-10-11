<?php

namespace App\Utilities;

use App\Models\Tag;

class Tagger
{
    public static function tagCafe($cafe, $tags, $userId)
    {
        foreach ($tags as $tag) {
            $name = trim($tag);
            $newCafeTag = Tag::firstOrNew(array('tag' => $name));
            $newCafeTag->tag = $name;
            $newCafeTag->save();
            // 将标签和咖啡店关联起来
            $cafe->tags()->syncWithoutDetaching([$newCafeTag->id => ['user_id' => $userId]]);
        }
    }
}