<?php
phpinfo();die;

//for live server use 'www' for test server use 'sandbox'
$wsdl='https://sandbox.usaepay.com/soap/gate/0AE595C1/usaepay.wsdl';

// instantiate SoapClient object as $client
$client = new SoapClient($wsdl);

$sourcekey = '3K3070JOzmqp0h936O2ligRNu0T8tXcR';
$pin = 'evol'; 

// generate random seed value
$seed=mktime() . rand();

// make hash value using sha1 function
$clear= $sourcekey . $seed . $pin;
$hash=sha1($clear);

// assembly ueSecurityToken as an array
$token = array(
    'SourceKey'=>$sourcekey,
    'PinHash'=>array(
       'Type'=>'sha1',
       'Seed'=>$seed,
       'HashValue'=>$hash
    ),
    'ClientIP'=>$_SERVER['REMOTE_ADDR'],
);
//print_r($token);die;

try {
 
  $Request=array(
    'AccountHolder' => 'Tester Jones',
    'Details' => array(
      'Description' => 'Example Transaction',
      'Amount' => '4.00',
      'Invoice' => '44539'
    ),
    'CreditCardData' => array(
      'CardNumber' => '4444555566667779',
      'CardExpiration' => '0909',
      'AvsStreet' => '1234 Main Street',
      'AvsZip' => '99281',
      'CardCode' => '999'
    )
  );
  //print_r($token);
  //print_r($Request);die;
  $res=$client->runTransaction($token, $Request);
 
  print_r($res);
 
}
catch (SoapFault $e){
  echo $client->__getLastRequest();
  echo $client->__getLastResponse();
  die("runTransaction failed :" .$e->getMessage());
}
exit;

phpinfo();die;

$con=mysqli_connect("172.17.0.1","root","123456","mysql");
// Check connection
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// Perform queries
$result = mysqli_query($con,"show tables");
while($table = mysqli_fetch_array($result)) {
	echo($table[0] . "<BR>");    // print the table that was returned on that row.
}

?>
