<?php
class User {
	/**
	 * Array of fields of user table, first one is unique
	 * @var array
	 */
	private static $_fields = array( 'name', 'username', 'roll', 'mobile' , 'email', 'pass' );
	private $_username;
	private $_name;
	private $_email;
	private $_mobile;
	private $_designation;
	private $_level;
	private $_token;
	private $_password;
	private $_roll;
	public function construct( $username, $password, $roll, $name='', $email='', $mobile='', $designation='', $level = 1 ) {
		$this->_username = $username;
		$this->_name = $name;
		$this->_roll = $roll;
		$this->_email = $email;
		$this->_mobile = $mobile;
		$this->_designation = $designation;
		$this->_level = $level;
		$this->_password = $password;
		var_dump($username);
	}
	/**
	 * Adds user with data to database
	 * @param array $data array of values to insert into user table in the same order
	 */
	public function addUser( $data ) {
		$user = self::$_fields[0];
		$query = "SELECT * from `user` where username=? OR roll=?";
		$db = DB::getInstance( 'Config' );
		$name = $data[0];
		$roll = $data[2];
		$db->query( $query , array( $name, $roll ) );
		if ( $db->getCount() > 0 ) {
			return USER_EXISTS;
		}
		//var_dump($data);
		$hash = password_hash($data[5],PASSWORD_DEFAULT);
		$data[5] = $hash;
		$db->insert( "user", self::$_fields , $data );
		if ( $db->getError() != false ) {
			return $db->getErrorInfo();
		}
		return true;
	}
	public function loadFromDb( $username, $password ) {
		$query = "SELECT * from `user` where username=?";
		$db = DB::getInstance( 'Config' );
		$db->query( $query , array( $username ) );
		if ( $db->getError() === true || $db->getCount() == 0 ) {
			return false;
		} else {
			$result = $db->getResult()[0];
			if ( password_verify( $password, $result['pass'] ) ) {
				//return new User( $result['username'], $result['Name'], $result['Mobile'], $result['Email'], $result['Designation'], $result['level'] );
				return $result;
			} else {
				return false;
			}
		}
	}
	public function verifyInput( $data ) {
		$error = "";
		if( !isset( $data['username'], $data['name'],$data['password'],$data['email'],$data['number']) ) {
			$error = "One of the fields is missing";
			$_SESSION['err'] = $error;
			//header("Location:register.php");
		} else if( strlen( $data['name'] ) > 50 || strlen( $data['password'] ) > 50 ) {
			$error = "name or password is too long(max 50)";
			$_SESSION['err'] = $error;
			//errorRedirect($error,"register.php");
			//preg_match( '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/', $data[ 'email' ] )
		}else if ( !filter_var($data['email'],FILTER_VALIDATE_EMAIL)) {
			$error = "Invalid email";
			$_SESSION['err'] = $error;
			//errorRedirect($error,"register.php");
		}
		if ( !empty( $error ) ) {
			return $error;
		} else {
			return true;
		}
	}
	public function setToken( $token ) {
		$this->_token = $token;
	}
	public function getToken() {
		return $this->_token;
	}
	public function getLevel() {
		return $this->_level;
	}
	public function getUserName() {
		return $this->_username;
	}
	public function getEmail() {
		return $this->_email;
	}
	public function getMobile() {
		return $this->_mobile;
	}
	public function getDesignation() {
		return $this->_designation;
	}
}
