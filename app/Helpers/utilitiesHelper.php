<?php

   function download($titles,$array, $filename , $delimiter="|")
   {
       
       $headers = array(
         "Content-Type: application/csv",  
         //'Content-Disposition: attachment; filename="'.$filename.'.txt";'
       );
     //dd(storage_path('app/public'));
       $file = storage_path('app')."/".$filename.date("Ymdhis").'.csv';
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen( $file , 'w'); 
   // $f = fopen('php://memory', 'w'); 
    // loop over the input array
   // dd($titles);
    foreach ($titles as $line1) { 
        // generate csv lines from the inner arrays
      //  fputcsv($f, $line1, $delimiter,chr(0)); 
        fwrite($f, implode($delimiter, array_map("utf8_decode", $line1)) . "\r\n");
    }
    
    
    foreach ($array as $line) { 
        // generate csv lines from the inner arrays
        fwrite($f, implode($delimiter, array_map("utf8_decode", $line)) . "\r\n");
    }
    

    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
   // header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
   // header('Content-Disposition: attachment; filename="'.$filename.'.txt";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
    fclose($f);
    
     return Response::download($file, $filename.'.csv', $headers)->deleteFileAfterSend(true);
   }

   
   function utf8_converter($array){
       
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
            $item = utf8_encode($item);
        }
    }); 
    return $array;
}
