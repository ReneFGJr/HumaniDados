<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $sx = view('headers/header');
        $sx .= view('welcome_message');
        $sx .= view('headers/footer');
        return $sx;
    }

    public function about(): string
    {
        $sx = view('headers/header');
        $sx .= view('Pages/About');
        $sx .= view('headers/footer');
        return $sx;
    }


    public function painel(): string
    {
        $sx = view('headers/header');
        $sx .= view('Pages/Painel');
        $sx .= view('headers/footer');
        return $sx;
    }
    public function page($arg1='',$arg2='')
        {
            $sx = view('headers/header');
            switch($arg1)
                {
                    case 'about':
                        $sx .= view('Pages/about');
                        break;
                    case 'cyracris':
                        $sx .= view('Pages/cyracris');
                        break;
                    case 'production':
                        $sx .= view('Pages/production');
                        break;
                    case 'team':
                        $sx .= view('Pages/team');
                        break;
                    default:
                        //$sx .= view('404');
                }
            $sx .= view('headers/footer');
        return $sx;
    }
}
