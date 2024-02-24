<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dev\CustomArrayController;
use App\Models\Blog;
use App\Models\EmdCustomPage;
use App\Models\Media;
use App\Models\Tool;
use App\Repositories\EmdCustomFieldRepository;
use App\Repositories\EmdMicrosoftClarityRepository;
use App\Repositories\EmdUserTransactionRepository;
use App\Repositories\EmdWebUserRepository;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

class FrontendController extends Controller
{
    public static $custom_array_tool_page_static = [];
    public static $custom_array_blog_page_static = [];
    public static $custom_array_sitemap_link_static = [];
    public function __construct()
    {
    }
    public function index()
    {
        // try {
        //     $home_tool_native_redis = Redis::hGetAll('home_tool_native');
        // } catch (\Throwable $th) {
        //     Log::channel('redis_info')->info("Redis Connection error");
        //     $home_tool_native_redis = "";
        // }
        // if (!empty($home_tool_native_redis) && count($home_tool_native_redis) > 0) {
        //     $tool = json_decode(json_encode($home_tool_native_redis, false));
        // } else {
        //     Log::channel('redis_info')->info("home tool query run");
        $tool = Tool::where('is_home', 1)->first();
        // }
        if ($tool) {
            return $this->get_tool($tool);
        } else {
            return view('main')->with('show_main', true);
        }
    }

