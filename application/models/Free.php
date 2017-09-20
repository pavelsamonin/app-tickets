<?php

/**
 * Created by PhpStorm.
 * User: Shadow
 * Date: 29.03.2017
 * Time: 3:21
 */
class Free extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user');
    }

    public function is_available($user_id = null)
    {
        if (!$user_id) {
            throw new Exception('user_id is not defined');
        }

        $d = $this->s->sql('SELECT *, (86400 - (UNIX_TIMESTAMP() -UNIX_TIMESTAMP(time_updated))) as time_left FROM ' . APPLICATION_TRANSACTIONS . ' WHERE user_id=' . $this->s->quote($user_id) . ' AND type=\'FREE\' AND time_updated> DATE_ADD(NOW(), INTERVAL -1 DAY)')->one();

        if (empty($d) || $this->user->is_admin()) {
            return 0;
        } else {
            return $d['time_left'];
        }

    }


    public function get_history($user_id = null)
    {
        if ($this->user->is_logged()) {

            $d = $this->s->sql('')->many();
            return $d;
        } else {
            return [];
        }
    }


    public function generate_js_list($itemlist)
    {

        $items = [];
        foreach ($itemlist as $item) {
            $items[] = ['weapon_name' => $item['weapon_name'], 'skin_name' => $item['skin_name'], 'rarity' => $item['rarity'], 'steam_image' => $item['steam_image'] . '/130fx100f.png'];
        }
        $money = json_decode($this->settings->FREE_MONEY_AMOUNT);
        foreach ($money as &$i) {
            $items[] = ['weapon_name' => lang('partial.currency') . $i, 'skin_name' => lang('free.balance'), 'rarity' => 'money', 'steam_image' => '/img/free/free.png'];
        }
        shuffle($items);
        return json_encode($items);
    }

}
