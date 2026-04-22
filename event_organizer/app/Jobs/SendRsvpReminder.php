<?php

namespace App\Jobs;

use App\Services\FonnteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRsvpReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $guest;
    protected $event;

    public $tries = 3;

    public function __construct($guest, $event)
    {
        $this->guest = $guest;
        $this->event = $event;
    }

    public function handle(FonnteService $fonnteService): void
    {
        if (empty($this->guest->phone_number)) {
            return;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->guest->phone_number);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        $rsvpLink = url("/invitation/" . $this->guest->barcode_token);

        $eventDate = \Carbon\Carbon::parse($this->event->event_date);

        $message = "Salam hangat *" . $this->guest->name . "*, perkenalkan kami dari Fenix Digital Guest Book\n\n";
        $message .= "Kami menantikan kehadiran Anda di event the wedding of *" . $this->event->title . "* yang akan diselenggarakan pada:\n";
        $message .= "Hari/ Tanggal: " . $eventDate->format('l, d F Y') . "\n";
        $message .= "Pukul: 18.00 WIB\n\n";
        $message .= "Silahkan tekan link berikut untuk mengakses undangan digital:\n";
        $message .= $rsvpLink . "\n";
        $message .= "*link ini dipastikan aman dan hanya berisi undangan dari vendor resmi terpercaya\n\n";
        $message .= "Sebagai registrasi masuk, mohon siapkan dan tampilkan QR Code Anda\n";
        $message .= "Kami ucapkan terima kasih dan sampai jumpa di lokasi acara!\n\n";
        $message .= "Salam hangat,\n*Fenix Event Organizer*";

        $fonnteService->sendMessage($phone, $message, '5-10');
    }
}
