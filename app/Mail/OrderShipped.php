<?php

namespace App\Mail;

use App\Http\Controllers\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        //
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Trạng thái đơn hàng ( đã cập nhật *)')
                    ->view('pages.send_mail')
                    ->with([
                        'order' => $this->order,
                    ]);
    }
}
