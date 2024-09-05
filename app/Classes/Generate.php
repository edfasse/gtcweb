<?php

namespace App\classes;

class Generate{
    public static function generateID($pre, $rand = [], $rand2 = []) {
    
        return $pre . rand($rand['min'],$rand['max']) . rand($rand2['min'],$rand2['max']); 
        // Limita o tamanho, se necessário
    }
}