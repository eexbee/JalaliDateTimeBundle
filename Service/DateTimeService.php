<?php

namespace Eexbee\JalaliDateTimeBundle\Service;

class DateTimeService
{
    public function date($format, $timestamp = '', $timeZone = 'Asia/Tehran', $lang = 'fa') : string
    {
        $timeSeconds = 0;

        if ($timeZone !== 'local') {
            date_default_timezone_set(($timeZone === '') ? 'Asia/Tehran' : $timeZone);
        }
        $ts = $timeSeconds + (($timestamp === '') ? time() : $this->persianEnglishNumberTransform($timestamp));
        $date = explode('_', date('H_i_j_n_O_P_s_w_Y', $ts));
        [$jalaliYear, $jalaliMonth, $jalaliDay] = $this->gregorianToJalali($date[8], $date[3], $date[2]);
        $dayOfYear =
            ($jalaliMonth < 7) ?
                (($jalaliMonth - 1) * 31) + $jalaliDay - 1 :
                (($jalaliMonth - 7) * 30) + $jalaliDay + 185;

        $isLeapYear = (((($jalaliYear % 33) % 4) - 1) === ((int)(($jalaliYear % 33) * 0.05))) ? 1 : 0;
        $formatLength = strlen($format);
        $resultOutput = '';
        for ($i = 0; $i < $formatLength; $i++) {
            $sub = substr($format, $i, 1);
            if ($sub === '\\') {
                $resultOutput .= substr($format, ++$i, 1);
                continue;
            }
            switch ($sub) {
                case 'E':
                case 'R':
                case 'x':
                case 'X':
                    $resultOutput .= '';
                    break;

                case 'B':
                case 'e':
                case 'g':
                case 'G':
                case 'h':
                case 'I':
                case 'T':
                case 'u':
                case 'Z':
                    $resultOutput .= date($sub, $ts);
                    break;

                case 'a':
                    $resultOutput .= ($date[0] < 12) ? 'ق.ظ' : 'ب.ظ';
                    break;

                case 'A':
                    $resultOutput .= ($date[0] < 12) ? 'قبل از ظهر' : 'بعد از ظهر';
                    break;

                case 'b':
                    $resultOutput .= (int) ($jalaliMonth / 3.1) + 1;
                    break;

                case 'c':
                    $resultOutput .=
                        $jalaliYear .'/'.$jalaliMonth.'/'.$jalaliDay.
                        ' ،'.$date[0].':'.$date[1].':'.$date[6].' '.$date[5];
                    break;

                case 'C':
                    $resultOutput .= (int) (($jalaliYear + 99) / 100);
                    break;

                case 'd':
                    $resultOutput .= ($jalaliDay < 10) ? '0'.$jalaliDay : $jalaliDay;
                    break;

                case 'D':
                    $resultOutput .= $this->dateInPersianAlphabet(['kh' => $date[7]], ' ');
                    break;

                case 'f':
                    $resultOutput .= $this->dateInPersianAlphabet(['ff' => $jalaliMonth], ' ');
                    break;

                case 'F':
                    $resultOutput .= $this->dateInPersianAlphabet(['mm' => $jalaliMonth], ' ');
                    break;

                case 'H':
                    $resultOutput .= $date[0];
                    break;

                case 'i':
                    $resultOutput .= $date[1];
                    break;

                case 'j':
                    $resultOutput .= $jalaliDay;
                    break;

                case 'J':
                    $resultOutput .= $this->dateInPersianAlphabet(['rr' => $jalaliDay], ' ');
                    break;

                case 'k':
                    $resultOutput .=
                        $this->persianEnglishNumberTransform(
                            100 - (int)($dayOfYear / ($isLeapYear + 365) * 1000) / 10,
                            $lang
                        );
                    break;

                case 'K':
                    $resultOutput .=
                        $this->persianEnglishNumberTransform(
                            (int) ($dayOfYear / ($isLeapYear + 365) * 1000) / 10,
                            $lang
                        );
                    break;

                case 'l':
                    $resultOutput .= $this->dateInPersianAlphabet(['rh' => $date[7]], ' ');
                    break;

                case 'L':
                    $resultOutput .= $isLeapYear;
                    break;

                case 'm':
                    $resultOutput .= ($jalaliMonth > 9) ? $jalaliMonth : '0'.$jalaliMonth;
                    break;

                case 'M':
                    $resultOutput .= $this->dateInPersianAlphabet(['km' => $jalaliMonth], ' ');
                    break;

                case 'n':
                    $resultOutput .= $jalaliMonth;
                    break;

                case 'N':
                    $resultOutput .= $date[7] + 1;
                    break;

                case 'o':
                    $jdw = ($date[7] === 6) ? 0 : $date[7] + 1;
                    $dny = 364 + $isLeapYear - $dayOfYear;
                    if ($jdw > ($dayOfYear + 3) && $dayOfYear < 3) {
                        $resultOutput .= $jalaliYear - 1;
                    } elseif ((3 - $dny) > $jdw && $dny < 3) {
                        $resultOutput .= $jalaliYear + 1;
                    } else {
                        $resultOutput .= $jalaliYear;
                    }
                    break;

                case 'O':
                    $resultOutput .= $date[4];
                    break;

                case 'p':
                    $resultOutput .= $this->dateInPersianAlphabet(['mb' => $jalaliMonth], ' ');
                    break;

                case 'P':
                    $resultOutput .= $date[5];
                    break;

                case 'q':
                    $resultOutput .= $this->dateInPersianAlphabet(['sh' => $jalaliYear], ' ');
                    break;

                case 'Q':
                    $resultOutput .= $isLeapYear + 364 - $dayOfYear;
                    break;

                case 'r':
                    $key = $this->dateInPersianAlphabet(['rh' => $date[7], 'mm' => $jalaliMonth]);
                    $resultOutput .=
                        $date[0].':'.$date[1].':'.$date[6].' '.$date[4].' '.
                        $key['rh'].'، '.$jalaliDay.' '.$key['mm'].' '.$jalaliYear;
                    break;

                case 's':
                    $resultOutput .= $date[6];
                    break;

                case 'S':
                    $resultOutput .= 'ام';
                    break;

                case 't':
                    $resultOutput .= ($jalaliMonth !== 12) ? (31 - (int) ($jalaliMonth / 6.5)) : ($isLeapYear + 29);
                    break;

                case 'U':
                    $resultOutput .= $ts;
                    break;

                case 'v':
                    $resultOutput .= $this->dateInPersianAlphabet(['ss' => ($jalaliYear % 100)], ' ');
                    break;

                case 'V':
                    $resultOutput .= $this->dateInPersianAlphabet(['ss' => $jalaliYear], ' ');
                    break;

                case 'w':
                    $resultOutput .= ($date[7] === 6) ? 0 : $date[7] + 1;
                    break;

                case 'W':
                    $avs = (($date[7] === 6) ? 0 : $date[7] + 1) - ($dayOfYear % 7);
                    if ($avs < 0) {
                        $avs += 7;
                    }
                    $num = (int) (($dayOfYear + $avs) / 7);
                    if ($avs < 4) {
                        $num++;
                    } elseif ($num < 1) {
                        $num =
                            (
                                $avs === 4 ||
                                $avs ===
                                    ((((($jalaliYear % 33) % 4) - 2) === ((int)(($jalaliYear % 33) * 0.05))) ? 5 : 4)
                            ) ? 53 : 52;
                    }
                    $aks = $avs + $isLeapYear;
                    if ($aks === 7) {
                        $aks = 0;
                    }
                    if (($isLeapYear + 363 - $dayOfYear) < $aks && $aks < 3) {
                        $resultOutput .= '01';
                    } elseif ($num < 10) {
                        $resultOutput .= '0'.$num;
                    } else {
                        $resultOutput .= $num;
                    }
                    break;

                case 'y':
                    $resultOutput .= substr($jalaliYear, 2, 2);
                    break;

                case 'Y':
                    $resultOutput .= $jalaliYear;
                    break;

                case 'z':
                    $resultOutput .= $dayOfYear;
                    break;

                default:
                    $resultOutput .= $sub;
            }
        }

        return($lang !== 'en') ? $this->persianEnglishNumberTransform($resultOutput, 'fa', '.') : $resultOutput;
    }

