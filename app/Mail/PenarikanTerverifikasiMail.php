<?php
namespace App\Mail;

use App\Models\PenarikanBerjangka;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PenarikanSukarela;

class PenarikanTerverifikasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $penarikan;
    public $users;

    public function __construct(PenarikanSukarela|PenarikanBerjangka $penarikan, $users)

    {
        $this->penarikan = $penarikan;
        $this->users = $users;
    }

    public function build()
    {
        return $this->subject('Penarikan Dana Anda Telah Diverifikasi - ' . $this->penarikan->no_penarikan)
                    ->view('email.penarikan_terverifikasi');
    }
}
