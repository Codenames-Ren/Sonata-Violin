<?php

namespace App\Controllers;

use App\Models\PaketModel;

class HomeController extends BaseController
{
    protected $paket;

    public function __construct()
    {
        $this->paket = new PaketModel();
    }

    public function index()
    {
        $paketModel = new \App\Models\PaketModel();
    
        $data['paket'] = $paketModel->where('status', 'aktif')
                                    ->orderBy('level', 'ASC')
                                    ->findAll();
        
        return view('spa/home', $data);
        // return view('spa/home', [
        //     'paket' => $this->paket->where('status', 'aktif')->findAll()
        // ]);
    }
}
