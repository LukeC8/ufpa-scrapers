<?php

class News
{
    public $link;
    public $date;
    public $short_desc;
    public $long_desc;

    private function process_raw_desc ($raw_desc)
    {
        return utf8_encode (
            str_replace(
                array("\n\r", "\r", "\n", "\t"),
                '',
                $raw_desc
            )
        );
    }

    private function create_desc_array ($raw_desc)
    {
        return explode (" -", $raw_desc);
    }

    function __construct ($raw_desc, $link)
    {
        $desc_array = News::create_desc_array(
            News::process_raw_desc ($raw_desc)
        );

        $this->link = utf8_encode($link);
        $this->date = $desc_array[0];
        $this->short_desc = $desc_array[1];
        $this->long_desc = $desc_array[2];
    }
}

?>
