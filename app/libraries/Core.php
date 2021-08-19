<?php
    //Core App Class

    class Core {
        protected $currentControler = 'Pages';
        protected $currentMethod = 'Index';
        protected $params = [];

        public function __construct() {
            $url = $this->getUrl();
            // look in 'controllers' fro first value, ucwords will capitalize first letter
          if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
             // Will set new controller
            $this->currentControler = ucwords($url[0]);
            unset($url[0]);
          }
          // Require the controler
          require_once '../app/controllers/' . $this->currentControler . '.php';
          $this->currentControler = new $this->currentControler;
          //Check for second part of the URL
          if (isset($url[1])) {
              if (method_exists($this->currentControler, $url[1])){
                  $this->currentMethod = $url[1];
                  unset($url[1]);
              }
          }

          //Get params
          $this->params = $url ? array_values($url) : [];
          //call callback with array of params
          call_user_func_array([$this->currentControler, $this->currentMethod], $this->params);
        }

        public function getUrl(){
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'], '/');
                //Aloows you to filter variables as sring/number
                $url = filter_var($url, FILTER_SANITIZE_URL);
                //Breaking it into an array
                $url = explode('/', $url);
                return $url;
            }
        }
    }