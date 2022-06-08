<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LotoController extends Controller
{
    public function index(Request $request)
    {
        $result = $this->getResult($request->jogo);

        $matriz = [
            'numero' => $result->numero,
            'sorteio' => $this->dezenas($result->listaDezenas),
            'dataAppuracao' => $result->dataApuracao,
            'listaRateioPremio' => $this->getListaRateioPremio($result->listaRateioPremio)
        ];

        echo json_encode($matriz);        
    }

    private function getResult($jogo, $concurso = NULL)
    {
        if(!empty($concurso)){
           $concurso = "/{$concurso}";
        }
   
       return json_decode(file_get_contents('https://servicebus2.caixa.gov.br/portaldeloterias/api/'.$jogo.$concurso));
    }

        
    /**
     * Retorna as dezenas prontas para inserir na base
     */
    function dezenas(array $data)
    {
       return implode(" ", $data);
        /*$result = NULL;
        $i      = 0;

        foreach($data as $value):
            $number = substr($value, 1, 2);
            $result .= $i > 0 ? " $number" : $number;
            $i++;
        endforeach;

        return $result;*/
    }

    function getListaRateioPremio(array $data)
    {
        $result = [];
        $i      = 1;

        foreach($data as $value):
            $result[] = [
                "ganhador_{$value->faixa}" => $value->numeroDeGanhadores,
                "rateio_{$value->faixa}"   => $value->valorPremio
            ];
            $i++;
        endforeach;

        return $result;
    }
}