    public function get_tool($tool)
    {
        self::$custom_array_tool_page_static = [];
        CustomArrayController::custom_array_function_tool_pages(tool: $tool);

        $emd_is_tool_premium = 0;
        $allow_json = [];
        $emd_tool_id = $tool->parent_id;
        // try {
        //     $custom_field_redis = Redis::hGetAll('custom_field_redis_' . $emd_tool_id);
        // } catch (\Throwable $th) {
        //     $custom_field_redis = "";
        // }
        // if (!empty($custom_field_redis) && count($custom_field_redis) > 0) {
        //     foreach ($custom_field_redis as $key => $single_custom_field) {
        //         $allow_json[$key] = (int) $single_custom_field;
        //     }
        // } else {
        foreach (EmdCustomFieldRepository::tool_wise_filter($emd_tool_id) as $custom_field) {
            $allow_json[$custom_field->key] = $custom_field->default_val;
            // Log::channel('redis_info')->info("custom field query run tool_id: " . $emd_tool_id . " custom_field_id: " . $custom_field->id);
        }
        // }
        $clarity_check = [];
        $clarity_check['display'] = false;
        $clarity_check['percent'] = 0;
        if ((config('emd_setting_keys.emd_microsoft_clarity_key') ?? '') != "") {
            $get_clarity = EmdMicrosoftClarityRepository::clarity_get(emd_tool_id: (int) $emd_tool_id);
            if ($get_clarity) {
                $clarity_check['display'] = true;
                $clarity_check['percent'] = $get_clarity;
                // $clarity_check['percent'] = $get_clarity->show_percentage;
            }
        }
        // check premium for this tool
        if (auth()->guard('web_user_sess')->check()) {
            $is_not_free = EmdWebUserRepository::EmdIsUserPremium();
            if ($is_not_free) {
                $user_id = auth()->guard('web_user_sess')->id();
                $is_available_plan = EmdUserTransactionRepository::AvailablePlanDetail((int) $emd_tool_id, (int) $user_id);
                if (count($is_available_plan->toArray()) > 0) {
                    $emd_is_tool_premium = $is_not_free == 1 ? 1 : 0;
                    foreach ($is_available_plan as $is_available_plan_item) {
                        $query_allow_json = json_decode($is_available_plan_item->allow_json);
                        foreach ($query_allow_json as $key_item => $value_item) {
                            if (@$allow_json[$key_item] < $value_item) {
                                $allow_json[$key_item] = $value_item;
                            }
                        }
                    }
                }
            }
        }
        //
        $is_home = 0;
        if (!$tool->is_home) {
            if ($tool->id == $tool->parent_id) {
                $parent_slug = $tool->slug;
                $slug_n_home['is_home'] = 0;
            } else {
                $parentTool = Tool::select('slug', 'is_home')->where('id', $tool->parent_id)->first();
                $parent_slug = $parentTool->slug;
                $slug_n_home['is_home'] = $parentTool->is_home;
            }
            if ($slug_n_home['is_home'] != 1) {
                $view = 'layout.frontend.pages.' . $parent_slug;
                // $view = 'layout.frontend.pages.emd-tools-pages.' . $parent_slug;
            } else {
                $view = 'layout.frontend.pages.home';
                // $view = 'layout.frontend.pages.emd-tools-pages.home';
                $is_home = 1;
            }
        } else {
            $view = 'layout.frontend.pages.home';
            // $view = 'layout.frontend.pages.emd-tools-pages.home';
            $parent_slug = $tool->slug;
            $is_home = 1;
        }

        // try {
        //     $tool_canonicals_redis = Redis::get('tool_canonicals_redis_' . $tool->parent_id);
        // } catch (\Throwable $th) {
        //     $tool_canonicals_redis = "";
        // }
        // if (!empty($tool_canonicals_redis) && $tool_canonicals_redis != null && gettype($tool_canonicals_redis) == "string") {
        //     $links = json_decode($tool_canonicals_redis);
        // } else {
        //     Log::channel('redis_info')->info("tool canonicals query run: " . $tool->parent_id);
        $links = Tool::select('name', 'slug', 'lang')->where('parent_id', $tool->parent_id)->get();
        // }

        return view($view)->with([
            'tool' => $tool,
            'content' => json_decode($tool->content),
            'links' => $links,
            'parent_slug' => $parent_slug,
            'is_home' => $is_home,
            'meta_title' => $tool->meta_title,
            'meta_description' => $tool->meta_description,
            'show_canonicals' => true,
            'emd_is_tool_premium' => $emd_is_tool_premium,
            'allow_json' => json_decode(json_encode($allow_json)),
            'custom_array' => self::$custom_array_tool_page_static,
            'clarity_check' => json_decode(json_encode($clarity_check)),
        ]);
    }
    public function native_language_tool($slug = null)
    {
        // try {
        //     $other_tool_native_redis = Redis::hGetAll('other_tool_native_' . $slug);
        // } catch (\Throwable $th) {
        //     $other_tool_native_redis = "";
        // }
        // if (!empty($other_tool_native_redis) && count($other_tool_native_redis) > 0) {
        //     $tool = json_decode(json_encode($other_tool_native_redis, false));
        // } else {
        //     Log::channel('redis_info')->info("other native tool query run: " . $slug);
        $tool = Tool::where('slug', $slug)->first();
        // }

        if ($tool && @$tool?->slug === $slug) {
            if ($tool->is_home) {
                return to_route('home', status: 301);
            }
            if ($tool->id != $tool->parent_id) {
                abort(404);
                exit;
            }
            return $this->get_tool($tool);
        } else {
            abort(404);
        }
    }
    public function other_language_tool($lang = null, $slug = null)
    {
        $native_language = config('constants.native_languge');
        // try {
        //     $other_lang_tool_redis = Redis::hGetAll('other_lang_tool_' . $lang . "_" . $slug);
        // } catch (\Throwable $th) {
        //     $other_lang_tool_redis = "";
        // }
        // if (!empty($other_lang_tool_redis) && count($other_lang_tool_redis) > 0) {
        //     $tool = json_decode(json_encode($other_lang_tool_redis, false));
        // } else {
        //     Log::channel('redis_info')->info("other lang tool query run: " . $lang . " | " . $slug);
        $tool = Tool::where([['lang', $lang], ['slug', $slug]])->first();
        // }
        if ($tool && @$tool?->lang === $lang && @$tool?->slug === $slug) {
            if ($native_language == $lang) {
                return to_route('native_language_tool', ['slug' => $slug], 301);
            } else {
                return $this->get_tool($tool);
            }
        } else {
            abort(404);
        }
    }

