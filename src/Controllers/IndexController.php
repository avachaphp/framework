<?php

declare(strict_types=1);

namespace App\Controllers;

use Avacha\Components\Language\Exceptions\CompilationError;
use Avacha\Http\Controller;
use Avacha\Support\Html\Html;
use function Avacha\Components\compile;
use function Avacha\Components\render;

class IndexController extends Controller
{
    public function index(): string
    {
        return render('avacha/changelog');
    }
}
