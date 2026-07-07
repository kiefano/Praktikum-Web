<?php

if (!function_exists('hitung_ppn')) {
    /**
     * Menghitung PPN 11% dari total harga pembelian (tidak termasuk ongkir).
     *
     * @param int|float $total_harga
     * @return int
     */
    function hitung_ppn($total_harga): int
    {
        return (int) ($total_harga * 0.11);
    }
}

if (!function_exists('hitung_biaya_admin')) {
    /**
     * Menghitung biaya admin berdasarkan total harga pembelian.
     *
     * - <= Rp 20.000.000 : 0.6%
     * - > Rp 20.000.000  : 0.7%
     *
     * @param int|float $total_harga
     * @return int
     */
    function hitung_biaya_admin($total_harga): int
    {
        if ($total_harga <= 20_000_000) {
            $tarif = 0.006;
        } else {
            $tarif = 0.007;
        }

        return (int) ($total_harga * $tarif);
    }
}

if (!function_exists('hitung_diskon_kupon')) {
    /**
     * Menghitung diskon berdasarkan kode kupon.
     * Diskon dihitung dari total harga pembelian (sebelum PPN dan biaya admin).
     *
     * Kode kupon yang valid:
     * - FLASH10  : 10%
     * - FLASH15  : 15%
     * - MEMBER20 : 20%
     *
     * @param int|float $total_harga
     * @param string    $kupon_code
     * @return int
     */
    function hitung_diskon_kupon($total_harga, string $kupon_code): int
    {
        $kupon = [
            'FLASH10'  => 0.10,
            'FLASH15'  => 0.15,
            'MEMBER20' => 0.20,
        ];

        $kode = strtoupper(trim($kupon_code));

        if (!isset($kupon[$kode])) {
            return 0;
        }

        return (int) ($total_harga * $kupon[$kode]);
    }
}
