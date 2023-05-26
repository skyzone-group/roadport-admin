<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Botuser;
use App\Models\Orders;
use App\Models\Current;

class SendRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        for($i = 0; $i < 5; $i ++)
        {
            //$this->sendByTelegram("okk");
            $orders = Orders::where('status', 1)
                ->with(['owner', 'regionFrom', 'regionTo', 'driver' => function ($query) {
                    $query->with('car');
                }])
                ->first();
            if(isset($orders))
            {
                $orders->save();
                $ownerId = $orders->tg_user_id;

                $comment = "\n<b>Комментарий к Заявки: </b>".(($orders->comment == 'no' || $orders->comment == "Izoh yo'q") ? 'Нет заметки' : $orders->comment);
                $message = "<b>Заявки: №".$orders->id.
                    "</b>\n<b>Имя водителя:</b> ".$orders->driver->name.
                    "</b>\n<b>Имя владельца:</b> ".$orders->owner->name.
                    "\n<b>Номер водителя:</b> +".$orders->driver->phone.
                    "\n<b>Номер клиента:</b> +".$orders->owner->phone.
                    "\n<b>Тип машины:</b> +".$orders->driver->car->name_ru.
                    "\n<b>Тип продукта:</b> ".$orders->product_type.
                    "\n<b>Вес изделия:</b> ".number_format($orders->product_weight, 0,' ',' ')." kg".
                    "\n<b>Адрес доставки:</b> ".$orders->regionFrom->name_ru."-".$orders->regionTo->name_ru.
                    $comment.
                    "\n<b>Способ оплаты: </b>".($orders->payment_method == 'naqd' ? "💵 Наличные" : "💳 Пластиковая карта($orders->payment_method)").
                    "\n<b>Цена доставки:</b> ".number_format($orders->price, 0,' ',' ')." cум";
                $this->sendByTelegram($message, $orders, $ownerId);
            }

        }
        for($i = 0; $i < 0; $i ++)
        {
            //$this->sendByTelegram("kokk");
            $currentTime = strtotime(date('Y-m-d H:i:s'));
            $orders = Orders::where( 'status_otziv', 0)
                ->with('current')
                ->first();
            if(isset($orders))
            {

                $createdTime = (strtotime($orders->created_at) + 2 * 60 * 60);
                //$this->sendOtziv(($currentTime - $createdTime)." - ".(90 * 60), 'uz', '1371980494');
                if(($currentTime - $createdTime) >= (90 * 60))
                {

                    $orders->status_otziv = 1;
                    $orders->save();
                    $current = Current::where('tg_user_id', $orders->tg_user_id)
                        ->first();
                    $lang = $current->lang;
                    $message =__('panel.order_otziv.'.$lang.'.rate_text');
                    $chatId = $current->tg_user_id;
                    $this->sendOtziv($message, $lang, $chatId);

                    $current->menu = 'otziv_asked';
                    $current->save();
                }

            }
            //$this->sendByTelegram(json_encode($orders));
            //$this->sendByTelegram("ok");
        }
    }

    function sendByTelegram($message, $orders, $ownerId)
    {
        $token  = config('constants.bot.token');
        $chatID = config('constants.bot.orders_group');

        $removeKeyboard = array(
            'remove_keyboard' => true,
            'inline_keyboard' =>
                [
                    [
                        ['text' => "✅%20Принять",'callback_data'=>'ok1_'.$orders->id],
                        ['text' => "❌%20Отменить",'callback_data'=>'no1_'.$orders->id],
                    ],
                ]
        );
        $removeKeyboardEncoded = json_encode($removeKeyboard);

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?parse_mode=HTML&chat_id=" . $ownerId;
        $url = $url . "&text=" . urlencode($message) .  "&reply_markup=" .urlencode($removeKeyboardEncoded);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-type:application/json']);

        //ssl settings
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}
