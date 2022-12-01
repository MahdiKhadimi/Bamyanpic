<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
         $this->middleware('auth');
         $this->authorizeResource(Image::class);  
    }

    public function index(){
        $images = Image::visibleFor(request()->user())->latest()->paginate(15)->withQueryString();
        
        
        return view('image.index',compact('images'));
    }

    public function show(Image $image){
        return view('image.show',compact('image'));
    }

    public function create(){
        return view('image.create');
        
    }

    public function store(ImageRequest $request){
        Image::create($request->handleRequest());

        return \redirect()->route('images.index')->with('message','Image has been uploaded successfully');
    }

    public function edit(Image $image){

        
       //$this->authorize('edit',$image);
       
        return view('image.edit',compact('image'));
    }

    public function update(Image $image,ImageRequest $request){
        // $this->authorize('edit',$image);
        $image->update($request->handleRequest());

        return \redirect()->route('images.index')->with('message','Image has been updated successfully');
    }

    public function destroy(Image $image)
    {
        Gate::authorize('delete',$image);

         $image->delete();

        return \redirect()->route('images.index')->with('message','Image has been deleted successfully');
    }
}