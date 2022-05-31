<?php
/**
 * @package EasyList
 */
namespace EasyList;

class DynaList
{
    public static $host;
    public static $database;
    public static $username;
    public static $password;
    
    public static function Initialise($info)
    {
        static::$host     = $info['hostname'];
        static::$database = $info['database'];
        static::$username = $info['username'];
        static::$password = $info['password'];
    }
    
    /**
     * 
     * @param array $options
     * $options array : select, joins, from, conditions, group, having, limit, offset, order 
     * array(
         "select" 	=> "<Comma separated column list>"
        ,"from" 	=> "<From table with alias>"
        ,"joins" 	=> "<Join statements>"
        ,"condition" 	=> array(
        			array("condition" => "name = ?", "value" => "<FILTER-NAME>", "Operation" => "AND" ),
        			array("condition" => "age = ?", "value" => "<FILTER-NAME>", "Operation" => "OR" ),
        			array("condition" => "(name = ? AND age IN( ?) )", "value" => array(<FILTER-NAME>, <ARRAY-FILTER-AGE->)), "Operation" => "OR" )
        		)
        ,"group" 	=> "<Comma separated group names>"
        ,"having" 	=> "<having conditions>"
        ,"order" 	=> "<Comma separated order coluns>"
        ,"limit" 	=> "<Integer value of limit>"
        ,"offset" 	=> "<Integer value of offeset>"
        ,"return-data" => "<HTML / JSON / PLAIN>"
        ,"view"	    => "<view location if return-data is HTML>"
        ,"page-size" => "<page size>"
        ,"<addtional element if required>"
        )
     */
    public static function Page($options)
    {
        $sql                = "";
        $select             = "";
        $query              = "";
        $viewData           = "";
        
        $return_data        = isset($options["return-data"]) ? $options["return-data"] : "JSON";
        $page_size          = isset($_POST['page-size']) ? $_POST['page-size'] : (isset($options["page-size"]) ? $options["page-size"] : 25);
        $page               = isset($_POST['page']) ? $_POST['page'] : (isset($options["page"]) ? $options["page"] : 1);
        $total_records      = isset($_POST['total-records']) ? $_POST['total-records'] : (isset($options["total-records"]) ? $options["page"] : 0);
        
        $mainData = array(
             "page-size"       => $page_size
            ,"page"            => $page
            ,"total-records"   => $total_records
            ,"return-data"     => $return_data
            ,"data"            => array()
        );
        
        $data = array();
        
        try{
            if(!isset($options['select']) || trim($options['select']) == "" || !isset($options['from']) || trim($options['from']) == "" ){
                throw new Exception("Select OR From clause is missing.");
            } else {
                $select = "SELECT " . $options['select'];
                $sql .= " FROM " . $options['from'];
            }
            
            if(isset($options['joins']) && $options['joins'] !=""){
                $sql .= $options['joins'];
            }
            
            if(isset($options['conditions']) && $options['conditions'] !=""){
                $sql .= " WHERE " .  $options['conditions'];
            }
            
            if(isset($options['group']) && $options['group'] !=""){
                $sql .= " GROUP BY " .  $options['group'];
            }
            
            if(isset($options['having']) && $options['having'] !=""){
                $sql .=  " HAVING " . $options['having'];
            }
            
            if(isset($options['order']) && $options['order'] !=""){
                $sql .=  " ORDER BY " . $options['order'];
            }
            
            if(isset($options['limit']) && $options['limit'] !=""){
                $sql .= " LIMIT " . $options['limit'];
            }
            
            if(isset($options['offset']) && $options['offset'] !=""){
               // $sql .= $options['offset'];
            }
            
            $conn = mysqli_connect(self::$host, self::$username, self::$password) OR trigger_error(mysql_error(),E_USER_ERROR);
            mysql_select_db(self::$database, $conn);
            
            //Set record count
            if($total_records == 0){
                $query = mysqli_query($conn, "SELECT COUNT(*) AS count " . $sql) OR trigger_error(mysql_error(),E_USER_ERROR);
                $rec = mysql_fetch_assoc($query);
                $mainData["total-records"] = $total_records = ($rec["count"]) ? $rec["count"] : 0;
            }
            
            $query = mysqli_query($conn, $select . $sql) OR trigger_error(mysql_error(),E_USER_ERROR);
            if (mysql_num_rows($query) > 0) {
                while ($row = mysql_fetch_assoc($query)) {
                    $data[] = $row;
                }
            }
            
            //Handling return data
            switch($return_data){
                case 'HTML' :
                    //:TODO
                    $viewData = "";
                    break;
                case 'JSON' :
                    $viewData = $data;
                    break;
                case 'PLAIN' :
                    //:TODO
                    $viewData = "";
                    break;
            }
        
            $mainData["data"] = $viewData;
            
            mysql_close($conn);
            
        } catch(Excetion $e){
            //throw new Exception($e-getMessage());
        }
        
        return json_encode($mainData);
    }
    
    /**
     * 
     * @param unknown $options
     */
    public static function List($options)
    {
    }
    
    
    
    
}