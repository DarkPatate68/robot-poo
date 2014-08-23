<?php
namespace Library;
 
class PDOFactory
{
  public static function getMysqlConnection(HTTPResponse $http = null)
  {
	try 
	{
		$bdd = new \PDO('mysql:host=localhost;dbname=robot', 'root', '');
	}
	catch (\PDOException $e) 
	{
		if($http !== null)
			$http->redirectBDD($e->getMessage());
		else
		{
			echo 'ERREUR';
			exit;
		}
	}
    
	$bdd->query('SET NAMES \'utf8\'');
    $bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
     
    return $bdd;
  }
}