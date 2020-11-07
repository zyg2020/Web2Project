 <?php
     define('DB_DSN','mysql:host=localhost;dbname=serverside;charset=utf8');
     define('DB_USER','serveruser');
     define('DB_PASS','gorgonzola7!');     
     
     try {
         // Try creating new PDO connection to MySQL.
         $db = new PDO(DB_DSN, DB_USER, DB_PASS);
         //,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
     } catch (PDOException $e) {
         print "Error: " . $e->getMessage();
         die(); // Force execution to stop on errors.
         // When deploying to production you should handle this
         // situation more gracefully. ¯\_(ツ)_/¯
     }

     // class MyPDO{
     //    private $dsn = 'mysql:host=localhost;dbname=serverside;charset=utf8';
     //    private $user = 'serveruser' ;
     //    private $password = 'gorgonzola7!';
     //    private $db;
     //    private static $instance = null;

     //    private function __construct(){
     //         try {
     //             // Try creating new PDO connection to MySQL.
     //             $this->db = new PDO($this->dsn,$this->user,$this->password);
     //         } catch (PDOException $e) {
     //             print "Error: " . $e->getMessage();
     //             die(); // Force execution to stop on errors.
     //             // When deploying to production you should handle this
     //             // situation more gracefully. ¯\_(ツ)_/¯
     //         }
     //    }

     //    public static function getInstance(){
     //        if (self::$instance == null) {
     //            self::$instance = new MyPDO();
     //        }

     //        return self::$instance;
     //    }

     //    public function getCategories($queryId){
     //        $CorrespondingCategoryQuery = "SELECT c.name FROM projects p 
     //                                    INNER JOIN projectscategories pc
     //                                        ON p.id = pc.projectId
     //                                    INNER JOIN categories c
     //                                        ON c.id = pc.categoryId
     //                                        WHERE p.id = :id";
     //        $mydb = $this->$db;
     //        $CategorySatement = $mydb->prepare($CorrespondingCategoryQuery);
     //        $CategorySatement = $CategorySatement->bindValue(':id', $queryId);
     //        $CategorySatement->execute();
     //        return $CategorySatement->fetchAll();
     //    }
     // }

     // $mydb = MyPDO::getInstance();
     
     // print_r($mydb->getCategories(1));
 ?>