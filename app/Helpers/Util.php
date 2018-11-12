<?php

namespace App\Helpers;

use Carbon;

class Util
{
	public static function sanitize_word($string)
	{
		$string = trim($string);

		$string = str_replace(
			array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
			$string
		);

		$string = str_replace(
			array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
			array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
			$string
		);

		$string = str_replace(
			array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
			array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
			$string
		);

		$string = str_replace(
			array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
			array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
			$string
		);

		$string = str_replace(
			array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
			array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
			$string
		);

		$string = str_replace(
			array('ñ', 'Ñ', 'ç', 'Ç'),
			array('n', 'N', 'c', 'C', ),
			$string
		);

		$string = str_replace(
			array(
				"\\", "¨", "º", "-", "~",
				"#", "@", "|", "!", "\"",
				"·", "$", "%", "&", "/",
				"(", ") ", " ? ", " '", "¡",
				"¿", "[", "^", "<code>", "]",
				"+", "}", "{", "¨", "´",
				">", "< ", ";", ",", ":",
				".", " "
			),
			' ',
			$string
		);

		return $string;
	}

	public static function formatMoney($value)
	{
		$value = number_format(floatval($value), 2, '.', ',');
		return $value;
	}

	public static function removeSpaces($text)
	{
		$re = ' / \s + / ';
		$subst = ' ';
		$result = preg_replace($re, $subst, $text);
		return $result ? trim($result) : null;
	}

	public static function fullName($object, $style = "uppercase", $order = "name_first")
	{
		$name = null;
		if ($order == ' lastname_first ') {
			switch ($style) {
				case 'uppercase':
					$name = mb_strtoupper($object->last_name ?? ' ') . ' ' . mb_strtoupper($object->mothers_last_name ?? ' ') . ' ' . mb_strtoupper($object->surname_husband ?? ' ') . ' ' . mb_strtoupper($object->first_name ?? ' ') . ' ' . mb_strtoupper($object->second_name ?? ' ');
					break;
				case 'lowercase':
					$name = mb_strtolower($object->last_name ?? ' ') . ' ' . mb_strtolower($object->mothers_last_name ?? ' ') . ' ' . mb_strtolower($object->surname_husband ?? ' ') . ' ' . mb_strtolower($object->first_name ?? ' ') . ' ' . mb_strtolower($object->second_name ?? ' ');
					break;
				case 'capitalize':
					$name = ucfirst(mb_strtolower($object->last_name ?? ' ')) . ' ' . ucfirst(mb_strtolower($object->mothers_last_name ?? ' ')) . ' ' . ucfirst(mb_strtolower($object->surname_husband ?? ' ')) . ' ' . ucfirst(mb_strtolower($object->first_name ?? ' ')) . ' ' . ucfirst(mb_strtolower($object->second_name ?? ' '));
					break;
			}
		} else {
			switch ($style) {
				case 'uppercase':
					$name = mb_strtoupper($object->first_name ?? ' ') . ' ' . mb_strtoupper($object->second_name ?? ' ') . ' ' . mb_strtoupper($object->last_name ?? ' ') . ' ' . mb_strtoupper($object->mothers_last_name ?? ' ') . ' ' . mb_strtoupper($object->surname_husband ?? ' ');
					break;
				case 'lowercase':
					$name = mb_strtolower($object->first_name ?? ' ') . ' ' . mb_strtolower($object->second_name ?? ' ') . ' ' . mb_strtolower($object->last_name ?? ' ') . ' ' . mb_strtolower($object->mothers_last_name ?? ' ') . ' ' . mb_strtolower($object->surname_husband ?? ' ');
					break;
				case 'capitalize':
					$name = ucfirst(mb_strtolower($object->first_name ?? ' ')) . ' ' . ucfirst(mb_strtolower($object->second_name ?? ' ')) . ' ' . ucfirst(mb_strtolower($object->last_name ?? ' ')) . ' ' . ucfirst(mb_strtolower($object->mothers_last_name ?? ' ')) . ' ' . ucfirst(mb_strtolower($object->surname_husband ?? ' '));
					break;
			}
		}
		$name = self::removeSpaces($name);
		return $name;
	}

