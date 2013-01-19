InitMysql
=========

A PHP class that helps in MySQL database tables creation.

  USAGE
	
		1) Include InitMysql
			
			> include("InitMysql.php");
			
		2) Have a database connection
			
			> mysql_connect("localhost","root","");
			> mysql_select_db("testDB");		
			
		3) Create initier object
			
			> $initier = new InitMysql();
			
		4) Let's create tables!
			
			> $initier -> createTable("lessons", "name");
			> $initier -> createTable("slides", "id_lesson:EXT>lessons, title, text:TEXT");
			
		5) At the end, it's possible to create the helper tables for the custom control panel.
		
			> $initier -> createCustomHelperTables( "prepend" );
			
	METHODS
	
		createTable(<string: tableName>, <string: fields>);
		
		Creates a new table.
		
		<string: tableName> 	The table name.
		
		<string: fields> 		A list of field name separed by comma in the following format:
									<field name>[:<field type>]
									If not specified, field type is assumed to be "NORMAL" (VARCHAR 255).
									Other types are defined in the parts array (see the constructor code).
									"ID", "created" and "order" fields are automatically added.
