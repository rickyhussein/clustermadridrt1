<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable; // Tambahkan ini

class SendNotification implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels; // Gunakan Dispatchable

    protected $user;
    protected $ipkl;
    protected $month_name;
    protected $whatsappApiUrl;
    protected $whatsappApiSession;
    protected $whatsappApiKey;

    public function __construct($user, $ipkl, $month_name)
    {
        $this->user = $user;
        $this->ipkl = $ipkl;
        $this->month_name = $month_name;
        $this->whatsappApiUrl = config('faspay.whatsapp_api_url');
        $this->whatsappApiSession = config('faspay.whatsapp_api_session');
        $this->whatsappApiKey = config('faspay.whatsapp_api_key');
    }

    public function handle()
    {
        $message = "Ini adalah pesan otomatis dari sistem layanan Cluster Madrid\n\n" .
                   "Salam sejahtera Bapak/Ibu, Kami informasikan data dibawah ini belum melakukan pembayaran\n" .
                   "Nama : " . $this->user->name . "\n" .
                   "Alamat : " . $this->user->alamat . "\n" .
                   "Jenis Pembayaran : IPKL (" . $this->month_name . ' ' . $this->ipkl->year . ") \n" .
                   "Jatuh Tempo : " . $this->ipkl->expired_date . "\n" .
                   "Status : " . $this->ipkl->status . "\n" .
                   "Nominal : Rp " . number_format($this->ipkl->nominal) . "\n\n" .
                   "Silakan lakukan pembayaran melalui link berikut:\n\n" .
                   url('/my-ipkl/show/'.$this->ipkl->id);

        Http::get($this->whatsappApiUrl, [
            'session' => $this->whatsappApiSession,
            'to' => $this->user->whatsapp($this->user->no_hp),
            'text' =>  $message,
            'key' =>  $this->whatsappApiKey,
        ]);
    }
}

