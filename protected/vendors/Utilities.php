<?php

class Utilities {

    public static function MoneyFormat($value, $decimals = 2) {
        $value = '$ ' . number_format($value, $decimals, ",", ".");
        return $value;
    }

    public static function Unformat($value) {
        if (strstr($value, '$ '))
            $value = str_replace('$ ', '', $value);
        if (strstr($value, '.'))
            $value = str_replace('.', '', $value);
        if (strstr($value, ','))
            $value = str_replace(',', '.', $value);
        return $value;
    }

    /**
     * [MysqlDateFormat Convierte una fecha d-m-Y a Y-m-d]
     * @param [type] $date [description]
     * @return $date [Y-m-d]
     */
    public static function MysqlDateFormat($date) {
        $dia = substr($date, 0, 2);
        $mes = substr($date, 3, 2);
        $anio = substr($date, 6, 4);
        return $anio . '-' . $mes . '-' . $dia;
    }

    /**
     * [ViewDateFormat Convierte una fecha Y-m-d a d/m/Y]
     * @param [type] $date [description]
     * @return $date [d-m-Y]
     */
    public static function ViewDateFormat($date) {
        $dia = substr($date, 8, 2);
        $mes = substr($date, 5, 2);
        $anio = substr($date, 0, 4);
        return $dia . '/' . $mes . '/' . $anio;
    }

     public static function ResumeString($str,$caracteres) {
        if (strlen($str)>$caracteres){
            $str = substr($str,0,$caracteres)."...";
        }
        return $str;
    }

    /**
     * [RestarFechas Devuelve la resta entre 2 fechas formato d-m-Y]
     * @param [type] $fechaInicio [formato d-m-Y]
     * @param [type] $fechaFin    [formato d-m-Y]
     */
    public static function RestarFechas($fechaInicio, $fechaFin) {
        $fechaInicio = str_replace("-", "", $fechaInicio);
        $fechaInicio = str_replace("/", "", $fechaInicio);
        $fechaFin = str_replace("-", "", $fechaFin);
        $fechaFin = str_replace("/", "", $fechaFin);

        preg_match("/([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})/", $fechaInicio, $arrayFechaInicio);
        preg_match("/([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})/", $fechaFin, $arrayFechaFin);

        $date1 = mktime(0, 0, 0, $arrayFechaInicio[2], $arrayFechaInicio[1], $arrayFechaInicio[3]);
        $date2 = mktime(0, 0, 0, $arrayFechaFin[2], $arrayFechaFin[1], $arrayFechaFin[3]);

        $dias = round(($date2 - $date1) / (60 * 60 * 24));
        return $dias;
    }

    public static function RestarFechas2($fechaInicio, $fechaFin){
        $fechaInicio = new DateTime($fechaInicio);
        $fechaFin = new DateTime($fechaFin);
        $intervalo = $fechaInicio->diff($fechaFin);
        return (int)$intervalo->days;
    }

    public static function RestarFechas3($fechaInicio, $fechaFin){

        $fechaInicio=str_replace('/', '-', $fechaInicio);
        $fechaInicio = strtotime($fechaInicio);
        
        $fechaFin=str_replace('/', '-', $fechaFin);
        $fechaFin = strtotime($fechaFin);
        $diff = $fechaFin - $fechaInicio; 
        return round($diff / 86400); 
    }

    public static function truncateFloat($number, $digitos) {
        $raiz = 10;
        $multiplicador = pow($raiz, $digitos);
        $resultado = ((int) ($number * $multiplicador)) / $multiplicador;
        return $resultado;
        //return number_format($resultado, $digitos);
    }

    function redondear_dos_decimal($valor) { 
           
            $multiplicado=$valor * 100;
           
             $float_redondeado=round($multiplicado / 100);

           
            return $float_redondeado; 
        } 
    /* !
      @function num2letras ()
      @abstract Dado un n?mero lo devuelve escrito.
      @param $num number - N?mero a convertir.
      @param $fem bool - Forma femenina (true) o no (false).
      @param $dec bool - Con decimales (true) o no (false).
      @result string - Devuelve el n?mero escrito en letra.

     */

