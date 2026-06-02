<?php

namespace Database\Seeders;

use App\Models\Researcher;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Tim Peneliti (dari proposal Penelitian Dasar Fundamental) =====
        $researchers = [
            ['name' => 'Anggun Fergina, M.Kom',            'role' => 'Ketua Peneliti',  'affiliation' => 'Dosen — Universitas Nusa Putra',     'sort_order' => 1],
            ['name' => 'M. Ikhsan Thohir, S.Kom., M.Kom',   'role' => 'Anggota Peneliti', 'affiliation' => 'Dosen — Universitas Nusa Putra',     'sort_order' => 2],
            ['name' => 'Aulia Kusuma, S.Tr.Tra., M.MT.',    'role' => 'Anggota Peneliti', 'affiliation' => 'Dosen — Universitas Nusa Putra',     'sort_order' => 3],
            ['name' => 'Siti Marni',                        'role' => 'Anggota Peneliti', 'affiliation' => 'Mahasiswa — Universitas Nusa Putra', 'sort_order' => 4],
            ['name' => 'Puput Handayani',                   'role' => 'Anggota Peneliti', 'affiliation' => 'Mahasiswa — Universitas Nusa Putra', 'sort_order' => 5],
            ['name' => 'Ervin Agustian Gunawan',            'role' => 'Anggota Peneliti', 'affiliation' => 'Mahasiswa — Universitas Nusa Putra', 'sort_order' => 6],
        ];

        foreach ($researchers as $r) {
            Researcher::firstOrCreate(['name' => $r['name']], $r + ['is_active' => true]);
        }

        // ===== Foto Galeri =====
        // Hapus foto lama dari seeder sebelumnya (jika ada) agar galeri konsisten.
        Gallery::where('image_path', 'galleries/2026/06/team_lawu.jpg')->delete();

        $galleries = [
            [
                'image_path'  => 'galleries/2026/05/edelweiss_mekar_pagi.jpg',
                'title'       => 'Edelweis Mekar di Pagi Hari',
                'description' => 'Bunga Edelweis (Anaphalis javanica) yang sedang mekar penuh dengan kelopak putih kekuningan, difoto dari dekat di tengah rerumputan pegunungan.',
                'location'    => 'Gunung Lawu',
                'category'    => 'field',
            ],
            [
                'image_path'  => 'galleries/2026/05/edelweiss_closeup_mekar.jpg',
                'title'       => 'Tandan Bunga Edelweis',
                'description' => 'Detail tandan bunga Edelweis yang sedang mekar, memperlihatkan kelopak bertumpuk khas dan daun jarum keperakan.',
                'location'    => 'Gunung Lawu',
                'category'    => 'field',
            ],
            [
                'image_path'  => 'galleries/2026/05/edelweiss_tunas_siang.jpg',
                'title'       => 'Edelweis Berbunga di Siang Hari',
                'description' => 'Rumpun Edelweis dengan beberapa tandan bunga yang baru mekar, diambil di bawah cahaya matahari siang yang cerah.',
                'location'    => 'Gunung Lawu',
                'category'    => 'field',
            ],
            [
                'image_path'  => 'galleries/2026/05/edelweiss_semak_habitat.jpg',
                'title'       => 'Habitat Semak Edelweis',
                'description' => 'Hamparan semak Edelweis tua dengan daun keperakan yang tumbuh rapat di lereng pegunungan, habitat alami flora endemik ini.',
                'location'    => 'Gunung Lawu',
                'category'    => 'field',
            ],
            [
                'image_path'  => 'galleries/2026/05/edelweiss_kemah_pagi.jpg',
                'title'       => 'Edelweis dan Tenda Pendaki',
                'description' => 'Tandan Edelweis di latar depan dengan tenda-tenda pendaki dan punggungan gunung berkabut di kejauhan saat pagi hari.',
                'location'    => 'Gunung Lawu',
                'category'    => 'field',
            ],
            [
                'image_path'  => 'galleries/2026/05/edelweiss_lembah_kabut.jpg',
                'title'       => 'Edelweis di Lembah Berkabut',
                'description' => 'Bunga Edelweis tumbuh di tepi padang dengan latar perbukitan hijau yang diselimuti kabut tipis dan area perkemahan di kejauhan.',
                'location'    => 'Gunung Lawu',
                'category'    => 'field',
            ],
            [
                'image_path'  => 'galleries/2026/05/padang_edelweiss_senja.jpg',
                'title'       => 'Padang Edelweis Menjelang Senja',
                'description' => 'Pemandangan luas padang Edelweis dengan rumpun keperakan tersebar di savana, perkemahan, dan punggungan gunung saat langit mendung.',
                'location'    => 'Gunung Lawu',
                'category'    => 'field',
            ],
            [
                'image_path'  => 'galleries/2026/05/padang_savana.jpg',
                'title'       => 'Savana Pegunungan',
                'description' => 'Padang savana luas berumput dengan semak dan pepohonan di kaki bukit, lanskap khas dataran tinggi habitat Edelweis.',
                'location'    => 'Gunung Lawu',
                'category'    => 'field',
            ],
            [
                'image_path'  => 'galleries/2026/05/pemandangan_puncak.jpg',
                'title'       => 'Pemandangan Punggungan Gunung',
                'description' => 'Panorama punggungan gunung berhutan lebat dengan langit biru cerah, diambil dari sela-sela vegetasi pegunungan.',
                'location'    => 'Gunung Lawu',
                'category'    => 'field',
            ],
            [
                'image_path'  => 'galleries/2026/05/tim_peneliti_lawu.jpg',
                'title'       => 'Tim Peneliti di Lapangan',
                'description' => 'Dokumentasi tim peneliti saat kegiatan pengumpulan data citra Edelweis langsung di habitat alami kawasan pegunungan.',
                'location'    => 'Gunung Lawu',
                'category'    => 'event',
            ],
            [
                'image_path'  => 'galleries/2026/05/tim_peneliti_lawu2.jpg',
                'title'       => 'Tim Peneliti Bersama Edelweis',
                'description' => 'Tim peneliti berfoto bersama rumpun Edelweis di padang savana dengan latar hutan pegunungan saat kegiatan lapangan.',
                'location'    => 'Gunung Lawu',
                'category'    => 'event',
            ],
        ];

        $tanggal = '2026-05-30';
        foreach ($galleries as $i => $g) {
            Gallery::firstOrCreate(
                ['image_path' => $g['image_path']],
                array_merge($g, [
                    'taken_at'     => $tanggal,
                    'is_published' => true,
                    'sort_order'   => $i + 1,
                    'created_at'   => $tanggal . ' 08:00:00',
                ])
            );
        }
    }
}
