<?php
class Quadrado {
    private $id;
    private $lado;
    private $unidade;

    public function __construct($lado, $unidade, $id = null) {
        $this->lado = $lado;
        $this->unidade = $unidade;
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getLado() {
        return $this->lado;
    }

    public function setLado($lado) {
        $this->lado = $lado;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function setUnidade($unidade) {
        $this->unidade = $unidade;
    }

    public function calcularArea() {
        return pow($this->lado, 2);
    }

    public function calcularPerimetro() {
        return 4 * $this->lado;
    }
}
?>
