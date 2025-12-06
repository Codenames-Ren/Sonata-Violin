<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use CodeIgniter\HTTP\RequestInterface;

class SiswaController extends BaseController
{
    protected $siswa;
    
    public function __construct()
    {
        $this->siswa = new SiswaModel();
    }

    public function siswa()
    {
        $data = [
            'siswa' => $this->siswa
                        ->where('deleted_at', null)
                        ->findAll(),

            'page_title' => 'Manajemen Siswa',
            'page_subtitle' => 'Kelola data siswa Sonata Violin'
        ];

        return view('siswa/siswa', $data);
    }

    //Create
    public function create()
    {
        $foto = $this->request->getFile('foto_profil');
        $namaFile = null;

        if ($foto && $foto->isValid()) {
            $namaFile = $foto->getRandomName();
            $foto->move('uploads/siswa', $namaFile);
        }

        $this->siswa->insert([
            'nama'              =>  $this->request->getPost('nama'),
            'alamat'            =>  $this->request->getPost('alamat'),
            'no_hp'             =>  $this->request->getPost('no_hp'),
            'foto_profil'       =>  $namaFile,
            'tgl_lahir'         =>  $this->request->getPost('tgl_lahir'),
            'email'             =>  $this->request->getPost('email'),
            'status'            =>  'aktif',
            'created_at'        =>  date('Y-m-d H:i:s'),
            'updated_at'        =>  date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan!');
    }

    // UPDATE
    public function update($id)
    {
        $data = [
            'nama'       => $this->request->getPost('nama'),
            'alamat'     => $this->request->getPost('alamat'),
            'no_hp'      => $this->request->getPost('no_hp'),
            'tgl_lahir'  => $this->request->getPost('tgl_lahir'),
            'email'      => $this->request->getPost('email'),
            'status'     => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $foto = $this->request->getFile('foto_profil');

        if ($foto && $foto->isValid()) {
            $namaFile = $foto->getRandomName();
            $foto->move('uploads/siswa', $namaFile);
            $data['foto_profil'] = $namaFile;
        }

        $this->siswa->update($id, $data);

        return redirect()->back()->with('success', 'Data siswa berhasil diupdate!');
    }

    // SOFT DELETE
    public function delete($id)
    {
        $this->siswa->update($id, [
            'deleted_at' => date('Y-m-d H:i:s'),
            'status'     => 'nonaktif'
        ]);

        return redirect()->back()->with('success', 'Siswa berhasil dihapus (soft delete).');
    }

    public function toggleStatus($id)
    {
        $s = $this->siswa->find($id);

        if (!$s) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        $newStatus = $s['status'] === 'aktif' ? 'nonaktif' : 'aktif';

        $this->siswa->update($id, [
            'status'     => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Status siswa berhasil diubah!');
    }

}