<?php namespace iWedmak\Export\Libs;
use iWedmak\ExtraCurl\Parser;

class AntiCap {

    private $api_key='e3062f3947d331c509e0973524e91858';
    private $url_in='http://anti-captcha.com/in.php';
    private $url_res='http://anti-captcha.com/res.php';
    private $p='http://anti-captcha.com/res.php';
    
    public function __construct()
    {
        $this->p = new Parser;
    }
    
    public function getCaptcha($url)
    {
        $resp=$this->postCaptcha($url);
        if($resp)
        {
            return $this->getResp($resp);
        }
        return $resp;
    }
    
    private function postCaptcha($url)
    {
        $this->p->c->post($this->url_in, array(
            'method' => 'base64',
            'key' => $this->api_key,
            'body' => base64_encode(file_get_contents($url)),
        ));
        $pos = mb_stripos($this->p->c->response, 'OK|');
        if($pos!==false)
        {
            return $this->p->c->response;
        }
        else
        {
            \Log::info($this->p->c->response);
            return false;
        }
    }
    
    private function getResp($id, $sleep=20)
    {
        sleep($sleep);
        $this->p->c->get($this->url_res, array(
                'action' => 'get',
                'key' => $this->api_key,
                'id' => preg_replace('/[^0-9]/', '', $id),
            ));
        $pos = mb_stripos($this->p->c->response, 'OK|');
        if($pos!==false)
        {
            $resp=explode('|',$this->p->c->response);
            return $resp[1];
        }
        else
        {
            $pos = mb_stripos($this->p->c->response, 'ERROR_NO_SUCH_CAPCHA_ID');
            if($pos!==false)
            {
                return false;
            }
            else
            {
                return $this->getResp($id, 20);
            }
        }
    }
    
}
?>