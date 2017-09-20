<?php
/**
 * Created by PhpStorm.
 * User: nyashkin
 * Date: 25.05.15
 * Time: 22:59
 */


class User extends CI_Model {

    const STEAMAPIUSER = 'http://api.steampowered.com/ISteamUser/';

    public function __construct()
    {
        parent::__construct();
        //$this->load->database();
    }


    function go_to_auth($params = []){
        if($this->uri->uri_string() != '')
            $this->session->set_flashdata('return', $this->uri->uri_string());
        $this->load->library('settings');

        $steam_login_ip = $this->config->item('steam_login_ip')[array_rand($this->config->item('steam_login_ip'))];


        require PACKAGE_PATH.'vendor/Openid2.php';
        $open_id = new LightOpenID($this->settings->LOGIN_DOMAIN);
        if ($steam_login_ip) {
            $open_id->set_proxy($steam_login_ip);
        }
        $open_id->returnUrl = $this->settings->LOGIN_DOMAIN.'/'.$this->settings->LOGIN_RETURN_TO;

        if(count($params) == 0) {
            $customUrl = 'https://steamcommunity.com/openid/login?openid.ns=http://specs.openid.net/auth/2.0&openid.mode=checkid_setup&openid.return_to='.$open_id->returnUrl.'&openid.realm='.$this->settings->LOGIN_DOMAIN.'&openid.ns.sreg=http://openid.net/extensions/sreg/1.1&openid.claimed_id=http://specs.openid.net/auth/2.0/identifier_select&openid.identity=http://specs.openid.net/auth/2.0/identifier_select';
            redirect($customUrl);
        } else {
            $time_1 = microtime(true);

            if ($open_id->validate()){
                $id = $open_id->identity;
                $time_2 = microtime(true);
                //Logger::LogInfo('[Main controller] Login timing ' . ($time_2 - $time_1).' ip '.$ip );
                preg_match("/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/", $id, $matches);
                //Logger::LogInfo('[Main controller] Login timing ' . ($time_2 - $time_1).' '.$steamid.' ip '.$ip  );
                $this->login($matches[1], false);



                try {
                    $ga = new UniversalAnalyticsCookieParser();
                    $cid = $ga->getCid();
                    if ($cid != null) {
                        $http = new Http();
                        $http->timeout = GA_TIMEOUT;
                        $s = $http->postSteam('https://www.google-analytics.com/collect',['v' => '1', 't' => 'event', 'tid' =>  'UA-61366615-11','cid' => $cid, 'ec' => 'Funnel','ea' => 'Login']);
                    }
                } catch (Exception $ex){
                    Logger::LogError('[User_Model] Error sending GA Funnel Login'.$ex->getMessage());
                }
            } else {
                var_dump('Not validated!');
            }

            redirect(site_url());
        }
    }




    /**
      * @param $steam_id
     * @param $history
     */
    function login($steam_id, $history = true){

        $this->load->library('user_agent');

        $steam_id = strval($steam_id);

        //$content = json_get_contents_curl(self::STEAMAPIUSER."GetPlayerSummaries/v0002/?key=" . STEAM_APIKEY . "&steamids=" . $steam_id);

        $http = new Http();
        $content = json_decode($http->getSteam(self::STEAMAPIUSER."GetPlayerSummaries/v0002/?key=" . STEAM_APIKEY . "&steamids=" . $steam_id),true);
//        if ($this->input->ip_address() == '109.162.125.56'){
//            var_dump($content['response']['players'][0]);
//            die();
//        }

        if(!isset($content['response']['players'][0])) {
            throw new CriticalException('Something going wrong on taking player data...');
        }
        $content = $content['response']['players'][0];
//        if ($content['profilestate'] != 1) {
//            return;
//        }
        if (!isset($content['steamid'])) {
            throw new CriticalException('Something going wrong on taking player data steamid...');
        }

        $user = $this->s->sql("SELECT * FROM user WHERE steamid = '$steam_id'")->one();

        if($user){

            /*
                if($user['hellstore_ban_time']){
                if(strtotime($user['hellstore_ban_time']) > time()){
                    redirect('banned');
                }
            }
            */
            $this->update($content);
        } else {
            $this->register($content);
            $user = $this->s->sql("SELECT * FROM user WHERE steamid = '$steam_id'")->one();
        }

        if ($this->agent->is_browser())
        {
            $agent = $this->agent->browser().' '.$this->agent->version();
        }
        elseif ($this->agent->is_robot())
        {
            $agent = $this->agent->robot();
        }
        elseif ($this->agent->is_mobile())
        {
            $agent = $this->agent->mobile();
        }
        else
        {
            $agent = 'Unidentified User Agent';
        }


        if($history) {
//            $this->db->insert(DB_PREFIX . 'login_log', array(
//                'steamid' => $steam_id,
//                'user_id' => $user['id'],
//                'IP' => $this->input->ip_address(),
//                'useragent' => $agent,
//                'country' => $this->get_country_by_ip($this->input->ip_address())
//            ));
        }

        $this->session->set_userdata('steam_id', $steam_id);
        $this->session->set_userdata('id', $user['id']);
    }


