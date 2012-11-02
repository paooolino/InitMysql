<?php

class InitMysql{
	private $parts = array();
	
	function __construct(){
		$this->parts["EXT"] = "`%s` INT(10) UNSIGNED NOT NULL DEFAULT '0',";
		$this->parts["INTEGER"] = "`%s` INT(10) NOT NULL DEFAULT '0',";
		$this->parts["BOOL"] = "`%s` TINYINT(1) NOT NULL DEFAULT '0',";
		$this->parts["FLOAT"] = "`%s` FLOAT(10,2) NOT NULL DEFAULT '0',";
		$this->parts["NORMAL"] = "`%s` VARCHAR(255) NOT NULL DEFAULT '',";
		$this->parts["TEXT"] = "`%s` TEXT NOT NULL DEFAULT '',";
	}
	
	public function createTable($tableName, $fields){
		mysql_query("DROP TABLE `$tableName`");
		
		$a_fields = explode(",", $fields);
		
		$query_fields = "";
		foreach($a_fields as $field){
			$field_parts = explode(":", $field);
			if(count($field_parts)>1){
				$field_subparts = explode(">", $field_parts[1]);
			} else {
				$field_subparts = array("NORMAL");
			}
			$field_name = trim($field_parts[0]);
			$field_type = trim($field_subparts[0]);
			
			$query_fields .= sprintf( $this->parts[$field_type], $field_name);
		}
		
		$query = "CREATE TABLE `$tableName` (  
			`ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  
			$query_fields
			`order` INT(10) UNSIGNED NOT NULL,  
			`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,  
			PRIMARY KEY (`ID`) ) COLLATE='utf8_general_ci' ENGINE=InnoDB ROW_FORMAT=DEFAULT;
		";
		
		mysql_query($query);
	}
	
}

?>
<?php
	mysql_connect("localhost","root","");
	mysql_select_db("testDB");
	$initier = new InitMysql();
	$initier -> createTable("lessons", "name");
	$initier -> createTable("slides", "id_lesson:EXT>lessons, title, text:TEXT");
?>
