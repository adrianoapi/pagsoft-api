<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'responsavel',
        'cpf_cnpj',
        'ie',
        'telefone',
        'telefone_com',
        'celular',
        'email',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado'
    ];
}
