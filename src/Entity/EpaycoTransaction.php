<?php

namespace App\Entity;

use App\Repository\EpaycoTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpaycoTransactionRepository::class)
 */
class EpaycoTransaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $x_cust_id_cliente;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $x_ref_payco;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $x_id_factura;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $x_id_invoice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $x_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $x_amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $x_respuesta;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $x_fecha_transaccion;

    /**
     * EpaycoTransaction constructor.
     * @param $x_cust_id_cliente
     * @param $x_ref_payco
     * @param $x_id_factura
     * @param $x_id_invoice
     * @param $x_description
     * @param $x_amount
     * @param $x_respuesta
     * @param $x_fecha_transaccion
     */
    public function __construct($x_cust_id_cliente, $x_ref_payco, $x_id_factura, $x_id_invoice, $x_description, $x_amount, $x_respuesta, $x_fecha_transaccion)
    {
        $this->x_cust_id_cliente = $x_cust_id_cliente;
        $this->x_ref_payco = $x_ref_payco;
        $this->x_id_factura = $x_id_factura;
        $this->x_id_invoice = $x_id_invoice;
        $this->x_description = $x_description;
        $this->x_amount = $x_amount;
        $this->x_respuesta = $x_respuesta;
        $this->x_fecha_transaccion = $x_fecha_transaccion;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getXCustIdCliente(): ?string
    {
        return $this->x_cust_id_cliente;
    }

    public function setXCustIdCliente(string $x_cust_id_cliente): self
    {
        $this->x_cust_id_cliente = $x_cust_id_cliente;

        return $this;
    }

    public function getXRefPayco(): ?string
    {
        return $this->x_ref_payco;
    }

    public function setXRefPayco(string $x_ref_payco): self
    {
        $this->x_ref_payco = $x_ref_payco;

        return $this;
    }

    public function getXIdFactura(): ?string
    {
        return $this->x_id_factura;
    }

    public function setXIdFactura(string $x_id_factura): self
    {
        $this->x_id_factura = $x_id_factura;

        return $this;
    }

    public function getXIdInvoice(): ?string
    {
        return $this->x_id_invoice;
    }

    public function setXIdInvoice(string $x_id_invoice): self
    {
        $this->x_id_invoice = $x_id_invoice;

        return $this;
    }

    public function getXDescription(): ?string
    {
        return $this->x_description;
    }

    public function setXDescription(string $x_description): self
    {
        $this->x_description = $x_description;

        return $this;
    }

    public function getXAmount(): ?string
    {
        return $this->x_amount;
    }

    public function setXAmount(string $x_amount): self
    {
        $this->x_amount = $x_amount;

        return $this;
    }

    public function getXRespuesta(): ?string
    {
        return $this->x_respuesta;
    }

    public function setXRespuesta(string $x_respuesta): self
    {
        $this->x_respuesta = $x_respuesta;

        return $this;
    }

    public function getXFechaTransaccion(): ?string
    {
        return $this->x_fecha_transaccion;
    }

    public function setXFechaTransaccion(string $x_fecha_transaccion): self
    {
        $this->x_fecha_transaccion = $x_fecha_transaccion;

        return $this;
    }
}
