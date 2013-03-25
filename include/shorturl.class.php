<?php
// +----------------------------------------------------------------------
// | shorturl for mongoDB
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://bigpu.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: dodge <dodgepudding@gmail.com>
// +----------------------------------------------------------------------
// $Id: shorturl.class.php 2012 2012-09-06 10:00:00Z dodge $

class shorturl {
	private $_db = 'shorturl';
	private $_table = 'url';
	private $_sumtable = 'click';
	private $_keys = "23456789abcdefghijkmnpqrstuvwxyz";
	private $_keylength = 6;
	private $_domain = "example.com";
	private $_host = "localhost";
	private $_port = 27017;
	private $_username;
	private $_password; 
	static $handle;
	private $conn;
	public function __construct($_options) {
		if (is_array($_options)) {
			if (!empty($_options['db'])) $this->_db = $_options['db'];
			if (!empty($_options['table'])) $this->_table = $_options['table'];
			if (!empty($_options['sumtable'])) $this->_sumtable = $_options['sumtable'];
			if (!empty($_options['keys'])) $this->_keys = $_options['keys'];
			if (!empty($_options['keylength'])) $this->_keylength = $_options['keylength'];
			if (!empty($_options['host'])) $this->_host = $_options['host'];
			if (!empty($_options['port'])) $this->_port = $_options['port'];
			if (!empty($_options['domain'])) $this->_domain = $_options['domain'];
			if (!empty($_options['username'])) $this->_username = $_options['username'];
			if (!empty($_options['password'])) $this->_password = $_options['password'];
			if (!class_exists('Mongo')) return false;
			if (!empty($this->_username) && !empty($this->_password))
				$constr = 'mongodb://'.$this->_username.':'.$this->_password.'@'.$this->_host.':'.$this->_port;
			else
				$constr = 'mongodb://'.$this->_host.':'.$this->_port;
			if (!self::$handle)
				self::$handle = new Mongo($constr);
			$db = self::$handle->selectDB($this->_db);
			$this->conn = $db->selectCollection($this->_table);
			if (!$this->conn) return false;
		}
	}

	private function generate_url($length=6) {
	    $i    = 0;
	    $url  = "";
	    while ($i < $length) {
	      $random = mt_rand(0, strlen($this->_keys) - 1);
	      $url   .= $this->_keys{$random};
	      $i++;
	    }
	    return $url;
	}
	
	public function sum($shorturl) {
		$db = self::$handle->selectDB($this->_db);
		$conn = $db->selectCollection($this->_sumtable);
		if (!$conn) return false;
		$result = $conn->findOne(array("shorturl"=>$shorturl));
		if($result){
			$result['count']++;
			$re = $conn->update( array( "shorturl" => $shorturl),  array( "shorturl" => $shorturl,"count" =>$result['count'],"mtime" => time()));
			return $result['count'];
		} else {
			$conn->insert(array( "shorturl" => $shorturl,"count" => 1,"mtime" => time()));
			return 1;
		}
	}
	public function getsum($shorturl){
		$db = self::$handle->selectDB($this->_db);
		$conn = $db->selectCollection($this->_sumtable);
		$result = $conn->findOne(array("shorturl"=>$shorturl));
		if($result){
			return $result['count'];
		}else{
			return false;
		}
	}
	public function get($shorturl){
		$result = $this->conn->findOne(array("shorturl"=>$shorturl));
		if($result) {
			$this->sum($shorturl);
			return $result['sourceurl'];
		}else {
			return false;
		}
	}

	public function set($sourceurl,$shorturl=''){
		if ($sourceurl{strlen($sourceurl) - 1} == "/") {
			$sourceurl = substr($sourceurl, 0, -1);
		}
	
	    if (!preg_match("/^(ht|f)t(p|ps)\:\/\//si", $sourceurl)) {
	        $sourceurl = "http://".$sourceurl;
	    }
	
		$result = false;
	
		do{
	
			$pos = strpos($sourceurl, $this->_domain);
			if($pos == true){
				$short_url = $sourceurl;
				$result = true;
				break;			
			}
			
			if($arr = $this->conn->findOne(array( "sourceurl" => $sourceurl))){
				if (empty($shorturl))
					$short_url = $arr['shorturl'];
				elseif ($arr['shorturl']!=$shorturl){
					if (!$this->conn->findOne(array( "shorturl" => $shorturl))) {
						$re = $this->conn->update(array( "sourceurl" => $sourceurl),  array("sourceurl" => $sourceurl,"shorturl" => $shorturl,"ctime" => time()));
						$short_url = $shorturl;
					}
				}
				$result = true;
				break;
			}
	
			$short_url = $shorturl?$shorturl:"http://".$this->_domain."/".$this->generate_url($this->_keylength);
	
			if($this->conn->findOne(array( "shorturl" => $short_url))){
				$result = false;
				break;
			}else{
				$this->conn->insert( array( "shorturl" => $short_url,"sourceurl" => $sourceurl,"ctime" => time() ) );
				$result = true;
				break;
			}
		} while($result == true && $shorturl!=='');
		if ($result)
			return $short_url;
		else
			return false;
	}
	
	public function edit($sourceurl,$shorturl) {
		if ($sourceurl{strlen($sourceurl) - 1} == "/") {
			$sourceurl = substr($sourceurl, 0, -1);
		}
	
		if (!preg_match("/^(ht|f)t(p|ps)\:\/\//si", $sourceurl)) {
			$sourceurl = "http://".$sourceurl;
		}
	
		if($this->conn->findOne(array( "shorturl" => $shorturl))){
			$re = $this->conn->update( array( "shorturl" => $shorturl),  array( "shorturl" => $shorturl,"sourceurl" => $sourceurl,"ctime" => time() ));
			if ($re) return $shorturl; else return false;
		} else {
			return false;
		}
	}
	
	public function delete($shorturl) {
		return $this->conn->remove(array("shorturl" => $shorturl),array("justOne" => true));
	}
}