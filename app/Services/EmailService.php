<?php

namespace App\Services;

use Config\Services;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = Services::email();
    }

    protected function send($to, $subject, $message)
    {
        $this->email->clear();
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);

        return $this->email->send();
    }

    public function pendaftaranBerhasil(array $data)
    {
        $message = view('emails/pendaftaran_berhasil', $data);

        return $this->send(
            $data['email'],
            'Pendaftaran Berhasil - Sonata Violin',
            $message
        );
    }

    public function pembayaranDiverifikasi(array $data)
    {
        $message = view('emails/pembayaran_berhasil', $data);

        return $this->send(
            $data['email'],
            'Pembayaran Berhasil - Sonata Violin',
            $message
        );
    }

    public function pembayaranDitolak(array $data)
    {
        $message = view('emails/pembayaran_ditolak', $data);

        return $this->send(
            $data['email'],
            'Pembayaran Ditolak - Sonata Violin',
            $message
        );
    }

}
