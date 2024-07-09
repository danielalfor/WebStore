<?php
require_once dirname(__DIR__, 1) . '/entity/Item.php';
require_once dirname(__DIR__, 1) . '/utils/ChromePhp.php';

class ItemAccessor
{
    // mysql strings
    private $getAllStatementString = "select * from item inner join inventory using (itemID) where siteID = 1 limit 15";
    private $getAllByStoreStatementString = "select * from item inner join inventory using (itemID) where siteID = :siteID";

    private $getAllStatement = null;
    private $getAllByStoreStatement = null;
    /*
    * Creates a new instance of the accessor with the supplied db connection
    */
    public function __construct($conn)
    {
        ChromePhp::log("Item accesor executing");

        if (is_null($conn)) {
            throw new Exception("No conection :( ");
        }

        $this->getAllStatement = $conn->prepare($this->getAllStatementString);
        if (is_null($this->getAllStatement)){
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }

        $this->getAllByStoreStatement = $conn->prepare($this->getAllByStoreStatementString);
        if (is_null($this->getAllByStoreStatement)) {
            throw new Exception("Bad Statement: '" . $this->getAllByStoreStatementString . "'");
        }
    }

    /**  
    * Get all items from inventory
    * @return array Item objects
    */
    public function getAllItems()
    {
        $result = [];

        try {
            $this->getAllStatement->execute();
            $dbresults = $this->getAllStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r){
                $InitemID = $r['itemID'];
                $Inname = $r['name'];
                $Insku = $r['sku'];
                $Indescription = $r['description'];
                $Incategory = $r['category'];
                $Inweight = $r['weight'];
                $IncaseSize = $r['caseSize'];
                $IncostPrice = $r['costPrice'];
                $InretailPrice = $r['retailPrice'];
                $InsupplierID = $r['supplierID'];
                $Inactive = $r['active'];
                $Innotes = $r['notes'];
                $InImage_url = $r['image_url'];
                $InQuantity = $r['quantity'];
                $obj = new Item($InitemID, $Inname, $Insku, $Indescription, $Incategory, $Inweight, $IncaseSize, $IncostPrice, $InretailPrice, $InsupplierID, $Inactive, $Innotes, $InImage_url,$InQuantity);
                array_push($result, $obj);
            }
        } catch (Exception $e){
            $result = [];
        } finally {
            if (!is_null($this->getAllStatement)){
                $this->getAllStatement->closeCursor();
            }
        }
        return $result;
    }

    /**  
    * Get all items filtered by Store
    * @return array Item objects
    */
    public function getAllItemsByStore($siteID)
    {
        $result = [];

        try {
            $this->getAllByStoreStatement->bindParam(":siteID", $siteID);
            $this->getAllByStoreStatement->execute();
            $dbresults = $this->getAllByStoreStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r){
                $InitemID = $r['itemID'];
                $Inname = $r['name'];
                $Insku = $r['sku'];
                $Indescription = $r['description'];
                $Incategory = $r['category'];
                $Inweight = $r['weight'];
                $IncaseSize = $r['caseSize'];
                $IncostPrice = $r['costPrice'];
                $InretailPrice = $r['retailPrice'];
                $InsupplierID = $r['supplierID'];
                $Inactive = $r['active'];
                $Innotes = $r['notes'];
                $InImage_url = $r['image_url'];
                $InQuantity = $r['quantity'];
                
                $obj = new Item($InitemID, $Inname, $Insku, $Indescription, $Incategory, $Inweight, $IncaseSize, $IncostPrice, $InretailPrice, $InsupplierID, $Inactive, $Innotes, $InImage_url,$InQuantity);
                array_push($result, $obj);
            }
        } catch (Exception $e){
            $result = [];
        } finally {
            if (!is_null($this->getAllStatement)){
                $this->getAllStatement->closeCursor();
            }
        }
        return $result;
    }
}//end of class Item

