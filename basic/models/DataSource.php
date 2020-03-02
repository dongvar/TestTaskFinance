<?
namespace app\models;

use app\models\Anticaptcha;

class DataSource
{
    private $antiCaptchaKey;    
    private $webSiteCaptchaKey;
    private $webSiteCaptchaURL;        
    private $financeInfoURL;     
    private $captchaFile; 
    
    private $errorMessage; 
    
    function __construct($antiCaptchaKey, $webSiteCaptchaKey, $webSiteCaptchaURL, $financeInfoURL)
    {        
        $this->antiCaptchaKey = $antiCaptchaKey; 
        $this->webSiteCaptchaKey = $webSiteCaptchaKey; 
        $this->webSiteCaptchaURL = $webSiteCaptchaURL; 
        $this->financeInfoURL = $financeInfoURL; 
        $this->captchaFile = 'generate.jpg'; 
    }
    
    /*
    *
    */    
    public function getData($iinBin)
    {        
        // Загружаем каптчу с сайта источника по ключу и временно сохраняем её
        if(!$this->loadCaptchaImage())
            return false;
                
        // Передаем каптчу на сервис anti-captch.com и получаем решение
        $captchaSolution = $this->getCaptchaSolution();                    
        
        // Удаляем картинку каптчи, так как она уже не нужна
        if(file_exists($this->captchaFile))
            unlink($this->captchaFile);
                
        // Если прошли каптчу вытаскиваем все данные по ИИН с сайта источника
        if(!$captchaSolution)
            return false;
                
        return $this->getFinanceData($iinBin, $captchaSolution);
    }
    
    /*
    * Сохраняет картинку каптчи с сайта источника 
    */    
    public function loadCaptchaImage()
    {
        try {
            $ch = curl_init($this->webSiteCaptchaURL.'?uid='.$this->webSiteCaptchaKey);
            $fp = fopen($this->captchaFile, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
       }
       catch(Exception $e) {            
            $this->errorMessage = 'Не удалось загрузить картинку каптчи!
                URL сайта источника: '.$this->webSiteCaptchaURL.'
                Ключ каптчи: '.$this->webSiteCaptchaKey;    
            return false;
       }       
       return true;
    }
        

    /*
    * Возвращает решение каптчи, используется сервис anti-captch.com
    */
    public function getCaptchaSolution()
    {
        $anticaptcha = new Anticaptcha();
        $anticaptcha->setVerboseMode(false);
              
        $anticaptcha->setKey($this->antiCaptchaKey);

        //echo $anticaptcha->getBalance(); exit;

        $anticaptcha->setFile($this->captchaFile);

        if (!$anticaptcha->createTask()) {
            $anticaptcha->debout("API v2 send failed - ".$anticaptcha->getErrorMessage(), "red");
            $this->errorMessage = 'ANTI-CAPTHA.COM: '.$anticaptcha->getErrorMessage();            
            return false;
        }

        $taskId = $anticaptcha->getTaskId();


        if (!$anticaptcha->waitForResult()) {
            $anticaptcha->debout("could not solve captcha", "red");
            $anticaptcha->debout($anticaptcha->getErrorMessage());
            $this->errorMessage = 'ANTI-CAPTHA.COM: '.$anticaptcha->getErrorMessage();            
            return false;
        } else {            
            return $anticaptcha->getTaskSolution();
        }
                
        return false;
    }

    /*
    * Возвращает данные загруженые с сайта источника
    */
    public function getFinanceData($iinBin, $captchaSolutin) {
        
        $data = [ 'iinBin' => $iinBin,
                  'captcha-id' => $this->webSiteCaptchaKey,
                  'captcha-user-value' => $captchaSolutin
                ];
                
                                
        $ch = curl_init($this->financeInfoURL);                 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json',
                               'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',                       
                               'Content-Type: application/json'
                               ));     
  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                          
        $res = curl_exec($ch);        
        if(curl_errno($ch)) {
            $this->errorMessage = 'Ошибка при запросе данных с сайта источника!';
            return false;
        }   
        curl_close($ch);        
        
        $result = json_decode($res, true);
        
        if(empty($result)) {
            $this->errorMessage = 'Нет данных или сервис http://kgd.gov.kz/ru/app/culs-taxarrear-search-web не работает!';
            return false;   
        }     
        
        return $result;
    }
    
    /*
    *
    */ 
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}