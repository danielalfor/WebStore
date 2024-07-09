<?php

class Site implements JsonSerializable
{
    private $siteID;
    private $name;

    public function __construct($siteID,$name)
    {
        $this->siteID = $siteID;
        $this->name = $name;
    }

    public function getSiteID()
    {
        return $this->siteID;
    }
    public function getName()
    {
        return $this->name;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}

?>