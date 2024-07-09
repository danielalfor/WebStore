<?php

class Item implements JsonSerializable
{
    private $itemID;
    private $name;
    private $sku;
    private $description;
    private $category;
    private $weight;
    private $caseSize;
    private $costPrice;
    private $retailPrice;
    private $supplierID;
    private $active;
    private $notes;
    private $image_url;
    private $quantity; //added this field that comes from table inventory

    public function __construct($itemID,$name,$sku,$description,$category,$weight,$caseSize,$costPrice,$retailPrice,$supplierID,$active,$notes,$image_url,$quantity)
    {
        $this->itemID = $itemID;
        $this->name = $name;
        $this->sku = $sku;
        $this->description = $description;
        $this->category = $category;
        $this->weight = $weight;
        $this->caseSize = $caseSize;
        $this->costPrice = $costPrice;
        $this->retailPrice = $retailPrice;
        $this->supplierID = $supplierID;
        $this->active = $active;
        $this->notes = $notes;
        $this->image_url = $image_url;
        $this->quantity = $quantity;
    }

    public function getItemID()
    {
        return $this->itemID;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getSku()
    {
        return $this->sku;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function getWeight()
    {
        return $this->weight;
    }
    public function getCaseSize()
    {
        return $this->caseSize;
    }
    public function getCostPrice()
    {
        return $this->costPrice;
    }
    public function getRetailPrice()
    {
        return $this->retailPrice;
    }
    public function getSupplierID()
    {
        return $this->supplierID;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function getNotes()
    {
        return $this->notes;
    }
    public function getImage_url()
    {
        return $this->image_url;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}

?>