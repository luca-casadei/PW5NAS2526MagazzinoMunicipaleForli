<?php
class PageInfo
{
    private string $title;
    private array $style_sheets;
    private array $scripts;
    private string $page_id;
    private string $content_path;

    public function __construct(string $page_id, string $title, string $content_path)
    {
        $this->title = $title;
        $this->style_sheets = [];
        $this->scripts = [];
        $this->page_id = $page_id;
        $this->content_path = $content_path;
    }

    public function add_script(string $script_path)
    {
        array_push($this->scripts, $script_path);
    }

    public function add_sheet(string $sheet_path)
    {
        array_push($this->style_sheets, $sheet_path);
    }

    public function get_title()
    {
        return $this->title;
    }

    public function get_sheets()
    {
        return $this->style_sheets;
    }

    public function get_scripts()
    {
        return $this->scripts;
    }

    public function get_page_id(){
        return $this->page_id;
    }

    public function get_page_content(){
        return $this->content_path;
    }

    public function __destruct() {}
}
