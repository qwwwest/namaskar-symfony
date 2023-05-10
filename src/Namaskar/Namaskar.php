<?php 

namespace App\Namaskar;

//use Psr\Log\LoggerInterface;

class Namaskar
{
    //private $mempad;
    private  static $mempads = [];
 

    public function getMempad($folder,$mempad): MemPad
    {
        
        if(! isset(self::$mempads[$mempad])){
            self::$mempads[$mempad] =  new MemPad("$folder/$mempad/$mempad.lst");
        }
         
        return self::$mempads[$mempad];
    }
}