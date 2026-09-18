<?php

namespace App\Enums;

enum CashflowKeterangan: string
{
    case PEMBELIAN  = 'pembelian';
    case PEMBAYARAN = 'pembayaran';
    case PENJUALAN  = 'penjualan';
    case MODAL      = 'modal';
    case LAINNYA    = 'lainnya';

    // Label yang ramah dibaca untuk tampilan UI / Dropdown
    public function label(): string
    {
        return match ($this) {
            self::PEMBELIAN  => 'Pembelian',
            self::PEMBAYARAN => 'Pembayaran',
            self::PENJUALAN  => 'Penjualan',
            self::MODAL      => 'Modal',
            self::LAINNYA    => 'Lain-lain',
        };
    }
}