	public static function ciExt($object)
	{
		$result = $object->identity_card . " " . ($object->city_identity_card->shortened ?? ' ');
		return self::removeSpaces($result);
	}

	public static function fillZerosLeft($value, $length = 8)
	{
		if ($value) {
			return str_pad($value, $length, "0", STR_PAD_LEFT);
		}
		return str_pad(0, $length, "0", STR_PAD_LEFT);
	}

	public static function getCivilStatus($est, $gender)
	{
		switch ($est) {
			case ' S ':
				return $gender ? ($gender == ' M ' ? ' SOLTERO ' : ' SOLTERA ') : ' SOLTERO(A) ';
				break;
			case ' D ':
				return $gender ? ($gender == ' M ' ? ' DIVORCIADO ' : ' DIVORCIADA ') : ' DIVORCIADO(A) ';
				break;
			case ' C ':
				return $gender ? ($gender == ' M ' ? ' CASADO ' : ' CASADA ') : ' CASADO(A) ';
				break;
			case ' V ':
				return $gender ? ($gender == ' M ' ? ' VIUDO ' : ' VIUDA ') : ' VIUDO(A) ';
				break;
			case ' S ':
				return $gender ? ($gender == ' M ' ? ' SOLTERO ' : ' SOLTERA ') : ' SOLTERO(A) ';
				break;
			default:
				return ' ';
				break;
		}
	}

	public static function getMonthEs($value)
	{
		$meses = [' ', ' Enero ', ' Febrero ', ' Marzo ', ' Abril ', ' Mayo ', ' Junio ', ' Julio ', ' Agosto ', ' Septiembre ', ' Octubre ', ' Noviembre ', ' Diciembre '];
		return $meses[(int)$value];
	}

	

	public static function getDate($date, $format = ' d / m / Y ')
	{
		return Carbon::parse($date)->format(' d / m / Y ');
	}

	public static function array_group_by(array $array, $key)
	{
		if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
			trigger_error(' array_group_by() : The key should be a string, an integer, or a callback ', E_USER_ERROR);
			return null;
		}
		$func = (!is_string($key) && is_callable($key) ? $key : null);
		$_key = $key;
		// Load the new array, splitting by the target key
		$grouped = [];
		foreach ($array as $value) {
			$key = null;
			if (is_callable($func)) {
				$key = call_user_func($func, $value);
			} elseif (is_object($value) && isset($value->{$_key})) {
				$key = $value->{$_key};
			} elseif (isset($value[$_key])) {
				$key = $value[$_key];
			}
			if ($key === null) {
				continue;
			}
			$grouped[$key][] = $value;
		}
		// Recursively build a nested grouping if more parameters are supplied
		// Each grouped array value is grouped according to the next sequential key
		if (func_num_args() > 2) {
			$args = func_get_args();
			foreach ($grouped as $key => $value) {
				$params = array_merge([$value], array_slice($args, 2, func_num_args()));
				$grouped[$key] = call_user_func_array(' array_group_by ', $params);
			}
		}
		return $grouped;
	}

	public static function format_number($number, $decimals = 2, $thousand_separator = ',', $decimal_separator = '.')
	{
		return number_format(round($number, $decimals), $decimals, $decimal_separator, $thousand_separator);
	}

	public static function get_percentage($number, $percent)
	{
		return ($number * $percent / 100);
	}

	