    function logout(){
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('steam_id');
    }


    function get($id = null){
        if($id == null){
            //$id = $this->get_user_id();
            $steamid = $this->get_steam_id();
             if ($steamid) {

                return $this->s->sql("SELECT * FROM user WHERE steamid = '$steamid'")->one();
            } else {
                return false;
            }
        } else {
            $data = $this->s->sql("SELECT * FROM user WHERE id = '$id'")->one();
            if (!$data) return false;
            return $data;
        }
    }


    function get_steam_id(){
        return $this->session->userdata('steam_id');
    }

    function get_id()
    {
        return $this->session->userdata('id');
    }


    function set_tradelink($link) {
        if (!$this->is_logged())
            return false;
        $user_id = $this->get_user_id();
        $this->s->from('user')->where(['id'=>$user_id])->update(['steam_tradelink'=>$link])->execute();
        return true;
    }

    function get_user_id($steam_id = null){

        if($steam_id == null){
            $steam_id = $this->get_steam_id();
        }

        if ($steam_id) {
            return $this->s->sql("SELECT * FROM user WHERE steamid = '$steam_id'")->one()['id'];
        } else {
            return false;
        }
    }

    function get_user_by_steamid($steam_id = null){

        if($steam_id == null){
            $steam_id = $this->get_steam_id();
        }
        if ($steam_id) {
            $data = $this->s->from('user')->where(['steamid' => $steam_id])->select()->one();
            if (empty($data)) return false;
            return $data;
        } else {
            return false;
        }
    }

    function register($data){

        $ref = get_cookie('referal_id');

        $postData = array(
            'steamid' => $data['steamid'],
            'steam_personaname' => $this->_filter_name($data['personaname']),
            'steam_profileurl'  => $data['profileurl'],
            'steam_avatarfull'  => $data['avatarfull'],
            'steam_personastate'=> $data['personastate'],
            'regip'   => $this->input->ip_address(),
            'lastip'  => $this->input->ip_address(),
            'last_country' => $this->input->server('HTTP_CF_IPCOUNTRY'),
            'reg_country' => $this->input->server('HTTP_CF_IPCOUNTRY'),
            'time_created'      => date('Y-m-d H:i:s'),
            'time_updated'      => date('Y-m-d H:i:s'),
        );

        if($ref) {
            $postData['referal_from'] = intval($ref);

            $this->s->sql(sprintf('UPDATE user SET referal_regged = referal_regged + 1 WHERE id = %s', $this->s->quote($postData['referal_from'])))->execute();
            delete_cookie('referal_id');
        }

        $this->s->from('user')->insert($postData)->execute();



        if ($this->config->load('redis', TRUE, TRUE))
        {
            $config = $this->config->item('redis');
            $redis = new Redis();
            $redis->connect($config['host'], $config['port']);
            $redis->auth($config['password']);
            $redis->select($config['db']);

            $redis->incr(STATISTICS_USER_REGISTRED);
        }



//        $ref = $this->session->userdata('ref');
//
//        if($ref && $ref > 0){
//
//            $uid = $this->db->insert_id();
//
//            $this->load->library('user_agent');
//
//            $this->db->insert('referral', array(
//                'partner_id' => intval($ref),
//                'user_id' => $uid,
//                'steam_id' => $data['steamid'],
//                'register_date' => time(),
//                'ip_address' => $this->input->ip_address(),
//                'referral_url' => $this->agent->referrer(),
//            ));
//        }


    }

