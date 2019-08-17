<?php

namespace App\Models\Entity;

/**
 * @Entity @table(name="users")
 */
class UserEntity
{
    /**
     * @var int
     * @Id @column(type="integer")
     * @GeneratedValue
     */
    protected $user_id;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    protected $nome;
    
    /**
     * @var int
     * @column(type="integer", unique=true, nullable=false, length=12)
     */
    protected $cpf;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    protected $telefone;

    /**
     * @var string
     * @column(type="string", unique=true, nullable=false)
     */
    protected $email;

    /**
     * @var string
     * @column(type="datetime", nullable=false)
     */
    protected $data_nascimento;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    protected $senha;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    protected $rua;
    
    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    protected $cidade;
    
    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    protected $estado;
    
    /**
     * @var int
     * @column(type="integer", nullable=false)
     */
    protected $numero;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    protected $bairro;
    
    /**
     * @var string
     * @column(type="string", nullable=true)
     */
    protected $complemento;

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getCPF()
    {
        return $this->cpf;
    }

    public function setCPF($cpf)
    {
        $this->cpf = $cpf;
    }
    
    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getDataNascimento()
    {
        return $this->data_nascimento;
    }

    public function setDataNascimento($data_nascimento)
    {
        $this->data_nascimento = $data_nascimento;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getRua()
    {
        return $this->rua;
    }

    public function setRua($rua)
    {
        $this->rua = $rua;
    }

    public function getcidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }
}
