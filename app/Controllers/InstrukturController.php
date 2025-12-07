<?php

namespace App\Controllers;

use App\Models\InstrukturModel;

class InstrukturController extends BaseController
{
    protected $instruktur;

    public function __construct()
    {
        $this->instruktur = new InstrukturModel();
    }

    public function instruktur()
    {
        $data['instruktur'] = $this->instruktur
            ->where('deleted_at', null)
            ->findAll();

        return view('instruktur/instruktur', $data);
    }

    //create
    public function create()
    {
        $foto = $this->request->getFile('foto_profil');
        $namaFile = null;

        if ($foto && $foto->isValid()) {
            $namaFile = $foto->getRandomName();
            $foto->move('uploads/instruktur', $namaFile);
        }

        $this->instruktur->insert([
            'nama'              =>  $this->request->getPost('nama'),
            'email'             =>  $this->request->getPost('email'),
            'no_hp'             =>  $this->request->getPost('no_hp'),
            'alamat'            =>  $this->request->getPost('alamat'),
            'foto_profil'       =>  $namaFile,
            'tgl_lahir'         =>  $this->request->getPost('tgl_lahir'),
            'keahlian'          =>  $this->request->getPost('keahlian'),
            'status'            =>  'aktif',
            'created_at'        =>  date('Y-m-d H:i:s'),
            'updated_at'        =>  date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Instruktur berhasil ditambahkan!');
    }

    //update
    public function update($id)
    {
        $data = [
            'nama'        => $this->request->getPost('nama'),
            'email'       => $this->request->getPost('email'),
            'no_hp'       => $this->request->getPost('no_hp'),
            'alamat'      => $this->request->getPost('alamat'),
            'tgl_lahir'   => $this->request->getPost('tgl_lahir'),
            'keahlian'    => $this->request->getPost('keahlian'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $foto = $this->request->getFile('foto_profil');

        if ($foto && $foto->isValid()) {
            $namaFile = $foto->getRandomName();
            $foto->move('uploads/instruktur', $namaFile);
            $data['foto_profil'] = $namaFile;
        }

        $this->instruktur->update($id, $data);

        return redirect()->back()->with('success', 'Data instruktur berhasil diupdate!');
    }

    //delete
    public function delete($id)
    {
        $this->instruktur->update($id, [
            'deleted_at' => date('Y-m-d H:i:s'),
            'status'     => 'nonaktif'
        ]);

        return redirect()->back()->with('success', 'Data instruktur berhasil diarsipkan!');
    }

    //toggle status
    public function toggleStatus($id)
    {
        $i = $this->instruktur->find($id);

        if (!$i) {
            return redirect()->back()->with('error', 'Data instruktur tidak ditemukan!');
        }

        $newStatus = $i['status'] === 'aktif'? 'nonaktif' : 'aktif';

        $this->instruktur->update($id, [
            'status'        =>  $newStatus,
            'updated_at'    =>  date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Status instruktur berhasil diubah!');
    }
}