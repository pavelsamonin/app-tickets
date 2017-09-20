<?php

class Http
{
    const HTTP_USERAGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.01; rv:53.0) Gecko/20100101 Firefox/53.0';

    public $my_headers = null;
    public $response = null;
    public $header = null;
    public $useProxy = false;
    public $proxyLine = null;
    public $myCookie = '';
    public $referer = '';
    public $lastError = '';
    public $followLocation = false;
    public $timeout = 21;
    public $info = '';
    public $statusCode = null;
    public $verbose = false;
    public $ip = false;
    public $basic_auth = false;
    public $basic_auth_username = null;
    public $basic_auth_password = null;

    public function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (false === ($this->response = curl_exec($ch))) {
            curl_close($ch);

            return (curl_error($ch));
        } else {
            curl_close($ch);

            return $this->response;
        }
    }

    public function getSteam($url)
    {
        $this->header = '';
        $headers = $this->getHeaders();

        if ($this->referer != '') {
            $headers[] = 'Referer: ' . $this->referer;
        }

        $this->my_headers = $headers;
        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_VERBOSE => $this->verbose,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_URL => $url,
                CURLOPT_FOLLOWLOCATION => $this->followLocation,
                CURLOPT_HTTPHEADER => $headers,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'gzip,deflate',
                CURLOPT_HEADERFUNCTION => [$this, 'header_callback'],
            ]);
            if ($this->ip) {
                curl_setopt($ch, CURLOPT_INTERFACE, $this->ip);
            }
            if ($this->basic_auth) {
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, $this->basic_auth_username . ":" . $this->basic_auth_password);
            }
            if ($this->useProxy) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxyLine);
            }
            if ($this->myCookie != '') {
                curl_setopt($ch, CURLOPT_COOKIE, $this->myCookie);
            }

            $this->response = curl_exec($ch);
            $this->info = curl_getinfo($ch);
            $this->my_headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);

            $this->statusCode = $this->info['http_code'];
            if (curl_errno($ch)) {
                $err = 'Error curl: ' . curl_error($ch) . ' on ' . $url;
                $this->lastError = $err;
                echo $err;
            }
            curl_close($ch);
        } catch (Exception $e) {
            $this->lastError = 'Ex: ' . $e->getMessage();

            return 'Ex: ' . $e->getMessage();
        }

        return $this->response;
    }

    public function postSteam($url, $data)
    {
        $this->header = '';
        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.8,en-US;q=0.6,en;q=0.4,uk;q=0.2',
            'Accept-Encoding: gzip, deflate, sdch, br',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: ' . self::HTTP_USERAGENT,
        ];
        if ($this->referer != '') {
            $headers[] = 'Referer: ' . $this->referer;
        }

        try {
            $ch = curl_init($url);
            $postStr = "";
            foreach ($data as $key => $value) {
                if ($postStr)
                    $postStr .= "&";
                $postStr .= $key . "=" . $value;
            }
            curl_setopt_array($ch, [
                CURLOPT_VERBOSE => $this->verbose,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_POST => 1,
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $postStr,
                CURLOPT_FOLLOWLOCATION => $this->followLocation,

                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'gzip,deflate',
                CURLOPT_HEADERFUNCTION => [$this, 'header_callback'],
                CURLINFO_HEADER_OUT => true,

            ]);
            if ($this->ip) {
                curl_setopt($ch, CURLOPT_INTERFACE, $this->ip);
            }
            if ($this->basic_auth) {
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, $this->basic_auth_username . ":" . $this->basic_auth_password);
            }
            if ($this->useProxy) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxyLine);
            }
            if ($this->myCookie != '') {
                curl_setopt($ch, CURLOPT_COOKIE, $this->myCookie);
            }

            $this->response = curl_exec($ch);
            $this->info = curl_getinfo($ch);
            $this->my_headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);

            $this->statusCode = $this->info['http_code'];
            if (curl_errno($ch)) {
                $err = 'Error curl: ' . curl_error($ch) . ' on ' . $url;
                $this->lastError = $err;
                echo $err;
            }

            curl_close($ch);
        } catch (Exception $e) {
            $this->lastError = 'Ex: ' . $e->getMessage();

            return 'Ex: ' . $e->getMessage();
        }

        return $this->response;
    }

    /**
     * HTTP - POST request
     *
     * @param       $url
     * @param array $data
     *
     * @return mixed|null|string
     */
    public function post($url, $data = [])
    {
        try {
            $ch = curl_init($url);
            $postStr = null;
            if (!empty($data)) {
                $postStr = http_build_query($data);
            }

            curl_setopt_array($ch, [
                CURLOPT_POST => 1,
                CURLOPT_URL => $url,
                CURLOPT_POSTFIELDS => $postStr,

                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'gzip,deflate',
            ]);
            $this->response = curl_exec($ch);
            $this->info = curl_getinfo($ch);
            $this->my_headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);

            $this->statusCode = $this->info['http_code'];
            if (curl_errno($ch)) {
                $err = 'Error curl: ' . curl_error($ch) . ' on ' . $url;
                $this->lastError = $err;
                echo $err;
            }

            curl_close($ch);
        } catch (Exception $e) {
            $this->lastError = 'Ex: ' . $e->getMessage();

            return 'Ex: ' . $e->getMessage();
        }

        return $this->response;
    }

    public function getSteamMarket($url)
    {
        $this->header = '';
        $headers = $this->getHeaders();

        if ($this->referer != '') {
            $headers[] = 'Referer: ' . $this->referer;
        }

        try {
            $ch = curl_init($url);

            curl_setopt_array($ch, [
                CURLOPT_VERBOSE => $this->verbose,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_URL => $url,
                CURLOPT_FOLLOWLOCATION => $this->followLocation,

                CURLOPT_HTTPHEADER => $headers,
                CURLINFO_HEADER_OUT => true,

                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'gzip,deflate',
                CURLOPT_HEADERFUNCTION => [$this, 'header_callback'],
            ]);


            if ($this->useProxy) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxyLine);
            }
            if ($this->myCookie != '') {
                curl_setopt($ch, CURLOPT_COOKIE, $this->myCookie);
            }

            $this->response = curl_exec($ch);
            $this->info = curl_getinfo($ch);
            $this->my_headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);

            $this->statusCode = $this->info['http_code'];
            if (curl_errno($ch)) {
                $err = 'Error curl: ' . curl_error($ch) . ' on ' . $url;
                $this->lastError = $err;
                echo $err;
            }

            curl_close($ch);
        } catch (Exception $e) {
            $this->lastError = 'Ex: ' . $e->getMessage();

            return 'Ex: ' . $e->getMessage();
        }

        return $this->response;
    }

    public function headSteam($url)
    {
        $this->header = '';
        $headers = $this->getHeaders();
        $this->my_headers = $headers;

        if ($this->referer != '') {
            $headers[] = 'Referer: ' . $this->referer;
        }

        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_CUSTOMREQUEST => 'HEAD',
                CURLOPT_VERBOSE => $this->verbose,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_URL => $url,
                CURLOPT_FOLLOWLOCATION => $this->followLocation,
                CURLOPT_HTTPHEADER => $headers,

                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'gzip,deflate',
                CURLOPT_NOBODY => true,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_HEADERFUNCTION => [$this, 'header_callback'],
            ]);
            if ($this->ip) {
                curl_setopt($ch, CURLOPT_INTERFACE, $this->ip);
            }
            if ($this->useProxy) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxyLine);
            }
            if ($this->myCookie != '') {
                curl_setopt($ch, CURLOPT_COOKIE, $this->myCookie);
            }


            $this->response = curl_exec($ch);
            $this->info = curl_getinfo($ch);

            $this->statusCode = $this->info['http_code'];

            if (curl_errno($ch)) {
                $err = 'Error curl: ' . curl_error($ch) . ' on ' . $url;
                $this->lastError = $err;
                echo $err;
            }
            curl_close($ch);
        } catch (Exception $e) {
            $this->lastError = 'Ex: ' . $e->getMessage();

            return 'Ex: ' . $e->getMessage();
        }

        return $this->response;
    }

    public function getG2aRest($url, $hash)
    {
        $this->header = '';
        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: en-us,en;q=0.8,en-US;q=0.6,en;q=0.4,uk;q=0.2',
            'Connection: keep-alive',
            'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36',
            'Authorization: ' . $hash,
        ];

        if ($this->referer != '') {
            $headers[] = 'Referer: ' . $this->referer;
        }

        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_VERBOSE => $this->verbose,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_URL => $url,
                CURLOPT_FOLLOWLOCATION => $this->followLocation,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADERFUNCTION => [$this, 'header_callback'],
            ]);
            if ($this->ip) {
                curl_setopt($ch, CURLOPT_INTERFACE, $this->ip);
            }
            if ($this->basic_auth) {
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, $this->basic_auth_username . ":" . $this->basic_auth_password);
            }
            if ($this->useProxy) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxyLine);
            }
            if ($this->myCookie != '') {
                curl_setopt($ch, CURLOPT_COOKIE, $this->myCookie);
            }

            $this->response = curl_exec($ch);
            $this->info = curl_getinfo($ch);

            $this->statusCode = $this->info['http_code'];

            if (curl_errno($ch)) {
                $err = 'Error curl: ' . curl_error($ch) . ' on ' . $url;
                $this->lastError = $err;
                echo $err;
            }
            curl_close($ch);
        } catch (Exception $e) {
            $this->lastError = 'Ex: ' . $e->getMessage();

            return 'Ex: ' . $e->getMessage();
        }

        return $this->response;
    }

    public function header_callback($ch, $header_line)
    {
        $this->header .= $header_line;

        return strlen($header_line);
    }

    private function getHeaders()
    {
        return [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.8,en-US;q=0.6,en;q=0.4,uk;q=0.2',
            'Accept-Encoding: gzip, deflate, sdch, br',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: ' . self::HTTP_USERAGENT,
        ];
    }

}
