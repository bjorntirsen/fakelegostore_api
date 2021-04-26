<?php
//Ange lämpliga http headers
//läs mer här: https://stackoverflow.com/questions/10636611/how-does-access-control-allow-origin-header-work
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header(
  'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept'
);
header('Referrer-Policy: no-referrer');

//php starkt baserat på arrayer JS på objekt
$genders = ['female', 'male'];
$femalefirstNames = ['Åsa', 'Kerstin', 'Elisabeth', 'Carina', 'Kristina', 'Johanna', 'Emelie', 'Pia', 'Lisbeth', 'Nina'];
$malefirstNames = ['Robert', 'Tommy', 'Adam', 'Hugo', 'Rolf', 'Oskar', 'Jimmy', 'Björn', 'Georgios', 'Özgür'];
$lastNames = ['Öberg', 'Goussis', 'Tirsén', 'Rickerdsson', 'Hovinmaa', 'Viklund', 'Sjögren', 'Samuelsson', 'Strömberg', 'Lindgren'];

$names = [];

function replaceSpecialCharacters($string) {
  return preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
}

function createEmailSlug($firstname, $lastname) {
  $firstname = replaceSpecialCharacters($firstname);
  $lastname = replaceSpecialCharacters($lastname);
  $firstTwoFromFirstname = substr($firstname, 0, 2);
  $firstThreeFromLastname = substr($lastname, 0, 3);
  return strtolower($firstTwoFromFirstname . $firstThreeFromLastname . '@example.com');
}

for ($i = 0; $i < 10; $i++) {
  $name = ['gender' => $genders[rand(0,1)]];
  if ($name['gender'] == 'female') {
    $name['firstname'] = $femalefirstNames[rand(0, 9)];
  }
  else {
    $name['firstname'] = $malefirstNames[rand(0, 9)];
  }
  $name['lastname'] = $lastNames[rand(0, 9)];
  $name['age'] = rand(1, 100);
  $name['email'] = createEmailSlug($name['firstname'], $name['lastname']);
  array_push($names, $name);
}

$json = json_encode($names, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

echo $json;

?>
