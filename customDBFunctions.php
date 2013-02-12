<?php

class customDBFunctions extends Model {
    
    private $db; 
    
    //can grab this from the constants file
    function __construct(){
        $this->db = new mysqli(DB_HOST, DB_USER, '', DB_DATABASE);
    }
    

    private function select($table, $arr){
        $query = "SELECT * FROM " . $table;
        $pref = " WHERE ";
        foreach($arr as $key => $value)
        {
            $query .= $pref . $key . "='" . $value . "'";
            $pref = " AND ";
        }
        $query .= ";";
        return $this->db->query($query);
    }
    

    private function insert($table, $arr)
    {
        $query = "INSERT INTO " . $table . " (";
        $pref = "";
        foreach($arr as $key => $value)
        {
            $query .= $pref . $key;
            $pref = ", ";
        }
        $query .= ") VALUES (";
        $pref = "";
        foreach($arr as $key => $value)
        {
            $query .= $pref . "'" . $value . "'";
            $pref = ", ";
        }
        $query .= ");";
        return $this->db->query($query);
    }
    

    private function delete($table, $arr){
        $query = "DELETE FROM " . $table;
        $pref = " WHERE ";
        foreach($arr as $key => $value)
        {
            $query .= $pref . $key . "='" . $value . "'";
            $pref = " AND ";
        }
        $query .= ";";
        return $this->db->query($query);
    }
 
    private function exists($table, $arr){
        $res = $this->select($table, $arr);
        return ($res->num_rows > 0) ? true : false;
    }
    
    public function userValidate($hash){
        $query = "SELECT Users.* FROM Users JOIN (SELECT username FROM UserAuth WHERE hash = '"; 
        $query .= $hash . "' LIMIT 1) AS UA WHERE Users.username = UA.username LIMIT 1";
        $res = $this->db->query($query);
        if($res->num_rows > 0)
        {
            return $res->fetch_object();
        }
        else
        {
            return false;
        }
    }
    
    public function userRegister($user){
        $emailCheck = $this->exists("Users", array("email" => $user['email']));
        if($emailCheck){
            return 1;
        }
        else{
            $userCheck = $this->exists("Users", array("username" => $user['username']));
            if($userCheck){
                return 2;
            }
            else{
                $user['created_at'] = date( 'Y-m-d H:i:s');
                $user['gravatar_hash'] = md5(strtolower(trim($user['email'])));
                $this->insert("Users", $user);
                $this->authorizeUser($user);
                return true;
            }
        }
    }
    
    public function authorizeUser($user){
        $chars = "j];L@u`s.BT/e8L2rBgWVoZ$3(X0_JX~}0dY1eJI?t*HX|`a*u:1sj)9N]0U)RM8";
        $hash = sha1($user['username']);
        for($i = 0; $i<12; $i++)
        {
            $hash .= $chars[rand(0, 64)]; 
        }
        $this->insert("UserAuth", array("hash" => $hash, "username" => $user['username']));
        setcookie("Auth", $hash);
    }
    
    public function attemptLogin($userInfo){
        if($this->exists("Users", $userInfo))
        {
            $this->authorizeUser($userInfo);
            return true;
        }
        else{
            return false;
        }
    }
    
    public function logoutUser($hash){
        $this->delete("UserAuth", array("hash" => $hash));
        setcookie ("Auth", "", time() - 3600);
    }
    
   
}
