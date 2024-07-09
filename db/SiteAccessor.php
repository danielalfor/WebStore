<?php
require_once dirname(__DIR__, 1) . '/entity/Site.php';

class SiteAccessor
{
    // mysql strings
    private $getAllStatementString = "select * from site where siteID > 1";
    //private $getAllStatementString = "select * from site where siteID > 3"; //to just show the stores

    private $getAllStatement = null;

    /*
    * Creates a new instance of the accessor with the supplied db connection
    */
    public function __construct($conn)
    {
        ChromePhp::log("Site Accesor executing");

        if (is_null($conn)) {
            throw new Exception("No conection :( ");
        }

        $this->getAllStatement = $conn->prepare($this->getAllStatementString);
        if (is_null($this->getAllStatement)){
            throw new Exception("bad statement: '" . $this->getAllStatementString . "'");
        }
    }

    /**  
    * Get all items from inventor
    * @return array Item objects
    */
    public function getAllItems()
    {
        $result = [];

        try {
            $this->getAllStatement->execute();
            $dbresults = $this->getAllStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r){
                $siteID = $r['siteID'];
                $name = $r['name'];
                                
                $obj = new Site($siteID, $name);
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