<?php

use Illuminate\Support\Facades\DB;

DB::table('posts')
    ->where('image', 'not like', '%.jpg')
    ->update(['image' => DB::raw("REPLACE(image, 'jpg', '.jpg')")]);

echo DB::table('posts')->where('image', 'like', '%.jpg')->count()." posts corregidos\n";
