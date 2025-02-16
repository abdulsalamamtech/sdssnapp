
200 OK
201 Created

401 - Invalid parameters

422 Unprocessable Entity
404 Not Found

500 Internal Server Error


<!-- PHP Match -->
$value = 2;  

$result = match ($value) {  
    1 => 'One',  
    2 => 'Two',  
    3 => 'Three',  
    default => 'Other',  
};  

echo $result; // Outputs: Two


<!-- Forward port -->
ssh -R 80:localhost:8000 nokey@localhost.run


<!-- Access SSH -->
ssh sdssn-tms@46.202.140.89
