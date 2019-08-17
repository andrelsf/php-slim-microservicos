<?php

namespace App\Models\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
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
    public $user_id;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    public $nome;
    
    /**
     * @var string
     * @column(type="string", unique=true, nullable=false, length=11)
     */
    public $cpf;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    public $telefone;

    /**
     * @var string
     * @column(type="string", unique=true, nullable=false)
     */
    public $email;

    /**
     * @var string
     * @column(type="datetime", nullable=false)
     */
    public $data_nascimento;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    public $senha;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    public $rua;
    
    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    public $cidade;
    
    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    public $estado;
    
    /**
     * @var int
     * @column(type="integer", nullable=false)
     */
    public $numero;

    /**
     * @var string
     * @column(type="string", nullable=false)
     */
    public $bairro;
    
    /**
     * @var string
     * @column(type="string", nullable=true)
     */
    public $complemento;

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
        if (!$nome && !is_string($nome)) {
            throw new \InvalidArgumentException(
                "nome is required", 400
            );
        }
        $this->nome = $nome;
        return $this;
    }

    public function getCPF()
    {
        return $this->cpf;
    }

    public function setCPF($cpf)
    {
        if (!$cpf && !is_string($cpf)) {
            throw new \InvalidArgumentException(
                "CPF is required", 400
            );
        }
        $this->cpf = $cpf;
        return $this;
    }
    
    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        if (!$telefone && !is_string($telefone)) {
            throw new \InvalidArgumentException(
                "Telefone is required", 400
            );
        }
        $this->telefone = $telefone;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        if (!$email && !is_string($email)) {
            throw new \InvalidArgumentException(
                "E-mail is required", 400
            );
        }
        $this->email = $email;
        return $this;
    }

    public function getDataNascimento()
    {
        return $this->data_nascimento;
    }

    public function setDataNascimento($data_nascimento)
    {
        if (!$data_nascimento) {
            throw new \InvalidArgumentException(
                "data_nascimento is required", 400
            );
        }
        $this->data_nascimento = $data_nascimento;
        return $this;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        if (!$senha && !is_string($senha)) {
            throw new \InvalidArgumentException(
                "senha is required", 400
            );
        }
        $this->senha = $senha;
        return $this;
    }

    public function getRua()
    {
        return $this->rua;
    }

    public function setRua($rua)
    {
        if (!$rua && !is_string($rua)) {
            throw new \InvalidArgumentException(
                "rua is required", 400
            );
        }
        $this->rua = $rua;
        return $this;
    }

    public function getcidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        if (!$cidade && !is_string($cidade)) {
            throw new \InvalidArgumentException(
                "cidade is required", 400
            );
        }
        $this->cidade = $cidade;
        return $this;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        if (!$estado && !is_string($estado)) {
            throw new \InvalidArgumentException(
                "estado is required", 400
            );
        }
        $this->estado = $estado;
        return $this;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        if (!$numero && !is_int($numero)) {
            throw new \InvalidArgumentException(
                "numero is required", 400
            );
        }
        $this->numero = $numero;
        return $this;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function setBairro($bairro)
    {
        if (!$bairro && !is_string($bairro)) {
            throw new \InvalidArgumentException(
                "bairro is required", 400
            );
        }
        $this->bairro = $bairro;
        return $this;
    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setComplemento($complemento)
    {
        if (!$complemento && !is_string($complemento)) {
            throw new \InvalidArgumentException(
                "User name is required", 400
            );
        }
        $this->complemento = $complemento;
        return $this;
    }

    public function verify_field($field) {
        if (!$field && !is_string($field)) {
            throw new \InvalidArgumentException(
                "Field is required", 400
            );
        }
        return true;
    }
}
