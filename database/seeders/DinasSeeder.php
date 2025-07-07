<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dinas;

class DinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dinasData = [
            ['name' => 'Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Purworejo'],
            ['name' => 'Sekreatariat Daerah Kabupaten Purworejo'],
            ['name' => 'Sekretariat DPRD Kabupaten Purworejo'],
            ['name' => 'Inspektorat Daerah Kabupaten Purworejo'],
            ['name' => 'Dinas Pendidikan dan Kebudayaan Kabupaten Purworejo'],
            ['name' => 'Dinas Kesehatan Daerah Kabupaten Purworejo'],
            ['name' => 'Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Purworejo'],
            ['name' => 'Dinas Perumahan Rakyat, Kawasan Permukiman dan Pertanahan Kabupaten Purworejo'],
            ['name' => 'Dinas Sosial Pengendalian Penduduk dan Keluarga Berencana Kabupaten Purworejo'],
            ['name' => 'Dinas Ketahanan Pangan dan Pertanian Kabupaten Purworejo'],
            ['name' => 'Dinas Lingkungan Hidup dan Perikanan Kabupaten Purworejo'],
            ['name' => 'Dinas Kependudukan dan Pencatatan Sipil Kabupaten Purworejo'],
            ['name' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak serta Pemberdayaan Masyarakat Desa Kabupaten Purworejo'],
            ['name' => 'Dinas Perhubungan Kabupaten Purworejo'],
            ['name' => 'Dinas Kepemudaan, Olahraga dan Pariwisata Kabupaten Purworejo'],
            ['name' => 'Dinas Perpustakaan dan Kearsipan Kabupaten Purworejo'],
            ['name' => 'Dinas Koperasi, Usaha Kecil, Menengah dan Perdagangan Kabupaten Purworejo'],
            ['name' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kabupaten Purworejo'],
            ['name' => 'Dinas Perindustrian, Transmigrasi dan Tenaga Kerja Kabupaten Purworejo'],
            ['name' => 'Satuan Polisi Pamong Praja dan Pemadam Kebakaran Kabupaten Purworejo'],
            ['name' => 'Badaan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Purworejo'],
            ['name' => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia Kabupaten Purworejo'],
            ['name' => 'Badan Pengelolaan Keuangan, Pendapatan dan Aset Daerah Kabupaten Purworejo'],
            ['name' => 'Badan Penanggulangan Bencana Daerah Kabupaten Purworejo'],
            ['name' => 'Badan Kesatuan Bangsa dan Politik Kabupaten Purworejo'],
            ['name' => 'RSUD dr. Tjitrowardojo'],
            ['name' => 'RSUD RAA Tjokronegoro'],
            ['name' => 'Kecamatan Grabag'],
            ['name' => 'Kecamatan Ngombol'],
            ['name' => 'Kecamatan Purwodadi'],
            ['name' => 'Kecamatan Bagelen'],
            ['name' => 'Kecamatan Kaligesing'],
            ['name' => 'Kecamatan Purworejo'],
            ['name' => 'Kecamatan Banyuurip'],
            ['name' => 'Kecamatan Bayan'],
            ['name' => 'Kecamatan Kutoarjo'],
            ['name' => 'Kecamatan Butuh'],
            ['name' => 'Kecamatan Pituruh'],
            ['name' => 'Kecamatan Kemiri'],
            ['name' => 'Kecamatan Bruno'],
            ['name' => 'Kecamatan Gebang'],
            ['name' => 'Kecamatan Loano'],
            ['name' => 'Kecamatan Bener'],
        ];

        foreach ($dinasData as $data) {
            Dinas::updateOrCreate(['name' => $data['name']], $data);
        }
    }
}