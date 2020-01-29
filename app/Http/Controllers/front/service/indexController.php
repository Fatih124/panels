<?php

namespace App\Http\Controllers\front\service;

use App\Http\Controllers\Controller;
use App\LanguageContent;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function index($slug)
    {
        $id = LanguageContent::getSlugToId(SERVICE_LANGUAGE,$slug);
        if ($id!=0)
        {
            $name = LanguageContent::getName(SERVICE_LANGUAGE,$id);
            $text = LanguageContent::getText(SERVICE_LANGUAGE,$id);
            $image = LanguageContent::getImage(SERVICE_LANGUAGE,$id);
            return view('front.service.index',['id'=>$id,'name'=>$name,'image'=>$image,'text'=>$text]);
        }
    }
}
