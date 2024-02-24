<?php

use App\Models\Blog;
use App\Models\Media;

if (!function_exists('get_blogs_by_limit')) {
    function get_blogs_by_limit($limit, $except_id = null)
    {
        if (!is_null($except_id)) {
            $blogs = Blog::orderBy('created_at', 'desc')->where('id', '!=', $except_id)->take($limit)->get();
        } else {
            $blogs = Blog::orderBy('created_at', 'desc')->limit($limit)->get();
        }
        $media = new Media();
        if ($blogs) {
            $blogs = $blogs->toArray();
            foreach ($blogs as $key => $value) {
                $parent_key = $key;
                $img_id = $value['img_id'];
                $images = $media->get_images_by_id($img_id);
                if (!is_null($images)) {
                    foreach ($images as $value) {
                        $arr[$value['dimension']] = $value['path'];
                    }
                    $blogs[$parent_key]['images'] = $arr;
                } else {
                    $blogs[$parent_key]['images'] = null;
                }
            }
            // uncomment or use (object) $item in loop to use as object
            // $blogs = collect($blogs)->map(function ($item) {
            //     return (object) $item;
            // });
            return $blogs;
        } else {
            return [];
        }
    }
}
