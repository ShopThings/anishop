<?php

use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    // case when testing
//    $q = \App\Models\User::case()
//        ->alias('custom_column')
//        ->when('id < ?', 'id', [15])
//        ->else('username')
//        ->build()
//        ->where(function ($q) {
//            return (new \App\Support\Model\CaseWhen($q))
//                ->when('is_admin = ?', '1', [1])
//                ->else('0')
//                ->build('where');
//        })
//        ->take(10);
//    dd($q->toSql());
//    dd($q->get()->pluck('custom_column'));

    // concat testing
//    $q = \App\Models\User::concat()
//        ->alias('full_name')
//        ->columns('first_name', ' ', 'last_name')
//        ->take(10);
//    dd($q->toSql());
//    dd($q->get());

//    $t = \App\Models\SendMethod::query()->orderBy('id', 'desc')->first();
//
//    /**
//     * @var Carbon $d
//     */
//    $d = $t->created_at;
//
//
//    var_dump(\verta($d)->timezone('UTC')->format(\App\Enums\Times\TimeFormatsEnum::DEFAULT_WITH_TIME->value));
//    echo '<br>';
//    var_dump(\verta($d)->format(\App\Enums\Times\TimeFormatsEnum::DEFAULT_WITH_TIME->value));

//    var_dump(now()->toString());
//    var_dump(\verta());
//    var_dump(\verta()->formatJalaliDatetime());

//    var_dump(!!preg_match('/^\d{4}-\d{0,2}$/', '1401-10'));
//    var_dump(\verta(Verta::parse('1401-09'))->toCarbon()->toString());
//    var_dump(\verta(Verta::parse('1401-09'))->toCarbon()->addMonth()->subDay()->toString());

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

//    var_dump(Storage::disk('public')->move('example.txt', 'test.txt'));
//    var_dump(Storage::disk('public')->move('test.txt', 'example.txt'));

    //------------------------------------------------------------------------------------
    // Test filesystem and move/copy operations
    //------------------------------------------------------------------------------------

//    $files = [
//        "b6-1-large.jpg",
//        "b6-1-medium.jpg",
//        "b6-1-small.jpg",
//        "b6-1.jpg",
//    ];
//    $files = [
//        "b66-1-large.jpg",
//        "b66-1-medium.jpg",
//        "b66-1-small.jpg",
//        "b66-1.jpg",
//    ];
//    $files = [
//        "b66-1-large.png",
//        "b66-1-medium.png",
//        "b66-1-small.png",
//        "b66-1.png",
//    ];
//
//    $storage = Storage::disk('public');
//
//    $source = '';
//    $dest = 'test/b66.jpg';
//    $sourceInfo = pathinfo($source);
//    $destInfo = pathinfo($dest);
//
//    if (empty($sourceInfo['dirname'])) {
//        $sourceInfo['dirname'] = '';
//    } else {
//        $sourceInfo['dirname'] = trim(
//            str_replace(
//                '\\',
//                '/',
//                str_replace('.', '', $sourceInfo['dirname'])
//            )
//        );
//    }
//
//    if (empty($destInfo['dirname'])) {
//        $destInfo['dirname'] = '';
//    } else {
//        $destInfo['dirname'] = trim(
//            str_replace(
//                '\\',
//                '/',
//                str_replace('.', '', $destInfo['dirname'])
//            )
//        );
//    }
//
//    foreach ($files as $file) {
//        $fileParts = explode('-', $file);
//        if (!empty($destInfo['extension'])) $fileParts[0] = $destInfo['filename'];
//        $newFile = implode('-', $fileParts);
//
//        $fileExtension = pathinfo($newFile, PATHINFO_EXTENSION);
//        $extPos = mb_strrpos($newFile, $fileExtension ?: '');
//        if ($extPos !== false && !empty($destInfo['extension'])) {
//            $newFile = mb_substr($newFile, 0, $extPos) . $destInfo['extension'];
//        }
//
//        $from = $sourceInfo['dirname'] . '/' . $file;
//        $to = $destInfo['dirname'] . '/' . $newFile;
//        if (empty($sourceInfo['extension']))
//            $from = $source . '/' . $file;
//        if (empty($destInfo['extension']))
//            $to = $dest . '/' . $newFile;
//
//        echo '<pre>';
//        var_dump($from, $to);
//        echo '</pre>';
//
//        var_dump($storage->move($from, $to));
//        var_dump($storage->copy($from, $to));
//    }
});

Route::fallback(function () {
    // ...
});
