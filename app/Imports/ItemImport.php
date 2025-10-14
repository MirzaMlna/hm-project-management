<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Pastikan kolom ada dan tidak kosong
        if (empty($row['kategori']) || empty($row['nama_barang']) || empty($row['satuan'])) {
            return null; // lewati baris kosong atau tidak lengkap
        }

        // Normalisasi teks (biar huruf besar di awal kata)
        $kategori = ucwords(strtolower(trim($row['kategori'])));
        $namaBarang = ucwords(strtolower(trim($row['nama_barang'])));
        $satuan = ucwords(strtolower(trim($row['satuan'])));
        $keterangan = $row['keterangan'] ?? null;

        // 🔹 Cek apakah kategori sudah ada
        $category = ItemCategory::firstOrCreate(
            ['category' => $kategori],
            ['category' => $kategori]
        );

        // 🔹 Buat kode unik berdasarkan jumlah item yang sudah ada
        $code = 'BRG' . str_pad(Item::count() + 1, 3, '0', STR_PAD_LEFT);

        // 🔹 Tambahkan item baru
        return new Item([
            'item_category_id' => $category->id,
            'code' => $code,
            'name' => $namaBarang,
            'unit' => $satuan,
            'description' => $keterangan,
        ]);
    }

    /**
     * Menentukan baris header (baris pertama di Excel).
     */
    public function headingRow(): int
    {
        return 1;
    }
}
