<?php

namespace GEAPIUsers\UserEntity;

class UserEntity
{
    private $nome;
    private $cpf;
    private $telefone;
    private $email;
    private $data_nascimento;
    private $senha;
    private $rua;
    private $cidade;
    private $estado;
    private $numero;
    private $bairro;
    private $complemento;

    public function __construct(
        $nome, $cpf, $telefone, $email, $data_nascimento,
        $senha, $rua, $cidade, $estado, $numero, $bairro, $complemento
    )
    {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->data_nascimento = $data_nascimento;
        $this->senha = $senha;
        $this->rua = $rua;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->complemento = $complemento;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getBairro()
    {
        return $this->cpf;
    }

    public function setCPF($cpf)
    {
        $this->cpf = $cpf;
    }
    
}

