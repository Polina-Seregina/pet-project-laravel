<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Product;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\BlockKit\Composites\ConfirmObject;
use Illuminate\Notifications\Slack\SlackMessage;
use Illuminate\Notifications\Notification;

class OrderCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    private $productName;
    private $seller;
    private $buyer;
    private $price;
    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product, User $seller, User $buyer)
    {
        $this->productName = $product->name;
        $this->seller = $seller->email;
        $this->buyer = $buyer->email;
        $this->price = $product->price;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->headerBlock('Новый заказ')
            ->sectionBlock(function (SectionBlock $block) {
                $block->text("Арт {$this->productName} был приобретен.\nПродавец - {$this->seller}, покупатель - {$this->buyer}.");
            })
            ->contextBlock(function (ContextBlock $block) {
                $block->text("Cтоимость: {$this->price} USD.");
            });
    }
}
