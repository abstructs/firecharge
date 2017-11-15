<?php
class UserModel {
    
    private $db;
    
    public function __construct() {}
    
    // EFFECTS: sets $db to the database connection
    // MODIFIES: $db
    // REQUIRES: there must be a database with name in database variable
    // RETURNS: boolean
    // EXCEPTION: Throws an exception when it fails to connect to the database
    private function connect() {
        // Check connection
        $servername = getenv('IP');
        $username = getenv('C9_USER');
        $password = "";
        $database = "dev";
        $dbport = 3306;
         
        // Create connection
        $this->db = new mysqli($servername, $username, $password, $database, $dbport);

        // Check connection
        if ($this->db->connect_error) {
            throw new Exception($this->db->connect_error);
        }
        
        return true;
    }
    
    // EFFECTS: 
    // MODIFIES: $_SESSION['user_id']
    private function createSession($id) {
        session_start();
        $_SESSION['User_Id'] = $id;
    }

    public function findAll() {
        if($this->connect()) {
            $stmt = $this->db->prepare('SELECT Username FROM User;');
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            return $result;
        }
        
        return false;
    }
    
    // EFFECTS: finds a user from the database with given username
    // REQUIRES: user must exist in the database
    // RETURNS: user or false
    public function findUserByUsername($username) {
        if($this->connect()) {
            $stmt = $this->db->prepare("SELECT * FROM User WHERE Username= ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            
            $result = $stmt->get_result();
            
            return $result;
        }
        
       die("Inernal error.");
    }
    
    // EFFECTS: find the username by ID
    // REQUIRES: user with the id must exist in the database
    // RETURNS: user or false
    public function findUserById($id) {
        if($this->connect()) {
            $stmt = $this->db->prepare("SELECT * FROM USER WHERE User_Id=? LIMIT 10");
            $stmt->bind_param('s',$id);
            $stmt->execute();
            
            $result = $stmt->get_result();
            return $result;
        }
        
        return false;
    }
    
    //EFFECT: checks the database for a user with the given username
    //        returns false if none found
    public function findByUsername($username, $max_return=10) {
        $this->connect();
        
        $stmt = $this->db->prepare("SELECT * FROM User WHERE Username=? LIMIT ?");
        $stmt->bind_param('si', $username, $max_return);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if($this->db->error) {
            return false;
        }
        
        if($result->num_rows < 1) {
            return false;
        }
        
        return $result;
    }
    
        //EFFECT: searches a column for a field
    public function searchByUsername($username, $max_return=10) {
        $this->connect();
        
        $like_param = $username ."%";
        
        $stmt = $this->db->prepare("SELECT * FROM User WHERE Username LIKE ? LIMIT ?");
        $stmt->bind_param('si', $like_param, $max_return);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if($this->db->error) {
            return false;
        }
        
        if($result->num_rows < 0) {
            return false;
        }
        
        return $result;
    }
    
    public function findByEmail($email) {
        $this->connect();
        
        $stmt = $this->db->prepare("SELECT User_Id, Username, Password FROM User WHERE Email=?;");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if($result->num_rows < 1) {
            return false;
        }
        
        return $result->fetch_array(MYSQLI_ASSOC);
    }
    
    public function create($email, $username, $password) {
        $this->connect();
        
        if(!$this->isValidUserInfo($email, $username, $password)) return false;
        
        $stmt = $this->db->prepare('INSERT INTO User (Email, Username, Password) VALUES (?, ?, ?);');

        $stmt->bind_param('sss', $email, $username, crypt($password));

        $stmt->execute();
        
        $result = $stmt->get_result();
            
        if($this->db->error) {
            return false;
        }
            
        return true;
    }
    
    public function userExists($email, $username="") {
        $this->connect();
        if($username == "") {
            $stmt = $this->db->prepare("SELECT * FROM User WHERE Email=?;");
            $stmt->bind_param('s', $email);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM User WHERE Email=? OR Username=?;");
            $stmt->bind_param('ss', $email, $username);
        }

        $stmt->execute();

        $result = $stmt->get_result();
        echo var_dump($result->fetch_array(MYSQLI_ASSOC));
        return $result->num_rows > 0;
    }
    
    public function isLoggedIn() {
        session_start();
        return isset($_SESSION['User_Id']);
    }
    
    // EFFECTS: removes the user from the session
    // MODIFIES: $_SESSION['user_id']
    // RETURNS: boolean
    public function logout() {
        session_start();
        unset($_SESSION['User_Id']);
    }
    
    // EFFECTS: validates the user information
    // REQUIRES: fields must be non empty, password & password_confirm must match
    //           username must be between 3 and 15 characters, 
    //           password must be between 6 and 25 characters,
    // RETURNS: boolean
    private function isValidUserInfo($email, $username, $password) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($username) && !empty($password) && !$this->userExists($email, $username);
    }
    
    public function update_user($username,$password,$email,$User_Id){
        if(!connect()){
            return false;
            
        }
        $stmt=$this->db->prepare("UPDATE User SET Username='?',Password='?',Email='?' WHERE Name=?;");
        $stmt->bind_param("ssss",$username,$password,$email,$User_Id);
        $stmt->execute();
    }
    
    public function delete_user ($username){

        if(!connect()){
            return false;
            }
           $stmt=$this->db->prepare("DELETE FROM User WHERE Name='?'");
           $stmt->bind_param('s',$username);
           $stmt->execute();
        
    }
}