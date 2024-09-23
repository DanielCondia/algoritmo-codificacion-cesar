<?php
function imprimir($mensaje, $palabra)
{
    echo $mensaje . $palabra;
}

$alfabeto = range('A', 'Z');
$palabra_codificada = "GFRGHA";
$palabra_decodificada = "DCODEX";
$desplazamiento = 3;
$opcion = 2;    // 1 = decodificar; 2 = Codificar
$codec = new Codec($palabra_codificada, $palabra_decodificada, $alfabeto, $desplazamiento);
if ($opcion === 1) {
    $mensaje = "Palabra Codificada => ";
    imprimir($mensaje, $codec->codificar()->iniciarCodificacion());
} else if ($opcion === 2) {
    $mensaje = "Palabra Decodificada => ";
    imprimir($mensaje, $codec->decodificar()->iniciarDecodificacion());
}

class Codec
{
    private $palabra_codificada, $palabra_decodificada, $alfabeto, $desplazamiento;

    public function __construct($palabra_codificada, $palabra_decodificada, $alfabeto, $desplazamiento)
    {
        $this->palabra_codificada = $palabra_codificada;
        $this->palabra_decodificada = $palabra_decodificada;
        $this->alfabeto = $alfabeto;
        $this->desplazamiento = $desplazamiento;
    }

    public function codificar()
    {
        return new class ($this->palabra_decodificada, $this->alfabeto, $this->desplazamiento) extends Codec {
            private $palabra_codificada, $alfabeto, $palabra_decodificada = "", $desplazamiento;

            public function __construct($palabra_decodificada, $alfabeto, $desplazamiento)
            {
                $this->palabra_decodificada = $palabra_decodificada;
                $this->alfabeto = $alfabeto;
                $this->desplazamiento = $desplazamiento;
            }

            public function iniciarCodificacion(): string
            {
                $letras = str_split($this->palabra_decodificada);
                $longitud_alfabeto = sizeof($this->alfabeto);
                foreach ($letras as $index => $key) {
                    $posicion_alfabeto = array_search($key, $this->alfabeto);
                    $modulo = ($posicion_alfabeto + $this->desplazamiento) % $longitud_alfabeto;
                    $this->palabra_codificada .= $this->alfabeto[$modulo];
                }
                return $this->palabra_codificada;
            }
        };
    }

    public function decodificar()
    {
        return new class ($this->palabra_codificada, $this->alfabeto, $this->desplazamiento) extends Codec {
            private $palabra_codificada, $desplazamiento, $alfabeto, $palabra_decodificada;

            public function __construct($palabra_codificada, $alfabeto, $desplazamiento)
            {
                $this->palabra_codificada = $palabra_codificada;
                $this->desplazamiento = $desplazamiento;
                $this->alfabeto = $alfabeto;
            }

            public function iniciarDecodificacion(): string
            {
                $letras = str_split($this->palabra_codificada);
                $longitud_alfabeto = sizeof($this->alfabeto);
                foreach ($letras as $index => $key) {
                    $posicion_alfabeto = array_search($key, $this->alfabeto);
                    $modulo = (abs($posicion_alfabeto - $this->desplazamiento)) % $longitud_alfabeto;
                    $this->palabra_decodificada .= $this->alfabeto[$modulo];
                }
                return $this->palabra_decodificada;
            }
        };
    }
}