    /**
     * @param \DateTime $dateTime
     * @param string $implodeCharacter
     * @return array|string
     */
    public function gregorianToJalaliFromDateTimeObject(\DateTime $dateTime, $implodeCharacter = '')
    {
        return $this->gregorianToJalali(
            $dateTime->format("Y"),
            $dateTime->format("m"),
            $dateTime->format("d"),
            $implodeCharacter
        );
    }


    /**
     * @param $year
     * @param $month
     * @param $day
     * @param string $implodeCharacter
     * @return array|string
     */
    public function gregorianToJalali($year, $month, $day, $implodeCharacter = '')
    {
        [$year, $month, $day] = explode('_', $this->persianEnglishNumberTransform($year.'_'.$month.'_'.$day));
        $monthOffsetInYear = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        if ($year > 1600) {
            $jalaliYear = 979;
            $year -= 1600;
        } else {
            $jalaliYear = 0;
            $year -= 621;
        }
        $year2 = ($month > 2) ? ($year + 1) : $year;
        $days =
            (365 * $year) +
            ((int)(($year2 + 3) / 4)) -
            ((int) (($year2 + 99) / 100)) +
            ((int) (($year2 + 399) / 400)) -
            80 +
            $day +
            $monthOffsetInYear[$month - 1];
        
        $jalaliYear += 33 * ((int) ($days / 12053));
        $days %= 12053;
        $jalaliYear += 4 * ((int) ($days / 1461));
        $days %= 1461;
        $jalaliYear += (int) (($days - 1) / 365);
        if ($days > 365) {
            $days = ($days - 1) % 365;
        }
        if ($days < 186) {
            $jalaliMonth = 1 + (int) ($days / 31);
            $jalaliDay = 1 + ($days % 31);
        } else {
            $jalaliMonth = 7 + (int) (($days - 186) / 30);
            $jalaliDay = 1 + (($days - 186) % 30);
        }

        if (empty($implodeCharacter)) {
            return [$jalaliYear, $jalaliMonth, $jalaliDay];
        }
        
        return implode($implodeCharacter, [$jalaliYear, $jalaliMonth, $jalaliDay]);
    }


