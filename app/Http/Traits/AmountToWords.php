<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait AmountToWords
{
    /**
     * Convert amount to words
     *
     * @param float $amount
     * @return string
     */
    public function amountToWords(float $amount): string
    {
        $number = number_format($amount, 2, '.', '');
        $num_arr = explode('.', $number);
        $whole = $num_arr[0];
        $decimal = isset($num_arr[1]) ? $num_arr[1] : '00';

        $wholeInWords = $this->convertNumberToWords((int)$whole);
        $decimalInWords = $this->convertNumberToWords((int)$decimal);

        $dollarsText = $wholeInWords !== '' ? $wholeInWords . ' CEDIS' : '';
        $centsText = $decimalInWords !== '' ? ' AND ' . $decimalInWords . ' PESEWAS' : '';

        return ucfirst(trim($dollarsText . $centsText));
    }

    /**
     * Convert number to words
     *
     * @param int $number
     * @return string
     */
    private function convertNumberToWords(int $number): string
    {
        $ones = array(
            0 => "ZERO",
            1 => "ONE",
            2 => "TWO",
            3 => "THREE",
            4 => "FOUR",
            5 => "FIVE",
            6 => "SIX",
            7 => "SEVEN",
            8 => "EIGHT",
            9 => "NINE",
        );

        $tens = array(
            10 => 'TEN',
            11 => 'ELEVEN',
            12 => 'TWELVE',
            13 => 'THIRTEEN',
            14 => 'FOURTEEN',
            15 => 'FIFTEEN',
            16 => 'SIXTEEN',
            17 => 'SEVENTEEN',
            18 => 'EIGHTEEN',
            19 => 'NINETEEN',
            20 => 'TWENTY',
            30 => 'THIRTY',
            40 => 'FORTY',
            50 => 'FIFTY',
            60 => 'SIXTY',
            70 => 'SEVENTY',
            80 => 'EIGHTY',
            90 => 'NINETY'
        );

        $words = '';

        if ($number < 10) {
            $words = $ones[$number];
        } elseif ($number < 20) {
            $words = $tens[$number];
        } elseif ($number < 100) {
            $words = $tens[floor($number / 10) * 10];
            $remainder = $number % 10;
            if ($remainder > 0) {
                $words .= ' ' . $ones[$remainder];
            }
        } elseif ($number < 1000) {
            $words = $ones[floor($number / 100)] . ' HUNDRED';
            $remainder = $number % 100;
            if ($remainder > 0) {
                $words .= ' ' . $this->convertNumberToWords($remainder);
            }
        } elseif ($number < 1000000) {
            $words = $this->convertNumberToWords(floor($number / 1000)) . ' THOUSAND';
            $remainder = $number % 1000;
            if ($remainder > 0) {
                $words .= ' ' . $this->convertNumberToWords($remainder);
            }
        } elseif ($number < 1000000000) {
            $words = $this->convertNumberToWords(floor($number / 1000000)) . ' MILLION';
            $remainder = $number % 1000000;
            if ($remainder > 0) {
                $words .= ' ' . $this->convertNumberToWords($remainder);
            }
        }elseif ($number < 1000000000000) {
            $words = $this->convertNumberToWords(floor($number / 1000000000)) . ' BILLION';
            $remainder = $number % 1000000000;
            if ($remainder > 0) {
                $words .= ' ' . $this->convertNumberToWords($remainder);
            }
        } elseif ($number < 1000000000000000) {
            $words = $this->convertNumberToWords(floor($number / 1000000000000)) . ' TRILLION';
            $remainder = $number % 1000000000000;
            if ($remainder > 0) {
                $words .= ' ' . $this->convertNumberToWords($remainder);
            }
        } elseif ($number < 1000000000000000000) {
            $words = $this->convertNumberToWords(floor($number / 1000000000000000)) . ' QUARDRILLION';
            $remainder = $number % 1000000000000000;
            if ($remainder > 0) {
                $words .= ' ' . $this->convertNumberToWords($remainder);
            }
        }

        return $words;
    }
}

?>