    function update($data){
        $postData = array(
            'steam_personaname' => ($this->_filter_name($data['personaname'])),
            'steam_profileurl'  => $data['profileurl'],
            'steam_avatarfull'  => $data['avatarfull'],
            'steam_personastate'=> $data['personastate'],
            //'referal_id'        => $data['steamid'] - STEAM_PROFILE_INCREMENT,
            'lastip'  => $this->input->ip_address(),
            'time_updated'      => date('Y-m-d H:i:s'),
            'last_country' => $this->input->server('HTTP_CF_IPCOUNTRY')
        );

        $this->s->from('user')->where(['steamid' => $data['steamid']])->update($postData)->execute();
    }

    /**
      * @param string $name
     * @return string
     */
    private function _filter_name($name){

        $sites = $this->s->from('site')->where('action', 'blacklist')->select()->many();

//        $names = $this->config->item('filter_name');

        foreach($sites as $row)
            $name = str_ireplace ($row['site'], '', $name);

        $name = trim($name);
        $name = htmlentities($name);

        $name = preg_replace('/[\xF0-\xF7].../s', '', $name);
        if(empty($name))
        {
            $name = 'User';
        }
        //$name = trim($name, "' ");

        return $name;
    }

    function is_logged($hard_check=false){

        $steam_id = $this->session->userdata('steam_id');

        if($steam_id){
            $steam_id = $steam_id;

            if($steam_id > 0){

                //$ref = $this->input->get('bonus');

                //if($ref) $this->session->set_userdata('ref', intval($ref));

                 if($hard_check){

                    $query = $this->s->from('user')->where(array('steamid' => $steam_id))->select()->one();

                    if(!empty($query)) return false;
                }

                return true;

            }
        }

        return false;
    }


    function add_money($steam_id, $sum, $message = 'Add money', $type="SOME", $system=false,$assign_id = null,$info = null){
        $uid = $this->get_user_id($steam_id);
        if($system) $ip = ''; else $ip = $this->input->ip_address();
        if(!$uid) show_error('invalid user id');
        $this->s->from(APPLICATION_TRANSACTIONS)->insert(array(
            'user_id' => $uid,
            'assign_id' => $assign_id,
            'message' => $message,
            'steamid' => $steam_id,
            'sum'     => $sum,
            'ip_address' => $ip,
            'mark'    => 'ADD',
            'type'    => $type,
            'info' => $info
        ))->execute();
        if ($this->s->from('user')->where(['id' => $uid])->update(sprintf('wallet_balance = wallet_balance + %s',$this->s->quote($sum)))->execute()) {
            return true;
        }  else {
            return false;
        }
    }

    function add_money_by_id($uid, $sum, $message = 'Add money', $type="SOME", $system=false,$assign_id = null,$info = null){
        $user = $this->get($uid);
        if($system) $ip = ''; else $ip = $this->input->ip_address();
        if(!$uid) show_error('invalid user id');
        $this->s->from(APPLICATION_TRANSACTIONS)->insert(array(
            'user_id' => $uid,
            'assign_id' => $assign_id,
            'message' => $message,
            'steamid' => $user['steamid'],
            'sum'     => $sum,
            'ip_address' => $ip,
            'mark'    => 'ADD',
            'type'    => $type,
            'info' => $info
        ))->execute();
        if ($this->s->from('user')->where(['id' => $uid])->update(sprintf('wallet_balance = wallet_balance + %s',$this->s->quote($sum)))->execute()) {
            return true;
        }  else {
            return false;
        }
    }


