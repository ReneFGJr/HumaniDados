<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Researcher extends BaseController
{
    public function index()
    {
        //
    }

    function profile($id): string
    {
        $XSD = new \App\Controllers\XsdViewer();
        $data = [];
        $data['cv'] = $XSD->extract($id);
        $sx = view('headers/header');
        $sx .= view('Pages/Person/researcher_profile', $data);
        $sx .= view('headers/footer');
        return $sx;
    }
}
