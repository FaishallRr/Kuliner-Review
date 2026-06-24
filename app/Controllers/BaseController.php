<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController: Controller dasar yang menyediakan
 * helper umum, session, dan flash message untuk semua controller.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance session.
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Helper yang di-load secara global.
     *
     * @var array<string>
     */
    protected $helpers = ['form', 'url', 'text'];

    /**
     * Inisialisasi controller: load helper dan session.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        $this->session = service('session');
    }
}