<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view_blogs');
        $blogs = Blog::select('id', 'parent_id', 'title', 'img_id', 'lang_key')->latest()->get();
        return view('admin.blog.index')->with([
            'blogs' => $blogs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('add_blog');
        $parents = Blog::whereColumn('id', 'parent_id')->get();
        $images = Media::get_media();
        return view('admin.blog.add')->with([
            'images' => @$images,
            'parents' => $parents,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('add_blog');
        $blog = Blog::create([
            'title' => $request->title,
            'parent_id' => $request->parent_id,
            'lang_key' => $request->lang_key,
            'status' => $request->pinch,
            'detail' => $request->blog_detail,
            'slug' => $request->slug,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'img_id' => $request->featured_img,
        ]);
        if ($request->parent_id == 0) {
            Blog::where('id', $blog->id)->update(['parent_id' => $blog->id]);
        }
        if ($blog) {
            return to_route('blog.index');
        } else {
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $this->authorize('blog_edit');
        $images = Media::get_media();
        $parents = Blog::whereColumn('id', 'parent_id')->get();
        if ($blog) {
            return view('admin.blog.edit')->with([
                'blog' => $blog,
                'images' => $images,
                'parents' => $parents,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $this->authorize('blog_edit');
        try {
            $blog->parent_id = $request->parent_id;
            $blog->lang_key = $request->lang_key;
            $blog->title = $request->title;
            $blog->status = $request->pinch;
            $blog->detail = $request->blog_detail;
            $blog->slug = $request->slug;
            $blog->meta_title = $request->meta_title;
            $blog->meta_description = $request->meta_description;
            $blog->img_id = $request->featured_img;
            $blog->save();
            return to_route('blog.index');
        } catch (\Throwable $th) {
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $this->authorize('blog_delete');
        $blog->delete();
        return back();
    }

    public function trash_list()
    {
        $this->authorize('view_trash_blog');
        $blogs = Blog::onlyTrashed()->get();
        return view('admin.blog.trash')->with(['blogs' => $blogs]);
    }

    public function blog_permanent_destroy($id)
    {
        $this->authorize('blog_delete');
        Blog::onlyTrashed()->find($id)->forceDelete();
        return back();
    }

    public function blog_restore($id)
    {
        $this->authorize('blog_restore');
        Blog::withTrashed()->find($id)->restore();
        return back();
    }
    public function content_translate(Request $request)
    {
        $this->authorize('blog_translation_btn');
        $current_blog = Blog::where('id', $request->blog_id)->first();
        $parenet_blog = Blog::where('id', $current_blog->parent_id)->first();
        try {
            $respn = Http::asForm()->post('http://143.110.156.45:7011/trans', [
                'text' => $parenet_blog->detail,
                'dlang' => $request->lang,
            ]);
        } catch (\Throwable $th) {
            $respn = $current_blog->detail;
        }
        $current_blog->detail = $respn;
        $current_blog->save();
        return $respn;
    }
}
