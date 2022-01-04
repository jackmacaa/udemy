<?php
    // App Core Class
    // Creates URL and loads core controller
    // URL format will be /controller/method/params

    class Core
    {
        // Default controller
        protected $currentController = "Pages";
        // So the home page is Pages/index
        protected $currentMethod = "index";
        protected $params = [];

        public function __construct()
        {
            // Array of the URL in format Arr([0] => controller, [1] => method, [2] => params)
            $url = $this->getUrl();

            // Look in controllers for first value
            // ucword capitalises first letter like classes are named
            if(isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php'))
            {
                // If exists, set as controller
                $this->currentController = ucwords($url[0]);
                // Unset 0 index
                unset($url[0]);
            }

            // Require the controller e.g. currentController now equals Posts or Users default is Pages
            require_once "../app/controllers/" . $this->currentController . ".php";

            // Instantiate controller class e.g. Pages = new Pages.
            // Since controllers/Pages is required above we can now create an instance of the Pages class
            $this->currentController = new $this->currentController;

            // Check for 2nd part of URL e.g. about in pages/about
            if(isset($url[1]))
            {
                // Check to see if exists in controller
                // if "about" or whatever at $url[1] is inside of currenController object, instantiated above
                if(method_exists($this->currentController, $url[1]))
                {
                    $this->currentMethod = $url[1];
                    // Unset 1 index
                    unset($url[1]);
                }
            }

            // Get params
            $this->params = $url ? array_values($url) : [];

            // Call a callback with array of params
            call_user_func_array([$this->currentController,
                $this->currentMethod],
                $this->params);
        }

        // Used to simplify the URL and not show redundant words
        public function getUrl()
        {
            //
            if(isset($_GET['url']))
            {
                // if user has a slash at the end of URL e.g. localhost/shareposts/post/ then removes it
                $url = rtrim($_GET['url'], "/");
                // knows what chars can exist in a URL and removes ones that aren't
                $url = filter_var($url, FILTER_SANITIZE_URL);
                return explode("/", $url);
            }
        }
    }