    /**
     * @param $year
     * @param $month
     * @param $day
     * @param string $implodeCharacter
     * @return array|string
     */
    public function jalaliToGregorian($year, $month, $day, $implodeCharacter = '')
    {
        [$year, $month, $day] = explode('_', $this->persianEnglishNumberTransform($year.'_'.$month.'_'.$day));
        if ($year > 979) {
            $gy = 1600;
            $year -= 979;
        } else {
            $gy = 621;
        }
        $days =
            (365 * $year) +
            (((int) ($year / 33)) * 8) +
            ((int) ((($year % 33) + 3) / 4)) +
            78 +
            $day +
            (($month < 7) ? ($month - 1) * 31 : (($month - 7) * 30) + 186);

        $gy += 400 * ((int) ($days / 146097));
        $days %= 146097;
        if ($days > 36524) {
            $gy += 100 * ((int) (--$days / 36524));
            $days %= 36524;
            if ($days >= 365) {
                $days++;
            }
        }
        $gy += 4 * ((int) (($days) / 1461));
        $days %= 1461;
        $gy += (int) (($days - 1) / 365);
        if ($days > 365) {
            $days = ($days - 1) % 365;
        }
        $gd = $days + 1;
        $gmArray = [
            0,
            31,
            ((($gy % 4 === 0) && ($gy % 100 !== 0)) || ($gy % 400 === 0)) ?
                29 :
                28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31
        ];
        foreach ($gmArray as $gm => $v) {
            if ($gd <= $v) {
                break;
            }
            $gd -= $v;
        }

        if (empty($implodeCharacter)) {
            return [$gy, $gm, $gd];
        }

        $gm = ($gm < 10)?"0$gm":$gm;
        $gd = ($gd < 10)?"0$gd":$gd;

        return implode($implodeCharacter, [$gy, $gm, $gd]);
    }


    public function strftime($format, $timestamp = '', $timeZone = 'Asia/Tehran', $lang = 'fa')
    {
        //TODO: Implement
    }


    /**
     * @param string $h
     * @param string $m
     * @param string $s
     * @param string $jm
     * @param string $jd
     * @param string $jy
     * @param string $timezone
     * @return false|int
     */
    public function makeTime($h = '', $m = '', $s = '', $jm = '', $jd = '', $jy = '', $timezone = 'Asia/Tehran')
    {
        if ($timezone !== 'local') {
            date_default_timezone_set($timezone);
        }
        if ($h === '') {
            return time();
        }

        [$h, $m, $s, $jm, $jd, $jy] =
            explode('_', $this->persianEnglishNumberTransform($h.'_'.$m.'_'.$s.'_'.$jm.'_'.$jd.'_'.$jy));

        if ($m === '') {
            return mktime($h);
        }

        if ($s === '') {
            return mktime($h, $m);
        }

        if ($jm === '') {
            return mktime($h, $m, $s);
        }

        $jdate = explode('_', $this->date('Y_j', '', '', $timezone, 'en'));
        if ($jd === '') {
            [$gy, $gm, $gd] = $this->jalaliToGregorian($jdate[0], $jm, $jdate[1]);
            return mktime($h, $m, $s, $gm);
        }

        if ($jy === '') {
            [$gy, $gm, $gd] = $this->jalaliToGregorian($jdate[0], $jm, $jd);
            return mktime($h, $m, $s, $gm, $gd);
        }

        [$gy, $gm, $gd] = $this->jalaliToGregorian($jy, $jm, $jd);
        return mktime($h, $m, $s, $gm, $gd, $gy);
    }


