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

class ConnectionController extends Controller
{
    public function connection()
    {

        $container = Container::whereUserId(Auth::user()->id)->first();

        return view('user.connection', [
            "my_container" => $container
        ]);
    }
}
