<?php  echo "hello \n";

$models = array();
$fileObject = new \SplFileObject('./1.csv','r');
/* $fileObject->setFlags(
            \SplFileObject::READ_CSV
            | \SplFileObject::READ_AHEAD
            | \SplFileObject::SKIP_EMPTY
            | \SplFileObject::DROP_NEW_LINE
        ); */
$fileObject->rewind();
$_colNames = $fileObject->fgetcsv("\t", "\"", "\\");
print_r($_colNames);
$fileObject->next();
/* if($fileObject->ftell() ==0)
{
	$fileObject->fgetcsv("	", "\"", "\\");
	$fileObject->next();
} */
for($count = 0; $fileObject->valid(); ++$count)
{
	$csvarr = $fileObject->fgetcsv("\t", "\"", "\\");
	$fileObject->next();
	if($csvarr === [null]) continue;
	print_r($csvarr);
	print_r($count);
//	$models[] = array_combine($_colNames, $csvarr) + [$count];
	$models[] =$csvarr;
	
}







//print_r($fileObject);


$string = array();
$fileObject->rewind();
while($fileObject->valid())
{
	$string[] = $fileObject->fgetc();
}

//print_r($string);

print_r($models);

    
 ?>