<<<<<<< HEAD
=======
	private static $CENTENAS = [
		' CIENTO ',
		' DOSCIENTOS ',
		' TRESCIENTOS ',
		' CUATROCIENTOS ',
		' QUINIENTOS ',
		' SEISCIENTOS ',
		' SETECIENTOS ',
		' OCHOCIENTOS ',
		' NOVECIENTOS ',
	];

	public static function convertir($number, $moneda = ' ', $centimos = ' ')
	{
		$converted = ' ';
		$decimales = ' ';
		if (($number < 0) || ($number > 999999999)) {
			return ' No es posible convertir el numero a letras ';
		}
		$div_decimales = explode('.', $number);
		if (count($div_decimales) > 1) {
			$number = $div_decimales[0];
			$decNumberStr = (string)$div_decimales[1];
			if (strlen($decNumberStr) == 2) {
				$decNumberStrFill = str_pad($decNumberStr, 9, ' 0 ', STR_PAD_LEFT);
				$decCientos = substr($decNumberStrFill, 6);
				$decimales = self::convertGroup($decCientos);
			}
		}
		$numberStr = (string)$number;
		$numberStrFill = str_pad($numberStr, 9, ' 0 ', STR_PAD_LEFT);
		$millones = substr($numberStrFill, 0, 3);
		$miles = substr($numberStrFill, 3, 3);
		$cientos = substr($numberStrFill, 6);
		if (intval($millones) > 0) {
			if ($millones == ' 001 ') {
				$converted .= ' UN MILLON ';
			} else if (intval($millones) > 0) {
				$converted .= sprintf(' % sMILLONES ', self::convertGroup($millones));
			}
		}
		if (intval($miles) > 0) {
			if ($miles == ' 001 ') {
				$converted .= ' MIL ';
			} else if (intval($miles) > 0) {
				$converted .= sprintf(' % sMIL ', self::convertGroup($miles));
			}
		}
		if (intval($cientos) > 0) {
			if ($cientos == ' 001 ') {
				$converted .= ' UN ';
			} else if (intval($cientos) > 0) {
				$converted .= sprintf(' % s ', self::convertGroup($cientos));
			}
		}
		if (empty($decimales)) {
			// $valor_convertido = $converted . strtoupper($moneda);
			$valor_convertido = $converted . ' 00 / 100 ';
		} else {
			$valor_convertido = $converted . strtoupper($moneda) . ($div_decimales[1]) . ' / 100 ';
			// $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . '' . strtoupper($centimos);
		}
		return $valor_convertido;
	}
>>>>>>> c88936e2ca04e9b12009a67413647d6a729e6248

	public static function end_of_month($year, $month)
	{
		return Carbon::create($year, $month)->endOfMonth()->setTime(0, 0, 0);
	}

	public static function compare_dates($contract_date, $payroll)
	{
		$end_of_month = self::end_of_month($payroll->procedure->year, $payroll->procedure->month->order);

		if ($end_of_month->day < 30) {
			return Carbon::parse($contract_date)->setTime(0, 0, 0)->gte(Carbon::create($payroll->procedure->year, $payroll->procedure->month->order)->endOfMonth()->setTime(0, 0, 0));
		} else {
			return Carbon::parse($contract_date)->setTime(0, 0, 0)->gte(Carbon::create($payroll->procedure->year, $payroll->procedure->month->order, 30)->setTime(0, 0, 0));
		}
	}

	public static function valid_contract($payroll, $contract)
	{
		if (!$contract) {
			$contract = $payroll->contract;
		}
		$end_of_month = self::end_of_month($payroll->procedure->year, $payroll->procedure->month->order);

		if (Carbon::parse($contract->start_date)->setTime(0, 0, 0)->lte($end_of_month)) {
			if (is_null($contract->end_date) && is_null($contract->retirement_date)) {
				return true;
			} elseif (!is_null($contract->retirement_date)) {
				return self::compare_dates($contract->retirement_date, $payroll);
			} else {
				return self::compare_dates($contract->end_date, $payroll);
			}
		} else {
			return false;
		}
	}
	private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];
    private static $DECENAS = [
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];
    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];
    public static function convertir($number, $moneda = '', $centimos = '')
    {
        $converted = '';
        $decimales = '';
        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        $div_decimales = explode('.',$number);
        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }
        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'UN MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }
        if(empty($decimales)){
            // $valor_convertido = $converted . strtoupper($moneda);
            $valor_convertido = $converted . '00/100';
        } else {
            $valor_convertido = $converted . strtoupper($moneda)  . ($div_decimales[1]) . '/100';
            // $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
        }
        return $valor_convertido;
    }
    private static function convertGroup($n)
    {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n,1));
        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }
}