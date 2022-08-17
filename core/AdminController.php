<?php

namespace Core;

use Core\Middleware\Security;

abstract class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        new Security();
    }
}
