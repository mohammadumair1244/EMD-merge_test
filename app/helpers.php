<?php

use App\Models\Blog;
use App\Models\Media;
use App\Models\Setting;

if (!function_exists('get_setting_by_key')) {
    function get_setting_by_key($key)
    {
        $setting = Setting::where('key', $key)->first();
        if (is_null($setting)) {
            return null;
        } else {
            return $setting;
        }
    }
}
if (!function_exists('get_setting_by_section')) {
    function get_setting_by_section($section)
    {
        $setting = Setting::where('section', $section)->get();
        if (is_null($setting)) {
            return null;
        } else {
            return $setting;
        }
    }
}
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

// SPINNER FUNCTIONS
// dd('app/http/helpers/turbospin/spin.php');
require_once('Http/helpers/turbospin/spin.php');
function spinIt()
{
    if (isset($_GET['extensionPara'])) {

        $spined = turbo_spin(stripslashes($_GET['extensionPara']), stripslashes('en'));
        $spined = str_replace("'", "\'", $spined);
    } else {
        if (isset($_POST['ignore'])) {

            $ignreWrds = explode(',', $_POST['ignore']);
            foreach ($ignreWrds as $value) {
                if (strpos($_POST['data'], $value) == true) {
                    $_POST['data'] = str_replace($value, "<102>" . $value . "</102>", $_POST['data']);
                }
            }
        }

        $spined = turbo_spin(stripslashes($_POST['data']), stripslashes($_POST['lang']));

        if (isset($_POST['ignore'])) {
            $spined = str_replace('<102>', '', $spined);
            $spined = str_replace('</102>', '', $spined);
        }
        // $spined = turbo_spin(stripslashes($_POST['data']),stripslashes($_POST['lang']));
    }
    $spined = ucfirst($spined);
    if (isset($_POST['apiRequest'])) {
        return $spined;
    } else {
        echo $spined;
    }
    // $spined= preg_replace_callback("|([.!?]\s*\w)|", function ($matches) {
    // 	dump($matches);
    // 	return strtoupper($matches[0]);
    // } , $spined);
    // echo $spined;
    exit;
}
function spinIt_api()
{
    if (isset($_GET['extensionPara'])) {

        $spined = turbo_spin_api(stripslashes($_GET['extensionPara']), stripslashes('en'));
        $spined = str_replace("'", "\'", $spined);
    } else {
        if (isset($_POST['ignore'])) {

            $ignreWrds = explode(',', $_POST['ignore']);
            foreach ($ignreWrds as $value) {
                if (strpos($_POST['data'], $value) == true) {
                    $_POST['data'] = str_replace($value, "<102>" . $value . "</102>", $_POST['data']);
                }
            }
        }

        $spined = turbo_spin_api(stripslashes($_POST['data']), stripslashes($_POST['lang']));
    }

    return $spined;
}

function spinItBeta()
{
    if (isset($_GET['extensionPara'])) {

        $spined = turbo_spin_beta(stripslashes($_GET['extensionPara']), stripslashes('en'));
        $spined = str_replace("'", "\'", $spined);
    } else {
        if (isset($_POST['ignore'])) {

            $ignreWrds = explode(',', $_POST['ignore']);
            foreach ($ignreWrds as $value) {
                if (strpos($_POST['data'], $value) == true) {
                    $_POST['data'] = str_replace($value, "<102>" . $value . "</102>", $_POST['data']);
                }
            }
        }

        $spined = turbo_spin_beta(stripslashes($_POST['data']), stripslashes($_POST['lang']));

        if (isset($_POST['ignore'])) {
            $spined = str_replace('<102>', '', $spined);
            $spined = str_replace('</102>', '', $spined);
        }
        // $spined = turbo_spin(stripslashes($_POST['data']),stripslashes($_POST['lang']));
    }
    $spined = ucfirst($spined);
    if (isset($_POST['apiRequest'])) {
        return $spined;
    } else {
        $res2['message'] = '';
        $res2['content'] = $spined;
        $finalData = json_encode($res2);
        return $finalData;
        // return $spined;
    }
    // $spined= preg_replace_callback("|([.!?]\s*\w)|", function ($matches) {
    // 	dump($matches);
    // 	return strtoupper($matches[0]);
    // } , $spined);
    // echo $spined;
    exit;
}

function spinItApi()
{
    $spined = turbo_spin(stripslashes($_POST['data']), stripslashes($_POST['lang']), "api");
    $words = explode("md5Found", $spined['data']);
    $founds = $spined['founds'];
    // dump($words);
    // dump($founds);
    foreach ($words as $key => $word) {
        //if(empty(trim($word))) continue;
        if (!empty($founds[$word])) {
            $suggestions = explode("|", $founds[$word]);
            $arr[$key]['word'] = $suggestions[0];
            array_shift($suggestions);
            $arr[$key]['suggestions'] = $suggestions;
        } else {
            $arr[$key]['word'] = $word;
        }
    }

    $res =  json_encode($arr);
    if (isset($_POST['apiRequest'])) {
        return $res;
    } else {
        echo $res;
    }
    // $spined= preg_replace_callback("|([.!?]\s*\w)|", function ($matches) {
    // 	dump($matches);
    // 	return strtoupper($matches[0]);
    // } , $spined);
    // echo $spined;
    exit;
}
function spinPlag()
{
    $spined = turbo_spin(stripslashes($_POST['data']), stripslashes($_POST['lang']));
    $spined = ucfirst($spined);
    $spined = preg_replace_callback("|([.!?]\s*\w)|", function ($matches) {
        return strtoupper($matches[0]);
    }, $spined);
    $data['text'] = $spined;
    $data['id'] = $_POST['id'];
    return $data;
}
