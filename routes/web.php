<?php

use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
//    var_dump(!!preg_match('/^\d{4}-\d{0,2}$/', '1401-10'));
//    var_dump(\verta(Verta::parse('1401-09'))->toCarbon()->toString());
//    var_dump(\verta(Verta::parse('1401-09'))->toCarbon()->addMonth()->toString());

//    Storage::disk('public')->put('example.txt', 'Contents');
//    var_dump(Storage::disk('public')->exists('example.txt'));
//    var_dump(Storage::disk('public')->size('example.txt'));
//    var_dump((new Carbon())->setTimestamp(Storage::disk('public')->lastModified('example.txt'))->diffForHumans());
//    var_dump(Storage::disk('public')->mimeType('example.txt'));
//    var_dump(Storage::disk('public')->path('example.txt'));
//    echo '<br>';
//    var_dump(Storage::disk('public')->url('example.txt'));
//    var_dump(Storage::disk('public')->path(''));
//    var_dump(is_dir(Storage::disk('public')->path('')));
//    var_dump(is_dir(storage_path()));
//    var_dump(is_dir('/storage/public/'));
});

Route::fallback(function () {
    // ...
});
