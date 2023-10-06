<?php
echo "*** Welcome to WordGame ***\n";
echo "Enter your word to play: ";
$input = fgets(STDIN);
$input = trim($input);

$ch = curl_init();
$apiUrl = "https://127.0.0.1:8000/api/checkWord";
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
]);

do {
  $dataToSend = json_encode(['word' => $input]);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $dataToSend);


  $response = curl_exec($ch);
  $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if ($httpStatusCode !== 200) {
    echo $httpStatusCode === 404 ? "Entered word not found!\n" : "Error! HTTP Status Code: " . $httpStatusCode . "\n";
  } else {
    if ($response) {
      $data = json_decode($response, true);
      echo "Score for entered word \"" . $data['word'] . "\" is: " . $data['score'] . "\n";
    } else {
      echo "Response is empty.\n";
    }
  }

  echo "Enter another word to play more [or just press Enter to exit]: ";
  $input = fgets(STDIN);
  $input = trim($input);
} while (!empty($input));

curl_close($ch);
?>