    public function blog()
    {
        self::$custom_array_blog_page_static = [];
        CustomArrayController::custom_array_function_blog_pages();

        $blogs = get_blogs_by_limit(10);
        if (is_null($blogs)) {
            return view('layout.frontend.pages.blog')->with([
                'blogs' => null,
                'meta_title' => "title goes here",
                'meta_description' => "description goes here",
                'custom_array' => self::$custom_array_blog_page_static,
            ]);
        }
        $view_page = 'layout.frontend.pages.blog';
        // $view_page='layout.frontend.pages.emd-blog-pages.blog';
        return view($view_page)->with([
            'blogs' => $blogs,
            'meta_title' => "title goes here",
            'meta_description' => "description goes here",
            'custom_array' => self::$custom_array_blog_page_static,
        ]);
    }
    public function single_blog($slug = null)
    {
        self::$custom_array_blog_page_static = [];
        CustomArrayController::custom_array_function_blog_pages(slug: $slug);

        $blog_model = new Blog();
        $blog = $blog_model->get_blog($slug);
        return $this->load_single_blog($blog, self::$custom_array_blog_page_static);
    }

    public function single_blog_other_language($lang = null, $slug = null)
    {
        self::$custom_array_blog_page_static = [];
        CustomArrayController::custom_array_function_blog_pages(lang: $lang, slug: $slug);

        $blog_model = new Blog();
        $blog = $blog_model->get_blog($slug, $lang);
        return $this->load_single_blog($blog, self::$custom_array_blog_page_static);
    }

    public function load_single_blog($blog = null, $custom_array = [])
    {
        if (is_null($blog)) {
            abort(404);
        } else {
            $parent = $blog->parent;
            $children = $parent->children;
            $blog = $blog->toArray();
        }
        $media = new Media();
        $img_id = $blog['img_id'];
        $images = $media->get_images_by_id($img_id);
        if ($images) {
            foreach ($images as $value) {
                $arr[$value['dimension']] = $value['path'];
            }
            $blog['images'] = $arr;
        } else {
            $blog['images'] = null;
        }

        $view_page = 'layout.frontend.pages.single-blog';
        // $view_page='layout.frontend.pages.emd-blog-pages.single-blog';
        return view($view_page)->with([
            'blog' => $blog,
            'meta_title' => $blog['meta_title'],
            'meta_description' => $blog['meta_description'],
            'parent' => $parent,
            'children' => $children,
            'show_blog_canonicals' => true,
            'custom_array' => $custom_array,
        ]);
    }
    // sitemap
    public function sitemap()
    {
        $tools = Tool::whereColumn('id', 'parent_id')->with('children')->get();
        $links = [];
        if ($tools->count() > 0) {
            foreach ($tools as $key => $item) {
                foreach ($item->children as $c_key => $c_item) {
                    if ($c_item->is_home) {
                        $links[] = route('home');
                    } else {
                        if ($c_item->id == $c_item->parent_id) {
                            $links[] = urldecode(route('native_language_tool', ['slug' => $c_item->slug]));
                        } else {
                            $links[] = urldecode(route('other_language_tool', ['lang' => $c_item->lang, 'slug' => $c_item->slug]));
                        }
                    }
                }
            }
        }

        if (Route::has('page.blog')) {
            $links[] = route('page.blog');
        }

        $blogs = Blog::whereColumn('id', 'parent_id')->with('children')->get();
        if ($blogs->count() > 0) {
            foreach ($blogs as $key => $item) {
                // foreach ($item->children as $c_key => $c_item) {
                if ($item->id == $item->parent_id) {
                    $links[] = urldecode(route('page.single_blog', ['slug' => $item->slug]));
                } else {
                    $links[] = urldecode(route('single_blog_other_language', ['lang' => $item->lang_key, 'slug' => $item->slug]));
                }
                // }
            }
        }

        $emd_custom_pages = EmdCustomPage::sitemap()->get();
        if ($emd_custom_pages->count() > 0) {
            foreach ($emd_custom_pages as $key => $item) {
                $links[] = url('/') . "/" . $item->slug;
            }
        }
        self::$custom_array_sitemap_link_static = [];
        CustomArrayController::custom_array_function_sitemap_links();

        $links = array_merge($links, self::$custom_array_sitemap_link_static);
        $links = array_unique($links);
        return response(view('sitemap', ['links' => $links]))
            ->withHeaders([
                'Content-Type' => 'text/xml',
            ]);
    }
}
