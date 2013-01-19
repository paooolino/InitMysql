<?php
/*
 *	InitMysql
 *		A PHP class that helps in MySQL database tables creation
 *		
 *	Paolo Savoldi 2012
 *	paooolino@gmail.com		
 */
class InitMysql{
	private $parts = array();
	private $fields = array();
	
	function __construct(){
		$this->parts["EXT"] = "`%s` INT(10) UNSIGNED NOT NULL DEFAULT '0',";
		$this->parts["INTEGER"] = "`%s` INT(10) NOT NULL DEFAULT '0',";
		$this->parts["BOOL"] = "`%s` TINYINT(1) NOT NULL DEFAULT '0',";
		$this->parts["FLOAT"] = "`%s` FLOAT(10,2) NOT NULL DEFAULT '0',";
		$this->parts["NORMAL"] = "`%s` VARCHAR(255) NOT NULL DEFAULT '',";
		$this->parts["TEXT"] = "`%s` TEXT NOT NULL DEFAULT '',";
	}
	
	public function createTable($tableName, $fields){
		$fieldnames = array();
		 
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
			
			array_push($fieldnames, $field_name);
		}
		
		$query = "CREATE TABLE `$tableName` (  
			`ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  
			$query_fields
			`order` INT(10) UNSIGNED NOT NULL,  
			`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,  
			PRIMARY KEY (`ID`) ) COLLATE='utf8_general_ci' ENGINE=InnoDB ROW_FORMAT=DEFAULT;
		";
		
		mysql_query($query);
		array_push($this->fields, array($tableName, $fieldnames));
	}
	
	public function createCustomHelperTables($ext){
		mysql_query("DROP TABLE `".$ext."_LABELS`");
		mysql_query("DROP TABLE `".$ext."_OWNERS`");
		mysql_query("DROP TABLE `".$ext."_USERS`");
		
		$query = "
			CREATE TABLE IF NOT EXISTS `".$ext."_LABELS` (
			  `IDLabel` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `table` varchar(255) NOT NULL DEFAULT '',
			  `field` varchar(255) NOT NULL DEFAULT '',
			  `value` mediumtext NOT NULL,
			  PRIMARY KEY (`IDLabel`)
			) COLLATE='utf8_general_ci' ENGINE=InnoDB ROW_FORMAT=DEFAULT;
		";
		mysql_query($query);

		$query = "
			CREATE TABLE IF NOT EXISTS `".$ext."_OWNERS` (
			  `IDrule` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `tablename` varchar(255) NOT NULL DEFAULT '',
			  `owner` varchar(255) NOT NULL DEFAULT '',
			  PRIMARY KEY (`IDrule`)
			) COLLATE='utf8_general_ci' ENGINE=InnoDB ROW_FORMAT=DEFAULT;	
		";		
		mysql_query($query);
		
		$query = "
			CREATE TABLE IF NOT EXISTS `".$ext."_USERS` (
			  `IDUser` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `username` varchar(255) NOT NULL DEFAULT '',
			  `password` varchar(255) NOT NULL DEFAULT '',
			  `admin` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`IDUser`)
			) COLLATE='utf8_general_ci' ENGINE=InnoDB ROW_FORMAT=DEFAULT;		
		";
		mysql_query($query);
		
		// populate LABELS table...
		print_r($this->fields);
		foreach($this->fields as $t){
			mysql_query("INSERT INTO `".$ext."_LABELS` (`table`) VALUES ('".$t[0]."')");		
			foreach($t[1] as $f){
				mysql_query("INSERT INTO `".$ext."_LABELS` (`table`, `field`) VALUES ('".$t[0]."', '".$f."')");			
			}
		}
	}
	
}

?>
<?php
	mysql_connect("localhost","root","");
	mysql_select_db("politico");
	$initier = new InitMysql();
	$initier -> createTable("pol_010pagine", "nomepagina, img_fotopagina, titolopagina_it, txt_testopagina_it:TEXT, url_it, seo_title, seo_keywords, seo_desc");
	$initier -> createTable("pol_020news", "titolonews_it, txt_testonews_it:TEXT, img_fotonews, fil_allegatonews, url_it, seo_title, seo_keywords, seo_desc");
	$initier -> createTable("pol_030eventi", "titoloevento_it, txt_testoevento_it:TEXT, img_fotoevento, fil_allegatoevento, url_it, seo_title, seo_keywords, seo_desc");
	$initier -> createTable("pol_040rassegna", "titolorassegna_it, txt_testorassegna_it:TEXT, img_fotorassegna, fil_allegatorassegna, url_it, seo_title, seo_keywords, seo_desc");
	$initier -> createTable("pol_050fotogallery", "titolofoto, img_fotografia, txt_descrizionefoto_it:TEXT, url_it, seo_title, seo_keywords, seo_desc");
	$initier -> createTable("pol_060videogallery", "linkyoutube, txt_descrizionevideo_it:TEXT, url_it, seo_title, seo_keywords, seo_desc");
	
	$initier -> createCustomHelperTables("pol");
?>
