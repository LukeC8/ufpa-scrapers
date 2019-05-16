<?php

class News
{
    public $link;
    public $date;
    public $short_desc;
    public $long_desc;

    public function process(DOMElement $element)
    {
        //get link and short desc
        foreach($element->getElementsByTagName("h2") as $tile) {
            if($tile->getAttribute("class") === "tileHeadline") {
                foreach($tile->getElementsByTagName("a") as $a) {
                    if ($a->hasAttribute("href")) {
                        $this->link = $a->getAttribute("href");
                        $this->short_desc = $a->nodeValue;
                        break;
                    }
                }
            }
        }

        //get long_desc
        foreach($element->getElementsByTagName("span") as $content) {
            if($content->getAttribute("class") === "description") {
                foreach($content->getElementsByTagName("p") as $desc) {
                    $this->long_desc = $desc->nodeValue;
                    break;
                }
            }
        }

        //get date
        foreach($element->getElementsByTagName("li") as $li) {
            foreach($li->getElementsByTagName("i") as $i) {
                if ($i->getAttribute("class") === "icon-fixed-width icon-calendar") {
                    $this->date = $li->nodeValue;
                    break;
                }
            }
        }

        foreach($element->getElementsByTagName("li") as $li) {
            foreach($li->getElementsByTagName("i") as $i) {
                if ($i->getAttribute("class") === "icon-fixed-width icon-time") {
                    $this->date .= ' - ' . $li->nodeValue;
                    break;
                }
            }
        }
    }

    function __construct ($raw_desc)
    {
        $this->process($raw_desc);
    }

}

?>