    /**
     * @param string $timestamp
     * @param string $timezone
     * @param string $lang
     * @return array
     */
    public function getDate($timestamp = '', $timezone = 'Asia/Tehran', $lang = 'en')
    {
        $ts = ($timestamp === '') ? time() : $this->persianEnglishNumberTransform($timestamp);
        $date = explode('_', $this->date('F_G_i_j_l_n_s_w_Y_z', $ts, $timezone, $lang));

        return [
            'seconds'=> $this->persianEnglishNumberTransform(
                (int)$this->persianEnglishNumberTransform($date[6]),
                $lang
            ),
            'minutes'=> $this->persianEnglishNumberTransform(
                (int)$this->persianEnglishNumberTransform($date[2]),
                $lang
            ),
            'hours'  => $date[1],
            'mday'   => $date[3],
            'wday'   => $date[7],
            'mon'    => $date[5],
            'year'   => $date[8],
            'yday'   => $date[9],
            'weekday'=> $date[4],
            'month'  => $date[0],
            0        => $this->persianEnglishNumberTransform($ts, $lang),
        ];
    }


    /**
     * @param $month
     * @param $day
     * @param $year
     * @return bool
     */
    public function checkDate($month, $day, $year) : bool
    {
        [$month, $day, $year] = explode('_', $this->persianEnglishNumberTransform($month.'_'.$day.'_'.$year));
        if ($month === 12) {
            if (((($year % 33) % 4) - 1) === ((int)(($year % 33) * 0.05))) {
                $lDay = 30;
            } else {
                $lDay = 29;
            }
        } else {
            $lDay = 31 - (int)($month / 6.5);
        }

        return !($month > 12 || $day > $lDay || $month < 1 || $day < 1 || $year < 1);
    }
    

    /**
     * @param $array
     * @param string $implodeCharacter
     * @return string
     */
    private function dateInPersianAlphabet($array, $implodeCharacter = '') : string
    {
        foreach ($array as $type => $num) {
            $num = (int)$this->persianEnglishNumberTransform($num);
            switch ($type) {
                case 'ss':
                    $sl = strlen($num);
                    $xy3 = substr($num, 2 - $sl, 1);
                    $h3 = $h34 = $h4 = '';
                    if ($xy3 === 1) {
                        $p34 = '';
                        $k34 =
                            ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده'];
                        $h34 = $k34[substr($num, 2 - $sl, 2) - 10];
                    } else {
                        $xy4 = substr($num, 3 - $sl, 1);
                        $p34 = ($xy3 === 0 or $xy4 === 0) ? '' : ' و ';
                        $k3 = ['', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];
                        $h3 = $k3[$xy3];
                        $k4 = ['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
                        $h4 = $k4[$xy4];
                    }
                    $array[$type] = (($num > 99) ? str_replace(['12', '13', '14', '19', '20'], ['هزار و دویست', 'هزار و سیصد', 'هزار و چهارصد', 'هزار و نهصد', 'دوهزار'], substr($num, 0, 2)).((substr($num, 2, 2) == '00') ? '' : ' و ') : '').$h3.$p34.$h34.$h4;
                    break;

                case 'mm':
                    $key = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'rr':
                    $key = ['یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه', 'ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده', 'بیست', 'بیست و یک', 'بیست و دو', 'بیست و سه', 'بیست و چهار', 'بیست و پنج', 'بیست و شش', 'بیست و هفت', 'بیست و هشت', 'بیست و نه', 'سی', 'سی و یک'];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'rh':
                    $key = ['یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه'];
                    $array[$type] = $key[$num];
                    break;

                case 'sh':
                    $key = ['مار', 'اسب', 'گوسفند', 'میمون', 'مرغ', 'سگ', 'خوک', 'موش', 'گاو', 'پلنگ', 'خرگوش', 'نهنگ'];
                    $array[$type] = $key[$num % 12];
                    break;

                case 'mb':
                    $key = ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'ff':
                    $key = ['بهار', 'تابستان', 'پاییز', 'زمستان'];
                    $array[$type] = $key[(int) ($num / 3.1)];
                    break;

                case 'km':
                    $key = ['فر', 'ار', 'خر', 'تی‍', 'مر', 'شه‍', 'مه‍', 'آب‍', 'آذ', 'دی', 'به‍', 'اس‍'];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'kh':
                    $key = ['ی', 'د', 'س', 'چ', 'پ', 'ج', 'ش'];
                    $array[$type] = $key[$num];
                    break;

                default:
                    $array[$type] = $num;
            }
        }

        if (empty($implodeCharacter)) {
            return $array;
        }
        
        return implode($implodeCharacter, $array);
    }
    
    
    /**
     * @param string $string
     * @param string $language
     * @param string $separator
     * @return mixed
     */
    private function persianEnglishNumberTransform($string, $language = 'en', $separator = '٫')
    {
        $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $separator];

        if ($language === 'fa') {
            return str_replace($englishNumbers, $persianNumbers, $string);
        }
        
        return str_replace($persianNumbers, $englishNumbers, $string);
    }
}
