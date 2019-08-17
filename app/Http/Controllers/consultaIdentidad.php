<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Peru\Jne\Dni;
use Peru\Sunat\Ruc;
use Peru\Http\ContextClient;

class consultaIdentidad extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dniConsult()
    {
        $cs = new Dni();
        $cs->setClient(new ContextClient());

        $person = $cs->get('73819654');
        if ($person === false) {
            echo $cs->getError();
            exit();
        }
        dd($person);
    }

    public function rucConsult($ruc)
    {
        $cs = new Ruc();
        $cs->setClient(new ContextClient());

        $company = $cs->get($ruc);
        if ($company === false) {
            echo $cs->getError();
            exit();
        }
        
        return $company;
    }
}