    function add_info($steam_id, $sum, $message = 'Add info', $type="WITHDRAW", $system=false,$assign_id = null,$info = null){
        $uid = $this->get_user_id($steam_id);
        if($system) $ip = ''; else $ip = $this->input->ip_address();
        if(!$uid) show_error('invalid user id');
        $this->s->from(APPLICATION_TRANSACTIONS)->insert(array(
            'user_id' => $uid,
            'assign_id' => $assign_id,
            'message' => $message,
            'steamid' => $steam_id,
            'sum'     => $sum,
            'ip_address' => $ip,
            'mark'    => 'INFO',
            'type'    => $type,
            'info' => $info
        ))->execute();;
    }


    function add_info_by_id($uid, $sum, $message = 'Add info', $type="WITHDRAW", $system=false,$assign_id = null,$info = null){
        $user = $this->get($uid);
        if($system) $ip = ''; else $ip = $this->input->ip_address();
        if(!$uid) show_error('invalid user id');
        $this->s->from(APPLICATION_TRANSACTIONS)->insert(array(
            'user_id' => $uid,
            'assign_id' => $assign_id,
            'message' => $message,
            'steamid' => $user['steamid'],
            'sum'     => $sum,
            'ip_address' => $ip,
            'mark'    => 'INFO',
            'type'    => $type,
            'info' => $info
        ))->execute();;
    }


    function remove_money($steam_id, $sum, $message = 'Remove money', $type="SOME", $system=false,$assign_id = null,$info = null){

        $uid = $this->get_user_id($steam_id);

        if($system) $ip = ''; else $ip = $this->input->ip_address();
//        $this->s->from('user')->where(['id' => $uid])->update('wallet_balance = wallet_balance - '.)->execute();
//        try {
            if ($this->s->from('user')->where(['id' => $uid])->update(sprintf('wallet_balance = wallet_balance - %s', $this->s->quote($sum)))->execute()) {
                $this->s->from(APPLICATION_TRANSACTIONS)->insert(array(
                    'user_id' => $uid,
                    'assign_id' => $assign_id,
                    'message' => $message,
                    'steamid' => $steam_id,
                    'sum'     => "-$sum",
                    'ip_address' => $ip,
                    'mark'    => 'REMOVE',
                    'type'    => $type,
                    'info' => $info
                ))->execute();
                return true;
            } else {
                return false;
            }
//        } catch (Exception $e){
//            return false;
//        }
//        $this->db->query("UPDATE user SET wallet_balance = wallet_balance - ? WHERE id = ?", [$sum, $uid]);
    }

    function remove_money_by_id($uid, $sum, $message = 'Remove money', $type="SOME", $system=false,$assign_id = null,$info = null){
        $user = $this->get($uid);
        if($system) $ip = ''; else $ip = $this->input->ip_address();

        if ($this->s->from('user')->where(['id' => $uid])->update(sprintf('wallet_balance = wallet_balance - %s', $this->s->quote($sum)))->execute()) {
            $this->s->from(APPLICATION_TRANSACTIONS)->insert(array(
                'user_id' => $uid,
                'assign_id' => $assign_id,
                'message' => $message,
                'steamid' => $user['steamid'],
                'sum'     => "-$sum",
                'ip_address' => $ip,
                'mark'    => 'REMOVE',
                'type'    => $type,
                'info' => $info
            ))->execute();;
            return true;
        } else {
            return false;
        }
    }


    function get_country_by_ip($ip){

        $content = json_decode(file_get_contents("http://api.sypexgeo.net/json/".$ip),true);
        if(isset($content['country'])){
            return $content['country']['name_ru'].', '.$content['city']['name_ru'];
        }

        return '';
    }

    function is_admin($user_id = NULL)
    {
        $user = $this->get($user_id);
        if (!$user) return false;
        return ($user['rights'] >= 6);
    }
    function is_moderator(){

        $user = $this->get();

        if(!$user) return false;


          //                          fursa                        nya                                               sid                                spanki                                         frenzy                              belman
        return ($user['steamid'] == '76561198137776109' ||  $user['steamid'] == '76561197985460076' || $user['steamid'] == '76561198305990519' || $user['steamid'] == '76561198129701635' || $user['steamid'] == '76561198298292187' || $user['steamid'] == '76561198063209297' );
    }
    
    function is_tester()
    {
        $user = $this->get();

        if (!$user) return false;

        return ($user['rights'] >= 1);
    }
 

}

