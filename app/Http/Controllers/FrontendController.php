<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\EmdCustomPage;
use App\Models\Media;
use App\Models\Tool;
use App\Repositories\EmdCustomFieldRepository;
use App\Repositories\EmdMicrosoftClarityRepository;
use App\Repositories\EmdUserTransactionRepository;
use App\Repositories\EmdWebUserRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        $tool = Tool::where('is_home', 1)->first();
        if ($tool) {
            return $this->get_tool($tool);
        } else {
            return view('main')->with('show_main', true);
        }
    }

    public function get_tool($tool)
    {
        //
        $custom = $this->CUSTOM_FUNCTION();
        //
        $emd_is_tool_premium = 0;
        $allow_json = [];
        $emd_tool_id = $tool->parent_id;
        foreach (EmdCustomFieldRepository::tool_wise_filter($emd_tool_id) as $custom_field) {
            $allow_json[$custom_field->key] = $custom_field->default_val;
        }
        $clarity_check = [];
        $get_clarity = EmdMicrosoftClarityRepository::clarity_get(emd_tool_id: (int) $emd_tool_id);
        if ($get_clarity) {
            $clarity_check['display'] = true;
            $clarity_check['percent'] = $get_clarity->show_percentage;
        } else {
            $clarity_check['display'] = false;
            $clarity_check['percent'] = 0;
        }
        // check premium for this tool
        if (auth()->guard('web_user_sess')->check()) {
            $user_id = auth()->guard('web_user_sess')->id();
            $is_not_free = EmdWebUserRepository::EmdIsUserPremium();
            if ($is_not_free) {
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
        $links = Tool::select('slug', 'lang')->where('parent_id', $tool->parent_id)->get();
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
            'custom' => $custom,
            'clarity_check' => json_decode(json_encode($clarity_check)),
        ]);
    }
    public function native_language_tool($slug = null)
    {
        $tool = Tool::where('slug', $slug)->first();
        if ($tool) {
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
        $tool = Tool::where([['lang', $lang], ['slug', $slug]])->first();
        if ($tool) {
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
        //
        $custom = $this->CUSTOM_BLOG_FUNCTION();
        //
        $blogs = get_blogs_by_limit(10);
        if (is_null($blogs)) {
            return view('layout.frontend.pages.blog')->with([
                'blogs' => null,
                'meta_title' => "title goes here",
                'meta_description' => "description goes here",
                'custom' => null,
            ]);
        }
        return view('layout.frontend.pages.blog')->with([
            'blogs' => $blogs,
            'meta_title' => "title goes here",
            'meta_description' => "description goes here",
            'custom' => $custom,
        ]);
    }
    public function single_blog($slug = null)
    {
        //
        $custom = $this->CUSTOM_BLOG_FUNCTION();
        //
        $blog_model = new Blog();
        $blog = $blog_model->get_blog($slug);
        return $this->load_single_blog($blog, $custom);
    }

    public function single_blog_other_language($lang = null, $slug = null)
    {
        //
        $custom = $this->CUSTOM_BLOG_FUNCTION();
        //
        $blog_model = new Blog();
        $blog = $blog_model->get_blog($slug, $lang);
        return $this->load_single_blog($blog, $custom);
    }

    public function load_single_blog($blog = null, $custom = null)
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

        return view('layout.frontend.pages.single-blog')->with([
            'blog' => $blog,
            'meta_title' => $blog['meta_title'],
            'meta_description' => $blog['meta_description'],
            'custom' => $custom,
            'parent' => $parent,
            'children' => $children,
            'show_blog_canonicals' => true,
        ]);
    }
    public function privacy_policy()
    {
        return view('layout.frontend.pages.privacy-policy');
    }
    public function terms_and_conditions()
    {
        return view('layout.frontend.pages.terms-and-conditions');
    }
    // Custom Function for your general purpose
    public function CUSTOM_FUNCTION()
    {
    }
    // Custom Function for your Blog related
    public function CUSTOM_BLOG_FUNCTION()
    {
    }
    //about-us
    public function about_us()
    {
        return view('layout.frontend.pages.about-us');
    }
    //contact-us
    public function contact_us()
    {
        return view('layout.frontend.pages.contact-us');
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

        $emd_custom_pages = EmdCustomPage::get();
        if ($emd_custom_pages->count() > 0) {
            foreach ($emd_custom_pages as $key => $item) {
                $links[] = url('/') . "/" . $item->slug;
            }
        }

        if (Route::has('privacy_policy')) {
            $links[] = route('privacy_policy');
        }
        if (Route::has('terms_and_conditions')) {
            $links[] = route('terms_and_conditions');
        }
        if (Route::has('page.about_us')) {
            $links[] = route('page.about_us');
        }
        if (Route::has('page.contact_us')) {
            $links[] = route('page.contact_us');
        }
        if (Route::has('emd_pricing_plans')) {
            $links[] = route('emd_pricing_plans');
        }
        $links = array_merge($links, $this->CUSTOM_SITEMAP_LINKS());
        $links = array_unique($links);
        return response(view('sitemap', ['links' => $links]))
            ->withHeaders([
                'Content-Type' => 'text/xml',
            ]);
    }
    public function CUSTOM_SITEMAP_LINKS()
    {
        $links = [];
        //
        // ....ADD LINKS HERE
        //
        return $links;
    }
    public function parafraseo(Request $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://143.110.156.45:7085/paraphrase_spanish',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('text' => $request->data, 'dlang' => config('constants.native_languge'), 'mode' => 3, 'website' => 'parafraseo.net'),
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function seggetions(Request $request)
    {
        $url = "https://www.spanishdict.com/thesaurus/".urlencode($request->word);
        $response = file_get_html($url);
        $synonyms = [];
        if(!empty($response)){
            $body = $response->find("body", 0);
            $seggetions = $body->find('.C3xwCUSe');
            if(count($seggetions) === 0) {
                // $seggetions = $body->find('.linkedWord--iG_sL6YI');
            }
            if(is_array($seggetions)){
                foreach($seggetions as $element)  {
                    $synonyms[] = $element->plaintext;
                }
            }
        }
        return $synonyms;
    }
}
