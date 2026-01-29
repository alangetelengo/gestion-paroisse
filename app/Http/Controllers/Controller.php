<?php

namespace App\Http\Controllers;

use App\Traits\LogsErrors;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use LogsErrors;
}
