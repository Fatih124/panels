<?php

namespace App\Helper;

use Image;
use File;

class imageHelper
{
    static function upload($name,$directory,$file)//Belirli isim ve dizinleri alıyoruz
    {
        $dir = "images/".$directory;//kayıtların tutulacağı ana dizin ve değişken ile oluştuacak dizin
        if (!empty($file))//boş değilse
        {
            if (!File::exists($dir))//olustur
            {
                File::makeDirectory($dir,0755,true);//dizini oluştur
            }
            $filename = $name."-".rand(1,9000).".".$file->getClientOriginalExtension();//dosya uzantısı alınıp dosya olustur
            $path = public_path($dir.'/'.$filename);//public dizine oluştur
            Image::make($file->getRealPath())->save($path);//gelen dosyayı dizine kaydet
            return $dir."/".$filename;//sonuç döndür
        }
        else
        {
            return "";//işlem başarısız ise boş dön
        }
    }
}