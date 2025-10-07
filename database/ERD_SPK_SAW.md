erDiagram
    admin {
        int id_admin PK
        varchar username
        varchar password
        varchar nama_lengkap
        varchar level
        text alamat
        varchar no_telp
        varchar email
        timestamp created_at
        timestamp updated_at
    }

    data_warga {
        int id_warga PK
        varchar nama_lengkap UK
        text alamat
        int jumlah_lansia
        int jumlah_disabilitas_berat
        int jumlah_anak_sd
        int jumlah_anak_smp
        int jumlah_anak_sma
        int jumlah_balita
        int jumlah_ibu_hamil
        timestamp created_at
        timestamp updated_at
    }

    tbl_kriteria {
        int id_kriteria PK
        varchar nama_kriteria
        decimal bobot
        enum jenis
        text keterangan
        varchar kode_kriteria UK
        decimal nilai
        timestamp created_at
        timestamp updated_at
    }

    modul {
        int id_modul PK
        varchar nama_modul
        varchar link
        varchar type
        int urutan
        enum aktif
        varchar status
    }

    tbl_klasifikasi {
        int id_klasifikasi PK
        int id_warga FK
        int C1
        int C2
        int C3
        int C4
        int C5
        int C6
        int C7
        int C8
        timestamp created_at
        timestamp updated_at
    }

    tbl_hasil_saw {
        int id_hasil PK
        int id_warga FK
        varchar nama_warga
        decimal C1_norm
        decimal C2_norm
        decimal C3_norm
        decimal C4_norm
        decimal C5_norm
        decimal C6_norm
        decimal C7_norm
        decimal C8_norm
        decimal skor_akhir
        int ranking
        enum rekomendasi
        timestamp created_at
        timestamp updated_at
    }

    tbl_himpunan {
        int id_himpunan PK
        int id_kriteria FK
        varchar keterangan
        int nilai
    }

    tbl_nilai_kriteria {
        int id_nilai PK
        int id_kriteria FK
        varchar keterangan
        int nilai
        int range_min
        int range_max
    }

    tbl_log_bobot {
        int id PK
        int id_kriteria FK
        varchar kode_kriteria
        decimal old_nilai
        decimal new_nilai
        varchar jenis
        varchar aksi
        varchar username
        timestamp created_at
    }

    data_warga ||--o{ tbl_klasifikasi : "1 warga has many klasifikasi"
    data_warga ||--o{ tbl_hasil_saw : "1 warga has many hasil SAW"
    tbl_kriteria ||--o{ tbl_himpunan : "1 kriteria has many himpunan"
    tbl_kriteria ||--o{ tbl_nilai_kriteria : "1 kriteria has many nilai"
    tbl_kriteria ||--o{ tbl_log_bobot : "1 kriteria has many log entries"
