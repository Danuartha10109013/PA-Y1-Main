<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpPenarikanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $penarikan;

    public function __construct($penarikan)
    {
        $this->penarikan = $penarikan;
    }

    public function build()
    {
        return $this->subject('Kode OTP Penarikan Simpanan '.$this->penarikan->no_penarikan)
                    ->view('email.otp_penarikan');
    }
}
