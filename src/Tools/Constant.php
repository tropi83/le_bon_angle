<?php

namespace App\Tools;



class Constant
{

    /**
     * Retourne les états de publication d'un advert
     * @return array
     */
    public static function getAdvertStates(): array
    {
        return array(
            'draft' => 'En cours de validation',
            'published' => 'Publié',
            'rejected' => 'Rejecté',
        );
    }




}
