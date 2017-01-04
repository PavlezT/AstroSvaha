<?

$user = "root";
$password="";
$dbname="db-main";
$dbUrlConnect = "127.0.0.1:3306";

$users = array();

if(isset($_POST['pagenum'])){
/* if db made by procedure method */
  $link = mysqli_connect($dbUrlConnect, $user , $password, $dbname);

  $yearMax = AgeToYear($_POST['ageMin']);
  $yearMin = AgeToYear($_POST['ageMax']);

  if ($result = mysqli_query($link, "SELECT Name as name, City_settlement as place, Year_brth as age,Month_brth as month,Day_brth as day, Marital_status as family,
      About_myself as about, Photo.Photo_1 as photo FROM Questionnaire JOIN Photo ON Questionnaire.ID=Photo.ID
      WHERE Gender='".$_POST['floor']."' AND Marital_status='".$_POST['familyposition']."' AND Children='".$_POST['children']."' AND
      Attitude_smoking='".$_POST['smoking']."' AND Attitude_alcohol='".$_POST['drink']."' AND Name LIKE '%".$_POST['name']."%' AND City_country LIKE '%".$_POST['placelivingCountry']."%'
      AND City_settlement LIKE '%".$_POST['placelivingCity']."%' AND Eyes_color='".$_POST['eyes']."' AND Hair_color='".$_POST['hair']."'
      AND Year_brth BETWEEN ".substr($yearMin,0,4)." AND ".substr($yearMax,0,4)." AND Height BETWEEN ".$_POST['heightMin']." AND ".$_POST['heightMax']." AND Weight BETWEEN ".$_POST['weightMin']." AND ".$_POST['weightMax']."
      LIMIT ".(($_POST['pagenum'] -1 )*21).",".($_POST['pagenum']* 21)."")) {

      if(mysqli_num_rows($result) > 0){
          while ($row = mysqli_fetch_assoc($result)){
             array_push($users, array(
                   'name' => $row['name'],
                   'photo' =>  $row['photo'],
                   'place' => $row['place'],
                   'age' => YearToAge($row['age'],$row['month'],$row['day']),
                   'family' => $row['family'],
                   'about' => $row['about']
                )
             );
          }
       }

       mysqli_free_result($result);
   }
}
if(isset($_GET['pagenum'])){
   $link = mysqli_connect($dbUrlConnect, $user , $password, $dbname);

   if ($result = mysqli_query($link, "SELECT Name as name, City_settlement as place, Year_brth as age,Month_brth as month,Day_brth as day, Marital_status as family,
       About_myself as about, Photo.Photo_1 as photo FROM Questionnaire JOIN Photo ON Questionnaire.ID=Photo.ID
       WHERE Gender='".$_GET['floor']."' LIMIT ".(($_GET['pagenum'] -1 )*21).",".($_GET['pagenum']* 21)."")) {

       if(mysqli_num_rows($result) > 0){
           while ($row = mysqli_fetch_assoc($result)){
              array_push($users, array(
                    'name' => $row['name'],
                    'photo' =>  $row['photo'],
                    'place' => $row['place'],
                    'age' => YearToAge($row['age'],$row['month'],$row['day']),
                    'family' => $row['family'],
                    'about' => $row['about']
                 )
              );
           }
        }

        mysqli_free_result($result);
    }
}

echo json_encode($users);

function YearToAge($year,$month,$day){
   $age = date_diff(date_create($day.'-'.$month.'-'.$year), date_create('now'))->y;
   return $age;
}

function AgeToYear($age){
   $year = date('Y-m-d', strtotime($age . ' years ago'));
   return $year;
}

?>
