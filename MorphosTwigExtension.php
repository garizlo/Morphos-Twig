<?php
namespace morphos;

use morphos\NumeralCreation;
use morphos\Russian\CardinalNumeral;
use morphos\Russian\OrdinalNumeral;
use morphos\Russian\Plurality;

class MorphosTwigExtension extends \Twig_Extension {
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('plural', array($this, 'pluralFilter')),
            new \Twig_SimpleFilter('money', array($this, 'moneyFilter')),
            new \Twig_SimpleFilter('numeral', array($this, 'numeralFilter')),
            new \Twig_SimpleFilter('ordinal', array($this, 'ordinalFilter')),
            new \Twig_SimpleFilter('name', array($this, 'nameFilter')),
        );
    }

    public function pluralFilter($word, $count) {
        return $count.' '.Plurality::pluralize($word, $count);
    }

    public function moneyFilter($value, $currency) {
        $money_big = floor($value);
        $money_little = floor($value * 100 % 100);
        switch ($currency) {
            case '₽':
            case 'р':
            case 'рубль':
            case 'r':
            case 'rub':
                return $money_big.' '.Plurality::pluralize('рубль', $money_big).' '.$money_little.' '.Plurality::pluralize('копейка', $money_little);

            case '₴':
            case 'г':
            case 'гривна':
            case 'uah':
                return $money_big.' '.Plurality::pluralize('гривна', $money_big).' '.$money_little.' '.Plurality::pluralize('копейка', $money_little);

            case '$':
            case 'доллар':
            case 'u':
            case 'usd':
                return $money_big.' '.Plurality::pluralize('доллар', $money_big).' '.$money_little.' '.Plurality::pluralize('цент', $money_little);

            case '€':
            case 'евро':
            case 'e':
            case 'eur':
            case 'euro':
                return $money_big.' '.Plurality::pluralize('евро', $money_big).' '.$money_little.' '.Plurality::pluralize('цент', $money_little);

            case '£':
            case 'фунт':
            case 'gbp':
                return $money_big.' '.Plurality::pluralize('фунт', $money_big).' '.$money_little.' '.Plurality::pluralize('пенни', $money_little);

            default:
                return $expression[0].' '.$expression[1];
        }
    }

    public function numeralFilter($word, $count = null, $gender = NumeralCreation::MALE) {
        if ($count === null) {
            return CardinalNumeral::generate($word);
        } else if (in_array($count, array('m', 'f', 'n'))) {
            return CardinalNumeral::generate($word, $count);
        } else {
            return CardinalNumeral::generate($count, $gender).' '.Plurality::pluralize($word, $count);
        }
    }

    public function ordinalFilter($number, $gender = NumeralCreation::MALE) {
        return OrdinalNumeral::generate($number, $gender);
    }

    public function nameFilter($name, $gender = null, $case = null) {
        if ($case === null)
            return \morphos\Russian\name($name, $gender);
        else
            return \morphos\Russian\name($name, $case, $gender);
    }

    public function getName() {
        return 'morphos_twig_extension';
    }
}
