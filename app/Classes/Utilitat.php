<?php
namespace App\Classes;

class Utilitat
{
    public static function errorMessage($e)
    {
        if (! empty($e->errorInfo[1])) {
            switch ($e->errorInfo[1]) {
                case 2601:
                    $missatge = 'Registre duplicat';
                    break;
                case 547:
                    $missatge = 'Registre amb elements relacionats';
                    break;
                case 500:
                    $missatge = 'Error al crear el cliente';
                default:
                    $missatge = $e->errorInfo[1] . ' - ' . $e->errorInfo[2];
                    break;
            }
        } else {
            switch ($e->getCode()) {
                case 1044:
                    $missatge = 'Usuari o contrasenya incorrectos';
                    break;
                case 1049:
                    $missatge = 'Base de dades desconeguda';
                    break;
                case 2002:
                    $missatge = 'No es troba el servidor';
                    break;
                default:
                    $missatge = $e->getCode() . ' - ' . $e->getMessage();
                    break;
            }
        }
        return $missatge;

    }
}
