<?php

namespace App\Http\Controllers;

use App\Scholarship;
use App\Http\Controllers\Controller;
use App\User;
use App\Contract;
use App\Models\Container;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    public function list()
    {

        $container = Container::whereUserId(Auth::user()->id)->first();

        return view('user.gallery.list', [
            "my_container" => $container
        ]);
    }

    public function upload()
    {
        $container = Container::whereUserId(Auth::user()->id)->first();

        return view('user.gallery.upload', [
            "my_container" => $container
        ]);
    }
}
