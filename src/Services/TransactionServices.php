<?php

namespace App\Services;

 class TransactionServices
{
    public function GenerateCode($idTrans)
    {
        $code ='';
        for ($i=1;$i<=3;$i++)
        {
            $code= $code.''.$this->RandomNumber();
            if ($i<3)
            {
                $code.='-';
            }
            
        }
        $code[0]=$idTrans;
        return $code;
    }

    public function RandomNumber()
    {
        return rand(100,999);
    }

    public function calculeFraisTotal($montant)
    {
        
        switch ($montant)
        {
            case ($montant<=5000): return 425;break;
            case ($montant<=10000): return 850;break;
            case ($montant<=15000): return 1270;break;
            case ($montant<=20000): return 1695;break;
            case ($montant<=50000): return 2500;break;
            case ($montant<=60000): return 3000;break;
            case ($montant<=75000): return 4000;break;
            case ($montant<=120000): return 5000;break;
            case ($montant<=150000): return 6000;break;
            case ($montant<=200000): return 7000;break;
            case ($montant<=250000): return 8000;break;
            case ($montant<=300000): return 9000;break;
            case ($montant<=400000): return 12000;break;
            case ($montant<=750000): return 15000;break;
            case ($montant<=900000): return 22000;break;
            case ($montant<=1000000): return 25000;break;
            case ($montant<=1125000): return 25000;break;
            case ($montant<=14000000): return 27000;break;
            case ($montant<=20000000): return 30000;break;
            case ($montant>20000000): return ($montant*2)/100;break;   
        }
    }
}

