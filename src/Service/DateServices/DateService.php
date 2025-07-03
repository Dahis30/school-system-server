<?php

namespace App\Service\DateServices ;

use DateTime;
use DateTimeImmutable;

class DateService {

    // DateTimeImmutable $date1 , DateTimeImmutable $date2
    public function validerDeuxDates(DateTimeImmutable $date1 , DateTimeImmutable $date2){
        // ici on vas essayer de valider si le date2 est inferieure de la date1
        if($date2 < $date1 ) return false ;
        return true ;

    } 
    


}