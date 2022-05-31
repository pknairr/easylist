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
     * @param unknown $options
     * $options array : select, joins, from, conditions, group, having, limit, offset, order 
     */
    public static function Page($options)
    {
        $sql = "";
        $query = "";
        $data = array();
        
        try{
            if(!isset($options['select']) || trim($options['select']) == "" || !isset($options['from']) || trim($options['from']) == "" ){
                throw new Exception("Select OR From clause is missing.");
            } else {
                $sql .= "SELECT " . $options['select'] . " FROM " . $options['from'];
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
            
            $query = mysqli_query($conn, $sql) OR trigger_error(mysql_error(),E_USER_ERROR);
            
            if (mysql_num_rows($query) > 0) {
                while ($row = mysql_fetch_assoc($query)) {
                    $data[] = $row;
                }
            }
            
            mysql_close($conn);
            
        } catch(Excetion $e){
            //throw new Exception($e-getMessage());
        }
        
        return json_encode($data);
        //   return $sql;
    }
    
    /**
     * 
     * @param unknown $options
     */
    public static function List($options)
    {
    }
    
    
    
    
}