    public static function num2letras($num, $fem = false, $dec = true, $son_decimales=false) {
        $matuni[2] = "dos";
        $matuni[3] = "tres";
        $matuni[4] = "cuatro";
        $matuni[5] = "cinco";
        $matuni[6] = "seis";
        $matuni[7] = "siete";
        $matuni[8] = "ocho";
        $matuni[9] = "nueve";
        $matuni[10] = "diez";
        $matuni[11] = "once";
        $matuni[12] = "doce";
        $matuni[13] = "trece";
        $matuni[14] = "catorce";
        $matuni[15] = "quince";
        $matuni[16] = "dieciseis";
        $matuni[17] = "diecisiete";
        $matuni[18] = "dieciocho";
        $matuni[19] = "diecinueve";
        $matuni[20] = "veinte";
        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "cuatro";
        $matunisub[5] = "quin";
        $matunisub[6] = "seis";
        $matunisub[7] = "sete";
        $matunisub[8] = "ocho";
        $matunisub[9] = "nove";

        $matdec[2] = "veint";
        $matdec[3] = "treinta";
        $matdec[4] = "cuarenta";
        $matdec[5] = "cincuenta";
        $matdec[6] = "sesenta";
        $matdec[7] = "setenta";
        $matdec[8] = "ochenta";
        $matdec[9] = "noventa";
        $matsub[3] = 'mill';
        $matsub[5] = 'bill';
        $matsub[7] = 'mill';
        $matsub[9] = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4] = 'millones';
        $matmil[6] = 'billones';
        $matmil[7] = 'de billones';
        $matmil[8] = 'millones de billones';
        $matmil[10] = 'trillones';
        $matmil[11] = 'de trillones';
        $matmil[12] = 'millones de trillones';
        $matmil[13] = 'de trillones';
        $matmil[14] = 'billones de trillones';
        $matmil[15] = 'de billones de trillones';
        $matmil[16] = 'millones de billones de trillones';

        //Zi hack
        $float = explode('.', $num);
        $num = $float[0];

        $num = trim((string) @$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        }else
            $neg = '';
        while ($num[0] == '0')
            $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9)
            $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (!(strpos(".,'''", $n) === false)) {
                if ($punt)
                    break;
                else {
                    $punt = true;
                    continue;
                }
            } elseif (!(strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0')
                        $zeros = false;
                    $fra .= $n;
                }else
                    $ent .= $n;
            }else
                break;
        }
        $ent = '     ' . $ent;
        if ($dec and $fra and !$zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' cero';
                elseif ($s == '1')
                    $fin .= $fem ? ' una' : ' un';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        }else
            $fin = '';
        if ((int) $ent === 0)
            return 'Cero ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while (($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'una';
                $subcent = 'as';
            } else {
                $matuni[1] = $neutro ? 'un' : 'uno';
                $subcent = 'os';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {

            } elseif ($n2 < 21)
                $t = ' ' . $matuni[(int) $n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = 'i' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }else {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = ' y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' ciento' . $t;
            } elseif ($n == 5) {
                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
            } elseif ($n != 0) {
                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
            }
            if ($sub == 1) {

            } elseif (!isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                } elseif ($num > 1) {
                    $t .= ' mil';
                }
            } elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . '?n';
            } elseif ($num > 1) {
                $t .= ' ' . $matsub[$sub] . 'ones';
            }
            if ($num == '000')
                $mils++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub]))
                    $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        //Zi hack --> return ucfirst($tex);

        if(isset($float[1])){
            $num=substr($float[1], 0, 2);
            if($num!="00")
                $decimales=self::num2letras($num,false,true,true);
            else
                $decimales="";
        } else
            $decimales="";

        if($son_decimales)
            $end_num =' con ' . $tex . ' centavos';
        else
            $end_num = ucfirst($tex) . $decimales;
        return $end_num;
    }

// END FUNCTION
}