<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $helpers = ['url', 'form'];
    protected $session;

    public function initController($request, $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();
    }
}