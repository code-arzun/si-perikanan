<?php

namespace App\Enums;

enum TransactionUnit: string
{
    CASE PCS = 'pcs';
    CASE KG = 'kg';
    CASE GRAM = 'gram';
    CASE LITER = 'liter';
    CASE ML = 'ml';
    CASE SAK = 'sak';
    CASE KARUNG = 'karung';
    CASE EKOR = 'ekor';
    CASE BOTOL = 'botol';
    CASE PAKET = 'paket';

    public function label(): string
    {
        return match($this) {
            self::PCS => 'Pieces (pcs)',
            self::KG => 'Kilogram (kg)',
            self::GRAM => 'Gram (g)',
            self::LITER => 'Liter (L)',
            self::ML => 'Mili Liter (ml)',
            self::SAK => 'Sak',
            self::KARUNG => 'Karung',
            self::EKOR => 'Ekor',
            self::BOTOL => 'Botol',
            self::PAKET => 'Paket / Borongan',
        };
    }
}