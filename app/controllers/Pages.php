<?php
    class Pages extends Controller
    {
        public function __construct()
        {

        }

        // Used to load the view and pass data through to the webpage
        // can access data['title'] on page index.php in folder pages
        public function index()
        {
            if(isLoggedIn())
            {
                redirect('posts');
            }
            // This data could be coming straight from the db e.g. posts
            // can render the posts with html on the view page
            $data = [
                'title' => 'SharePosts',
                'description' => 'Simple social network built on the JackmacMVC PHP Framework'
            ];

            $this->view('pages/index', $data);
        }

        public function about()
        {
            $data = [
                'title' => 'About Us',
                'description' => 'App to share posts with other users'
            ];
            $this->view('pages/about', $data);
        }
    }