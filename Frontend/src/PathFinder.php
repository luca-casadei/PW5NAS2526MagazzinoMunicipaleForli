<?php
    class PathFinder
    {
        private string $root_dir;
        public function __construct()
        {
            $this->root_dir = realpath(__DIR__);
        }
        public function get_root_dir(){
            return $this->root_dir;
        }
        public function __destruct() {}
    }
?>