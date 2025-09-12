-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 18 Nov 2015 pada 03.18
-- Versi Server: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;


--
-- Database: `spkold`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE IF NOT EXISTS `absensi` (
  `id` int(11) NOT NULL,
  `kodematkul` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `nim` varchar(50) NOT NULL,
  `pertemuan` int(11) NOT NULL,
  `ket` varchar(4) NOT NULL,
  `status` varchar(4) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id`, `kodematkul`, `tanggal`, `nim`, `pertemuan`, `ket`, `status`) VALUES
(1, 'KA12-101', '2015-11-02', '142510030021', 1, 'v', ''),
(2, 'KA12-101', '2015-11-02', '142510030068', 1, 'v', ''),
(3, 'KA12-101', '2015-11-02', '142510030153', 1, 'v', ''),
(4, 'KA12-101', '2015-11-02', '142510030136', 1, 'v', ''),
(5, 'KA12-101', '2015-11-02', '142510030046', 1, 'v', ''),
(6, 'KA12-101', '2015-11-02', '142510030041', 1, 'v', ''),
(7, 'KA12-101', '2015-11-02', '142510030059', 1, 'v', ''),
(8, 'KA12-101', '2015-11-02', '142510030142', 1, 'v', ''),
(9, 'KA12-101', '2015-11-02', '142510030052', 1, 'v', ''),
(10, 'KA12-101', '2015-11-02', '142510030015', 1, 'v', ''),
(11, 'KA12-101', '2015-11-02', '142510030024', 1, 'v', ''),
(12, 'KA12-101', '2015-11-02', '142510030058', 1, 'v', ''),
(13, 'KA12-101', '2015-11-02', '142510030138', 1, 'v', ''),
(14, 'KA12-101', '2015-11-02', '142510030038', 1, 'v', ''),
(15, 'KA12-101', '2015-11-02', '142510030035', 1, 'v', ''),
(16, 'KA12-101', '2015-11-02', '142510030056', 1, 'v', ''),
(17, 'KA12-101', '2015-11-02', '142510030154', 1, 'v', ''),
(18, 'KA12-101', '2015-11-02', '142510030106', 1, 'v', ''),
(19, 'KA12-101', '2015-11-02', '142510030033', 1, 'v', ''),
(20, 'KA12-101', '2015-11-02', '142510030155', 1, 'v', ''),
(21, 'KA12-101', '2015-11-02', '142510030078', 1, 'v', ''),
(22, 'KA12-101', '2015-11-02', '142510030008', 1, 'v', ''),
(23, 'KA12-101', '2015-11-02', '142510030109', 1, 'v', ''),
(24, 'KA12-101', '2015-11-02', '142510030162', 1, 'v', ''),
(25, 'KA12-101', '2015-11-02', '142510030101', 1, 'v', ''),
(26, 'KA12-101', '2015-11-02', '142510030095', 1, 'v', ''),
(27, 'KA12-101', '2015-11-02', '142510030103', 1, 'v', ''),
(28, 'KA12-101', '2015-11-02', '142510030127', 1, 'v', ''),
(29, 'KA12-101', '2015-11-02', '142510030023', 1, 'v', ''),
(30, 'KA12-101', '2015-11-02', '142510030120', 1, 'v', ''),
(31, 'KA12-101', '2015-11-02', '142510030104', 1, 'v', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int(3) NOT NULL,
  `username` varchar(100) NOT NULL DEFAULT 'administrator',
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `level` varchar(50) NOT NULL DEFAULT 'admin',
  `alamat` text NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `blokir` enum('Y','N') NOT NULL DEFAULT 'N',
  `id_session` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `level`, `alamat`, `no_telp`, `email`, `blokir`, `id_session`) VALUES
(1, 'administrator', '200ceb26807d6bf99fd6f4f0d1ca54d4', 'admin E-Muhdela', 'admin', 'jl.kenari no 145.B ', '085228482669', 'almazari@ymail.com', 'N', '2r5327o6g3q0aqsjnirj9g4840'),
(3, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Adam Abdi A', 'admin', 'Depoan Kg 2 No.0 Rt 0 Rw 2 Yogyakarta', '085228482669', 'adam@gmail.com', 'N', 'iescos1cg73p1j81otolfqbpq4'),
(4, 'Fitri', '77c796ee460fecfb87f038f1bb6fb248', 'Fitriani Nurhidayah', 'admin', 'Baregbeg', '089649071508', 'fitri@gmail.com', 'Y', '77c796ee460fecfb87f038f1bb6fb248');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE IF NOT EXISTS `kelas` (
  `id` int(5) NOT NULL,
  `id_kelas` varchar(5) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `id_kelas`, `nama`) VALUES
(4, '14-01', 'XII B'),
(31, '14-02', 'KA 12 B'),
(32, '15-01', 'XII A'),
(33, '14-16', '9  A');

-- --------------------------------------------------------

--
-- Struktur dari tabel `modul`
--

CREATE TABLE IF NOT EXISTS `modul` (
  `id_modul` int(5) NOT NULL,
  `nama_modul` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `link` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `static_content` text COLLATE latin1_general_ci NOT NULL,
  `gambar` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'Y',
  `status` enum('pengajar','admin') COLLATE latin1_general_ci NOT NULL,
  `aktif` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'Y',
  `urutan` int(5) NOT NULL,
  `link_seo` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Type` varchar(10) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data untuk tabel `modul`
--

INSERT INTO `modul` (`id_modul`, `nama_modul`, `link`, `static_content`, `gambar`, `publish`, `status`, `aktif`, `urutan`, `link_seo`, `Type`) VALUES
(2, 'Manajemen Admin', '?module=admin', '', '', 'N', 'admin', 'N', 2, '', ''),
(37, 'Data Siswa', '?module=siswa', '', 'gedungku.jpg', 'Y', 'admin', 'Y', 1, 'profil-kami.html', ''),
(81, 'Pembobotan Kriteria', '?module=matapelajaran', '', '', 'N', 'admin', 'Y', 5, '', ''),
(78, 'Data Kriteria', '?module=matapelajaran&act=himpunankriteria', '', '', 'Y', 'admin', 'Y', 11, '', ''),
(41, 'Data Kelas', ' ?module=kelas', '', '', 'Y', 'admin', 'Y', 4, 'semua-agenda.html', ''),
(68, 'Laporan Hasil Analisa ', '?module=matapelajaran&act=analisa', '', '', 'Y', 'admin', 'Y', 9, '', 'Report'),
(79, 'Data Klasifikasi', '?module=matapelajaran&act=klasifikasi', '', '', 'Y', 'admin', 'Y', 12, '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajar`
--

CREATE TABLE IF NOT EXISTS `pengajar` (
  `id_pengajar` int(9) NOT NULL,
  `kodedosen` char(12) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `alias_d` varchar(50) NOT NULL,
  `username_login` varchar(100) NOT NULL,
  `password_login` varchar(100) NOT NULL,
  `level` varchar(50) NOT NULL DEFAULT 'pengajar',
  `alamat` text NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `agama` varchar(20) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `foto` varchar(100) NOT NULL,
  `website` varchar(100) DEFAULT NULL,
  `jabatan` varchar(200) NOT NULL,
  `blokir` enum('Y','N') NOT NULL DEFAULT 'N',
  `id_session` varchar(100) NOT NULL,
  `honor` int(11) DEFAULT NULL,
  `npwp` varchar(100) DEFAULT NULL,
  `kewajiban` int(11) DEFAULT NULL,
  `bidang` varchar(5) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pengajar`
--

INSERT INTO `pengajar` (`id_pengajar`, `kodedosen`, `nama_lengkap`, `alias_d`, `username_login`, `password_login`, `level`, `alamat`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `agama`, `no_telp`, `email`, `foto`, `website`, `jabatan`, `blokir`, `id_session`, `honor`, `npwp`, `kewajiban`, `bidang`) VALUES
(1, 'D001', 'Akhmad A. Rokhiyat', 'Akhmad A. Rokhiyat', 'D001', 'd5cbf528f740b502b79241ff873ce6c5', 'pengajar', 'JL.NAKULA NO 59, PANGLAYUNGAN - TASIKMALAYA', '', '1966-02-12', 'L', 'Islam', '081395552627', 'akhmad.rokhiyat@brisyraih.co.id', 'AAR.jpg', '', 'Part-time', 'N', 'j8t0fd07o11jb2frne1okp71h3', 75000, '264901943425000', NULL, NULL),
(2, 'D002', 'Andi Usmar, S.E., M.M.', 'Andi Usmar, S.E., M.M.', 'D002', 'b3460c056d63af44fe66bd92c961a144', 'pengajar', '', '', '1950-01-01', 'L', 'Islam', '081222837665', '', 'Andi Usmar.jpg', '', 'Part-time', 'N', 'g3jcq5uvk108vvt3srjpbv39g3', 67500, '', NULL, NULL),
(3, 'D003', 'Arip Budiman, S.T.', 'Arip Budiman, S.T.', 'D003', 'fda7f226bff2b2df5da2e703f84bab11', 'pengajar', 'Kp.Cikuya RT 03 RW 07 Kec . Rajapolah Kab. Tasikmalaya', 'Bandung', '1987-04-02', 'L', 'Islam', '085352244255', 'otomotif.lp3itasik@gmail.com', 'Arip Budiman.jpg', '', 'Koordinator', 'N', 'd3tcu6o5keqvk25g63hikk6f11', 45000, 'NA', 32, NULL),
(4, 'D004', 'Asep Alamsyah, S.Kom.', 'Asep Alamsyah, S.Kom.', 'D004', '22d164d469bfc0bc0f2aefe67dd2806e', 'pengajar', 'Jl. Pesantren Condong Kp. Nyompet RT 04 RW 03 Kel. Setianagara Kec. Cibeureum Kota Tasikmalaya', 'Tasikmalaya', '1972-04-09', 'L', 'Islam', '081324501759', 'asep_alamsyah@rocketmail.com', 'asep.jpg', 'http://www.mtsn-cilendek.web.id', 'Part-time', 'N', 'sku5kcacqlmi13cdoolcrr8f57', 62500, '257345298425000', NULL, NULL),
(5, 'D005', 'Asep Rukmantara', 'Asep Rukmantara', 'D005', 'b7ca9b1c84b0669f89357ee7bafb1274', 'pengajar', '', '', '1950-01-01', 'L', '', '08156000309', '', 'rukmantara.jpg', '', 'Part-time', 'N', 'qi46p9c2ch5s3i99l5nkivlsp3', 80000, '661705244425000', NULL, NULL),
(6, 'D006', 'Asep Solihin, ST', 'Asep Solihin, ST', 'D006', '0d86b2d838d51e9ae5d1720054578f16', 'pengajar', '', '', '1978-05-01', 'L', 'Islam', '085223678123', '', 'asol.jpg', 'http://aresave.blogspot.com', 'Part-time', 'N', 'jfu0jp6nql3ucft441nqfcmrd0', 62500, NULL, NULL, NULL),
(7, 'D007', 'Ating Suryana, S.Sos', 'Ating Suryana, S.Sos', 'D007', '89dcc06341a2e25e573a26d0e780fbbb', 'pengajar', '', '', '1957-04-28', 'L', '', '085295486764', '', 'ating.jpg', '', 'Part-time', 'N', 'eg9r1ent6laaufc66sevtv4pn5', 67500, NULL, NULL, NULL),
(8, 'D008', 'Cecep Riki, ST', 'Cecep Riki, ST', 'D008', 'a7a81ba176b755a597b776604940a2c5', 'pengajar', '', '', '1974-05-24', 'L', 'Islam', '085223393056', 'skuadron_74@yahoo.com', 'ceri.jpg', 'nebeng k batur.net', 'Part-time', 'N', '98of7vqhen0ttld4npmjr6ng87', 65000, NULL, NULL, NULL),
(9, 'D009', 'Dede Rahayu, S.Sos., M.Pd', 'Dede Rahayu, S.Sos', 'D009', 'f6cce92549c349a9953bd877f4860f32', 'pengajar', 'Perum Gunung Ranji Regency Blok C-1 Babakan Tengah Cisumur Kawalu Kota Tasikmalaya 46182', 'DKI jakarta', '1975-10-25', 'L', 'Islam', '082121823375', 'deratasikmalaya@gmail.com', 'Dera Unsil-1.jpg', '', 'Part-time', 'N', 's1ff15utft0hvpmkl66o7o9740', 70000, NULL, NULL, NULL),
(10, 'D098', 'Rosliana', 'Rosliana', 'D098', '', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Library Staff', 'N', '', NULL, NULL, NULL, NULL),
(11, 'D011', 'Dheri Febiyani L, S.Pd', 'Dheri Febiyani L, S.Pd', 'D011', 'd5e4effc166af7f050089416e7e53b57', 'pengajar', 'Perum bumi citra pagaden Blok G.28 Kec Kawalu Tasikmalaya', 'Cianjur', '1987-02-21', 'P', 'Islam', '085223333553', 'dheri@lp3i.ac.id', 'dheri.jpg', '', 'Part-time', 'N', '4jh104db5jt9ubc0a30l82del6', 50000, 'NA', NULL, NULL),
(12, 'D012', 'Djoko Subinarto, SS', 'Djoko Subinarto, SS', 'D012', '1bc964f6f1e70dcba774fd80fe1118e0', 'pengajar', '', '', '1950-01-01', 'L', '', '08562376886', 'djoko@mail.ua', 'Djoko.jpg', '', 'Part-time', 'N', 'sc1r8hvas47lu4053mm12votd4', 80000, '196602909428000', NULL, NULL),
(13, 'D013', 'Asep Wahyudin, S.Pd', 'Asep Wahyudin, S.Pd', 'D013', 'e66e21ac2b0dbf5f42a77ae8373e789e', 'pengajar', '', '', '1950-01-01', 'L', '', '081324214732', '', 'aswa.jpg', '', 'Part-time', 'N', 'u66c250km3spi3b94j5jatben4', 82500, NULL, NULL, NULL),
(14, 'D014', 'DR.Herwan S, S.Pd., M.Si', 'DR.Herwan S, S.Pd., M.Si', 'D014', '420c0ede70cff1d160c862004a7bfee0', 'pengajar', 'Jl. Rajawali Barat No 466/187 Bandung 40184', 'Bandung', '1970-10-20', 'L', 'Islam', '081324214732', 'herwans70@gmail.com', 'herwan.jpg', '', 'Part-time', 'N', 'n61cdm78hm22m5la1sqchl21d0', 65000, NULL, NULL, NULL),
(16, 'D017', 'Ernawati, SE,M.Pd', 'Ernawati, SE,M.Pd', 'D017', '447f299d2ed46a403af8ec2b9cfff194', 'pengajar', '', 'Tasikmalaya', '1974-11-14', 'P', 'Islam', '081323303080', '', 'ernawati.jpg', '', 'Koordinator', 'N', 'pc1ikf0fnhduvta8rko09s3ks4', 45000, 'NA', 32, NULL),
(17, 'D018', 'Febriana Hidayati, S.Pd', 'Febriana Hidayati, S.Pd', 'D018', '62cc46459284fd62c0ad61eb117f1645', 'pengajar', '', '', '1976-02-24', 'P', 'Islam', '085237107907', '', '', '', 'Part-time', 'N', 'q29l79vao3vreqppmn4nm08b40', 72500, NULL, NULL, NULL),
(18, 'D019', 'H. Agus Nugraha, BSC, SE, MBA', 'H. Agus N, BSC, SE, MBA', 'D019', '5e78d6d7d6b97e07863f2b2ac6c72c49', 'pengajar', 'JL. PERUM KOTA BARU KENCANA KP.CIEURIH RT.01 RW.07 N0.14 (DEPAN MESJID ASSULAHA) KERSANAGARA CIBEUREUM TASIKMALAYA', 'TASIKMALAYA', '1968-11-17', 'L', 'Islam', '085316987799', 'sigoest@yahoo.com', '1382246683790.jpg', 'agusnugraha.web.com', 'Part-time', 'N', '4ukumch5rguf1ipj9p63i5sc55', 70000, NULL, NULL, NULL),
(19, 'D020', 'H. Rudi Kurniawan, ST.,MM', 'H. Rudi Kurniawan, ST.,MM', 'D020', '0bfb179af3c357296c35d64d10a7c0b3', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Part-time', 'N', 'ne29f1924knv6rb22mpeqterq3', 75000, 'NA', NULL, NULL),
(20, 'D073', 'Elin Yuliani', 'Elin Yuliani', 'D073', '7cbe723ded3e93d528fc6c77b4c7c8fb', 'pengajar', '', '', '2015-01-01', 'P', 'Islam', '085294872942', '', '', '', 'Part-time', 'N', '89s57povb6dnqe65unrpis73q0', 60000, NULL, NULL, NULL),
(21, 'D022', 'Hermien Ardhyanti', 'Hermien A, SE', 'd083', 'ab9f950415e10b11f532002c0526484a', 'pengajar', 'Jl Raya Imbanagara No 411A Ciamis', 'Tasikmalaya', '1969-07-23', 'P', 'Islam', '081313921910', 'abbsblue@gmail.com', '', '', 'Part-time', 'N', 'qcbb69iib0dv585mhrcltec3u5', 85000, NULL, NULL, NULL),
(22, 'D023', 'Ima Rosmiati, S.Pd., M.Pd', 'Ima Rosmiati, S.Pd', 'D023', 'f2da24cc2c612a0002eb7f07ec02801b', 'pengajar', 'Jalan Kapten Jamhur No. 44 Banjar', 'Garut', '1982-11-14', 'P', 'Islam', '081221980014', 'ima_rosmiati@yahoo.co.id', '', '', 'Part-time', 'N', 'tunh1gf3ogomk7mo76t3s2fpn2', 60000, '441435781442000', NULL, NULL),
(23, 'D024', 'Iman Harjono, SE', 'Iman Harjono, SE', 'D024', 'cb756d3448ad467033846e84017824f3', 'pengajar', '', '', '1967-10-07', 'L', 'Islam', '02292116484', 'iman_powers@yahoo.com', '', '', 'Part-time', 'N', 'ciifpgosa41h2gtki09c0gd5s3', 95000, '365142801429000', NULL, NULL),
(24, 'D025', 'Imas Masturoh', 'Imas Masturoh', 'D025', 'c2921c13fa62065c8d28c85aae51497e', 'pengajar', '', '', '1950-01-01', 'L', '', '08179291945', '', '', '', 'Part-time', 'N', 'sls0ubufe1oaftsk0itnggn903', 62500, '313129417425000', NULL, 'PAI'),
(25, 'D026', 'Indri Fitrianasari, S.Kom', 'Indri Fitrianasari, S.Kom', 'D026', 'ec6d0d0555498e764280158310986e87', 'pengajar', '', '', '1989-04-02', 'P', 'Islam', '085223405088', '', '', '', 'Part-time', 'N', 'ocouclb4addg8pllpvkav6hqe0', 40000, 'NA', NULL, NULL),
(26, 'D027', 'Ir.Mia Sumiarsih, MM', 'Ir.Mia Sumiarsih, MM', 'D027', 'b7ea2ca01a87777825bc151ebf8f8e2b', 'pengajar', '', '', '1968-04-16', 'P', '', '085323389766', '', '', '', 'Part-time', 'N', 'mu4hpk81hu558h5mg1ga960i54', 67500, '000000000000001', NULL, NULL),
(27, 'D028', 'Iwan M. Ridwan, M.Kom', 'Iwan Ridwan, M.Kom', 'D028', '299be138d615ca9d4dd0d382a642e96c', 'pengajar', '', '', '1973-04-16', 'L', 'Islam', '081320470412', 'iwan47a@yahoo.co.id', '', '', 'Part-time', 'N', 'r1jsqe4f30klrnfpsmgm603mg5', 70000, '260459474425000', NULL, NULL),
(28, 'D029', 'Jono Taryono', 'Jono Taryono', 'D029', 'fcc8fa0ea88d370507a094126bd40fae', 'pengajar', '', '', '1950-01-01', 'L', '', '082119201484', '', '', '', 'Part-time', 'N', '0rjf74m7glqhrc7lcv2jt0tvq1', 75000, '466320556425000', NULL, NULL),
(29, 'D030', 'Kiki Supendi, ST', 'Kiki Supendi, ST', 'D030', '9b4444362f0552972f13bf5536751e75', 'pengajar', '', 'Purwakarta', '1977-01-20', 'L', 'Islam', '085224175689', 'kikisupendi@gmail.com', '', '', 'Part-time', 'N', '8mmauksr12b53b4afjlhbsecv6', 65000, NULL, NULL, NULL),
(30, 'D031', 'Komarudin Tasdik,S.Kom', 'Komarudin,S.Kom', 'D031', '01f00daeed1418fb5cf6d8988d8a8fab', 'pengajar', 'Parakanmuncang-Sumedang', 'Garut', '1982-11-04', 'L', 'Islam', '085322356999', 'komarudintasdik28@gmail.com', '', 'www.katabah.com', 'Part-time', 'N', 'p818o5ar0rnd9o6phhghehtd20', 67500, '353206683443000', NULL, NULL),
(31, 'D032', 'Lucy Dian Rosalin, SE', 'Lucy Dian Rosalin, SE', 'D032', '32d5e36f384bebd5d3753a04a0302eec', 'pengajar', 'Perum Taman Abdi Negara Blok D No. 9, Tamansari, Tasikmalaya', 'Tasikmalaya', '1979-01-27', 'P', 'Islam', '08122259208', 'lucydianrosalin@yahoo.com', '', 'www.sekolahkomunikasimiracle.com', 'Part-time', 'N', 'u0hd47kgb9de9gejlkj109qln3', 75000, '443076476425000', NULL, NULL),
(32, 'D033', 'Nelly Amelia Suryani, S.Pd', 'Nelly Amalia, S.Pd', 'D033', '7bdf081dc5e322c3da257e565ba540e5', 'pengajar', '', '', '1950-09-18', 'P', 'Islam', '085223590318', '', '', '', 'Part-time', 'N', 'tspgbfo86hlqvdfbjajno1t0g5', 67500, NULL, NULL, NULL),
(33, 'D034', 'Neni Sholihat, M.Psi', 'Neni Sholihat, M.Psi', 'D034', '8cc054261ce69fc6f8fa34acf567ed34', 'pengajar', '', '', '1974-01-23', 'P', 'Islam', '081323400476', 'nsholihat@yahoo.com', '', 'nsholihat.wordpress.com', 'Part-time', 'N', 'b6bl0kb03n2i3uk5srn1g4i872', 70000, NULL, NULL, NULL),
(34, 'D035', 'Nia Senia Tresnasari, S.S', 'Nia Senia Tresnasari, S.S', 'D035', 'e95451662d98089c12c674cd6c4c95fa', 'pengajar', '', '', '1950-01-01', 'P', '', '02659115161', '', '', '', 'Part-time', 'N', 'fadjb61hjdmk4ehku8g05p9po3', 62500, '709631295425000', NULL, NULL),
(35, 'D036', 'Rahmat W., M. P., MM', 'Rahmat W., M. P., MM', 'D036', '8275737b27435dc0e3824ffa513d38c6', 'pengajar', '', '', '1982-10-04', 'L', 'Islam', '087728296000', '', '', '', 'Part-time', 'N', 'gqddomhfajkf30nmho9bnm6co5', 67500, '1111111', NULL, NULL),
(36, 'D037', 'Rani Ligar Fitriani, S.Pd', 'Rani Ligar Fitriani, S.Pd', 'D037', 'e57284ac2f370a5e8670b04b59e33222', 'pengajar', 'Perum Situ Gede Indah A.75 Tasikmalaya', 'Tasikmalaya', '1987-05-21', 'P', 'Islam', '08179217805', 'raniigay@yahoo.com', 'editedit.jpg', '', 'Koordinator', 'N', '85higfceqn767l6getsmh1fjs3', 45000, 'NA', 32, NULL),
(37, 'D038', 'Rani Maulina A, S.Pd', 'Rani Maulina, S.Pd', 'D038', '5ecb03a9c52173882477750dc793e27f', 'pengajar', 'Perum Graha Persada F 40', 'Tasikmalaya', '1981-01-28', 'P', 'Islam', '082217026375', '', '', '', 'Part-time', 'N', 'brdf6mu8fu5crqg37c9l53vb93', 75000, NULL, NULL, NULL),
(38, 'D039', 'Riki Nuryadin, S.Pd', 'Riki Nuryadin, S.Pd', 'D039', '3fecfa8184617158f652b293b57907f3', 'pengajar', '', '', '1950-01-01', 'L', '', '081223682530', '', '', '', 'Part-time', 'N', 'dl2889638883j7tkt68g8200t5', 85000, NULL, NULL, NULL),
(39, 'D040', 'Rini Susilawati, MM', 'Rini Susilawati, MM', 'D040', '17501b593e86c204ad269fa190b38b91', 'pengajar', 'bandung', 'bandung', '1970-01-18', 'P', 'Islam', '087822914015', 'rini_id@yahoo.com', '', '', 'Part-time', 'N', '87qhja0qaj0dba0b8a0umq8br7', 95000, NULL, NULL, NULL),
(40, 'D041', 'Usep Abdul Rosid, ST.', 'Usep Abdul R, ST.', 'D041', '0d9e459da47aad740f266a5610da11ba', 'pengajar', 'Perum Melatimas Residence2 Parakan Honje Indihiang', 'Garut', '1989-05-12', 'L', 'Islam', '085222116925', 'ushefcenco@gmail.com', 'SP_A0376.jpg', 'kakacenco.blogspot.com', 'Part-time', 'N', 'v9qq65ioobun43fubtgskm6du2', 52500, NULL, NULL, NULL),
(41, 'D042', 'Uyu Wachyu. Ruhiyat, S. Kom', 'Uyu W. Ruchiyat, S. Kom', 'D042', '7781df0d4d7ac2a74ef45437c7346560', 'pengajar', 'Perum Bumi Kahuripan A7 Tasikmalaya', 'Ciamis', '1976-05-29', 'L', 'Islam', '081546861778', 'uyuwachyu@gmail.com', '', '', 'Part-time', 'N', 'c3hil9l8jfe2dpvp2abeqkm4b7', 80000, '886160860425000', NULL, NULL),
(44, 'D045', 'Yopi Hermawan, S. Pd', 'Yopi Hermawan, S. Pd', 'D045', 'ed259682b3478162ec02d965248a7cb8', 'pengajar', '', '', '1973-03-15', 'L', 'Islam', '085222089654', 'yopikoe73@gmail.com', '', '', 'Part-time', 'N', 'cndi6qeroeld5qqqhn5dpc40j5', 82500, NULL, NULL, NULL),
(45, 'D046', 'Yudi Hendarman, MM', 'Yudi H, MM', 'D046', '982edae44597887cb83f5df073f71103', 'pengajar', '', '', '1950-01-01', 'L', 'Islam', '08121435739', 'yudihen68@gmail.com', '', '', 'Part-time', 'N', 'juj73ea7v0lvhscrr9k11u6qu7', 80000, NULL, NULL, NULL),
(46, 'D047', 'Yudi Mulyadi, SE', 'Yudi Mulyadi, SE', 'D047', '843f740ebf0e4f2b4213e79bdf2bbdec', 'pengajar', 'Cisalak Pratama, Sukamanah, Cipedes, Tasikmalaya', 'Garut', '1987-07-16', 'L', 'Islam', '085323720007', 'yudimulyadi987@gmail.com', '', '', 'Part-time', 'N', 'rbtcc72gprlj1hktm3lbollff3', 85000, NULL, NULL, NULL),
(47, 'D048', 'M. Jusuf Hamzah,S.E', 'M. Jusuf Hamzah,S.E', 'D048', 'c740074bfcdb9e3a84d65e1d7b43a6c5', 'pengajar', 'FORTUNA REGENCY BLOKD-9, CILEMBANG TASIKMALAYA', 'TASIKMALAYA', '1971-08-30', 'L', 'Islam', '081312949392', 'jusuf.hamzah@gmail.com', '', '', 'Part-time', 'N', 'pjhkk4antg1b125ts8qe07ke22', 90000, '', NULL, NULL),
(48, 'D049', 'Zulkarnaen, S.Psi', 'Zulkarnaen, S.Psi', 'D049', 'fb30e8fa73e48b6398f919675e7edef0', 'pengajar', 'Kp. Sukawangi No.48 Singaparna', 'Gresik ', '1982-12-17', 'L', 'Islam', '0811212909', 'cakrahusada.tasikmalaya@gmail.com', '', '', 'Part-time', 'N', 'c84v6e684r400iiirqge16feh4', 60000, NULL, NULL, NULL),
(51, 'D052', 'Fine Afriani, SE., Ak.', 'Fine Afriani, SE', 'D052', 'f312de47fef4936f1c646b2e299c43e9', 'pengajar', 'Jl. Letnan Harun Cijolang Sebrang Polresta Tasik', 'Tasikmalaya', '1986-04-02', 'P', 'Islam', '087728099095', 'fine_afriani@yahoo.com', 'My famz.jpg', '', 'Part-time', 'N', 'mo8jn2pbh5603ogfc7l4t8cc02', 62500, '541008785425000', NULL, NULL),
(52, 'D053', 'Indah Permatasari, S. Pd', 'Indah Permatasari, S. Pd', 'D053', 'f66026e6c2df8cd86ce29fabe30c4832', 'pengajar', 'Jl. Asrama Nyantong No. 101A', 'Bekasi', '1986-05-02', 'P', 'Islam', '085721670800', 'ndah_cantik86@yahoo.com', '', '', 'Part-time', 'N', 'hhs5a7vvcdst5o0mtufehbomq4', 57500, NULL, NULL, NULL),
(53, 'D054', 'Rudi Permadi, M. Pd', 'Rudi Permadi, S. Pd', 'D054', 'ea876d445b0da7a71357d135b60508fd', 'pengajar', '', '', '1983-07-16', 'L', 'Islam', '085223151282', '', '', '', 'Part-time', 'N', 'qlevj0bq1er4j6uuhdj6o61256', 62500, '350924445425000', NULL, NULL),
(54, 'D056', 'Yudi Kurniadi,S.Pd', 'Yudi Kurniadi,S.Pd', 'D056', 'c016b1ae33a5c890f3b5b624bcbe0589', 'pengajar', '', '', '1950-01-01', 'L', '', '085317710404', '', '', '', 'Full-time', 'N', 'nces7890oa9ap1cslokpjhi5n0', 40000, 'NA', 40, NULL),
(55, 'D057', 'Fitriyadi Abdillah, S.E.', 'Fitriyadi Abdillah, S.E.', 'D057', '70219094f08f030aeea33b19a8910f34', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Part-time', 'N', '74b5mkksgfg00jas6d4052hss7', 55000, NULL, NULL, NULL),
(56, 'D058', 'Dicky Zulkarnaen', 'Dicky Zulkarnaen', 'D058', 'fd8dae4b2a9b131522866220617403b8', 'pengajar', '', 'Jakarta', '1977-07-03', 'L', '', '081322552876', '', '', '', 'Part-time', 'N', '1fqd8bo7nsfnquqd8s0dkadq12', 100000, '249482019425000', NULL, NULL),
(57, 'D059', 'Heri Sugara, SE., BKP.', 'Heri Sugara, S.E.', 'D059', '74d90aafda34e6060f9e8433962d14fd', 'pengajar', 'Jl. R.E. Martadinata 221 Indihiang Tasikmalaya', 'Ciamis', '1980-10-04', 'L', 'Islam', '085723454838', 'herrie.sugara@gmail.com', '', 'www.sagaraya.com', 'Part-time', 'N', '7gs46sv69hmhrgsvj9n8p09nr7', 60000, '249482019425000', NULL, NULL),
(58, 'D060', 'Fatimah Zahra Nasution, S.E.I., M.A.', 'Fatimah Zahra Nasution, S.E.I., M.A.', 'D060', '892fedcefb6090595136ebde9b31d346', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Part-time', 'N', 'epe4vq7b2qmr4003b172eo1c52', 50000, NULL, NULL, NULL),
(59, 'D061', 'H. Rudi Setiawan, S.E., M.M.', 'H. Rudi Setiawan, S.E., M.M.', 'D061', '1e42f9927952777889110290cf8c8e81', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Part-time', 'N', '', 50000, NULL, NULL, NULL),
(60, 'D062', 'Desiana, S.E.', 'Desiana, S.E.', 'D062', '47d3a7f0535c3a3b5d131bc568a41ad3', 'pengajar', '', '', '1950-01-01', 'P', '', '085223937392', '', 'desiana.jpg', '', 'Koordinator', 'N', '9qkm89mttnr791b4f9kim55gn0', 40000, 'NA', 32, NULL),
(62, 'D064', 'Suryana', 'Suryana', 'D064', '406597782abd624c9feb67dced9176a7', 'pengajar', 'Kp. Legoknagka RT 06/06 Kel. Cibunigeulis Kec. BUngursari Kota Tasikmalaya', 'tasikmalaya, ', '1975-05-25', 'L', 'Islam', '081320600614', 'suryanazieba@gmail.com', '', '', 'Part-time', 'N', 'rnljaetklfbdermmf3stkgf2b3', 75000, '', NULL, NULL),
(63, 'D065', 'Liana Dewi, S.Pd.', 'Liana Dewi, S.Pd.', 'D065', '6a496d5665009ab8e632abd7a98e218d', 'pengajar', 'jl. tentara pelajar gg. daya rasa no.31', 'tasikmalaya', '1988-04-05', 'P', 'Islam', '085221982559', 'lianadewi73@gmail.com', '', '', 'Part-time', 'N', 'mj0kbhsstljmdai72vs4jhfo15', 57500, '717637656425000', NULL, NULL),
(64, 'D066', 'Luqman Hakim', 'Luqman Hakim', 'D066', '056e3d1a05beeecf63556ada9bfd24f1', 'pengajar', 'Perum Mutiara Cilolohan H15', 'Tasikmalaya', '1986-09-11', 'L', 'Islam', '081323129704', 'alhakim_pik@yahoo.com', '', '', 'Part-time', 'N', '756ou37a8qr475raj9t79qunf4', 50000, '472498799425000', NULL, NULL),
(65, 'D067', 'Wafa Tsamrotul F, S.Pd.', 'Wafa Tsamrotul F, S.Pd.', 'D067', '728a36fbe8b5ad9c64b6a1966ed5d976', 'pengajar', 'Patrol Kulon Manonjaya', 'Tasikmalaya', '2015-07-15', 'P', 'Islam', '089662489662', 'wafa.0606959@gmail.com', '', '', 'Part-time', 'N', 'qeaisc4non1soo0ij11k7e6c62', 40000, 'NA', NULL, NULL),
(66, 'D068', 'Untung Eko S, S.Sos., M.A.', 'Untung Eko S, S.Sos., M.A.', 'D068', '52e1ee0c6cb6ef8d11d4844bc40701c7', 'pengajar', 'jl. Asrama Nyantong Perum Wulan Regency No. 14 ', 'Yogyakarta', '1985-08-07', 'P', 'Islam', '081312691555', 'setyasari@yahoo.com', '', '', 'Part-time', 'N', 'lgmem68bppk07pigub57vpd523', 50000, 'NA', NULL, NULL),
(67, 'D069', 'Iwan Gunawan', 'Iwan Gunawan', 'D069', 'cbabb6315360e9b60b32e8abe7fdb85c', 'pengajar', 'Kp.kedunghalang,RT 04/01,Ds.Tarunajaya,Kec.Sukaraja,Kab.Tasikmalaya', 'Tasikmalaya', '1984-07-17', 'L', 'Islam', '081213641302', 'iwan.gunawan@nissan.indomobil.co.id', '', '', 'Part-time', 'N', '4a0ca5nt5n2rede423418r6sr7', 75000, NULL, NULL, NULL),
(68, 'D070', 'Yanti Fadila Wahab,S.Pd', 'Yanti Fadila Wahab,S.Pd', 'D070', '342727840bcc06a3cb0b68e7433d6f37', 'pengajar', 'jl guntur no 67 blok 1 Rt/Rw : 06/13 Perum bumi resik panglayungan', 'Tasikmalaya', '1989-10-26', 'P', 'Islam', '085223319913', 'yanti.fadila@gmail.com', '', '', 'Part-time', 'N', 'a2nrpan4vv54oavta68a12rlj5', 40000, 'NA', NULL, NULL),
(69, 'D071', 'Muhamad Farihin,S.T', 'Muhamad Farihin,S.T', 'D071', 'bc8919c70be4794710133f6c05349604', 'pengajar', 'Banjar', 'Langensari', '1990-09-27', 'L', 'Islam', '085722386538', 'farihinmuhamad@gmail.com', '', 'farihinmuhamad.blogspot.com', 'Part-time', 'N', '9dlc2l2t3bbfq5k7nu6phtvrn5', 40000, 'NA', NULL, NULL),
(71, 'D072', 'Aep Saepudin,S.Pd', 'Aep Saepudin,S.Pd', 'D072', 'a4048e19d4b8f4c46dcfa94724ef4761', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Koordinator', 'N', 'cm7d5sp9ih1us11nf50dvd3412', 45000, 'NA', 32, 'PAI'),
(72, 'D076', 'Rudi Hartono', 'Rudi Hartono', 'D076', '63e8edcba4405a63335753c5808bee38', 'pengajar', '', '', '1990-12-23', 'L', 'Islam', '085223120103', '', '', '', 'Part-time', 'N', 'd1jiu5p22dcglksnvgd97dq671', 40000, 'NA', NULL, NULL),
(73, 'D077', 'Adam Abdi A,A.Md', 'Adam Abdi A,A.Md', 'D077', '', 'pengajar', '', '', '1950-01-01', 'L', '', '089649071509', '', '', '', 'Staff IT', 'N', '', 30000, 'NA', NULL, NULL),
(74, 'D078', 'Tresna Retna Asih', 'Tresna Retna Asih', 'D078', '', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Asst. Lab', 'N', '', 30000, 'NA', NULL, NULL),
(75, 'D079', 'Bini Hasbiani,S.T', 'Bini Hasbiani,S.T', 'D079', '', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Asst. Lab', 'N', '', 12500, 'NA', NULL, NULL),
(76, 'D0100', '--Pilih Dosen--', '--Pilih Dosen--', 'D0100', '', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Part-time', 'Y', '', NULL, NULL, NULL, NULL),
(77, 'D080', 'M.Arifin', 'M.Arifin', 'D080', '', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Education Staff', 'N', '', 40000, 'NA', NULL, NULL),
(78, 'D081', 'Yopi Fernando', 'Yopi Fernando', 'D081', '', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Asst. Lab', 'N', '', 40000, 'NA', NULL, NULL),
(79, 'D075', 'Suminar', 'Suminar', 'D075', 'ced8910367b19e7a8b7ac0639c265439', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Part-time', 'N', 'dsslimao1ffbaaajn96n5d3793', 50000, NULL, NULL, NULL),
(80, 'D082', 'Yadi Herdiyadi,S.E', 'Yadi Herdiyadi,S.E', 'D082', '0e72ec097b4b693c6edd578cbcedf73c', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Part-time', 'N', '', 65000, NULL, NULL, NULL),
(81, 'D083', 'Eep Hanapi', 'Eep Hanapi', 'D083', '898e56ea6daa433c9df59cbbf0919fe5', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Part-time', 'N', 'qcbb69iib0dv585mhrcltec3u5', 60000, '241608140425000', NULL, NULL),
(82, 'D084', 'Agus M Faruq', 'Agus M Faruq', 'D084', 'a6143e2e2505543f990ec591de6f4716', 'pengajar', 'Villa Marhamah Regency', 'Tasikmalaya City', '1950-01-01', 'L', 'Islam', '085321845510', 'agus_faruq@yahoo.co.id', 'ji.jpg', '', 'Part-time', 'N', '79iool41ksb3e276te1ggqhiv3', 40000, 'NA', NULL, 'PAI'),
(83, 'D085', 'Itoh Siti Masyitoh', 'Itoh Siti Masyitoh', 'D085', '', 'pengajar', '', '', '0000-00-00', 'L', '', '', NULL, '', NULL, 'Part-time', 'N', '', 55000, '972121743425000', NULL, 'PAI'),
(84, 'D086', 'M Abdurrahman', 'M Abdurrahman', 'D086', '', 'pengajar', '', '', '0000-00-00', 'L', '', '', NULL, '', NULL, 'Part-time', 'N', '', 50000, NULL, NULL, 'PAI'),
(85, 'D087', 'Uang Redy', 'Uang Redy', 'D087', '', 'pengajar', '', '', '0000-00-00', 'L', '', '', NULL, '', NULL, 'Part-time', 'N', '', 45000, '', NULL, 'PAI'),
(86, 'D088', 'Asep Koswara', 'Asep Koswara', 'D088', '', 'pengajar', '', '', '0000-00-00', 'L', '', '', NULL, '', NULL, 'Part-time', 'N', '', 62500, NULL, NULL, 'PAI'),
(87, 'D089', 'Rahmi Rahmah', 'Rahmi Rahmah', 'D089', '', 'pengajar', '', '', '0000-00-00', 'L', '', '', NULL, '', NULL, 'Part-time', 'N', '', 50000, NULL, NULL, 'PAI'),
(88, 'D090', 'Ai Rohayati', 'Ai Rohayati', 'D090', '', 'pengajar', '', '', '0000-00-00', 'L', '', '', NULL, '', NULL, 'Part-time', 'N', '', 50000, NULL, NULL, 'PAI'),
(89, 'D099', 'M Fadil Sahab', 'M Fadil Sahab', 'D099', '', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Asst. Lab', 'N', '', 12500, 'NA', NULL, NULL),
(90, 'D091', 'Abu Bakar Sidik, S.E', 'Abu Bakar Sidik, S.E', 'D091', '07da93c2aef8782eddc45d973dfd969d', 'pengajar', '', '', '1950-01-01', 'L', '', '', '', '', '', 'Part-time', 'N', '', 50000, NULL, NULL, NULL),
(91, 'D093', 'Meirlinda Hapsari,S.E', 'Meirlinda Hapsari,S.E', 'D093', '', 'pengajar', '', '', '0000-00-00', 'L', '', '', NULL, '', NULL, 'Part-time', 'N', '', 50000, NULL, NULL, NULL),
(92, 'D092', 'Roni Fitra, A.Md.', 'Roni Fitra, A.Md.', 'D092', 'fd05e86c275985416b3b8818ef1d0648', 'pengajar', '', '', '1950-01-01', 'L', 'Islam', '', '', 'Roni Fitra.jpg', '', 'Part-time', 'N', '3sv7uh38cosg1gti1ems49vm50', 50000, '792762809425000', NULL, NULL),
(94, 'D100', 'Asep Dadan S., S.E.', 'Asep Dadan S., S.E.', 'D100', 'f89c18c51c263fcda4d5b554af5b30b4', 'pengajar', 'Pelang No 141 kel. sukamanah kec. cipedes kota tasikmalaya', 'Tasikmalaya', '1985-09-07', 'L', 'Islam', '082119111771', 'asdanar@gmail.com', 'IMG00115-20130428-0751.jpg', 'http://', 'Full-time', 'N', 'hvk8hreq2rrofclrmre80skfo1', 40000, 'NA', 32, NULL),
(95, 'D101', 'Lutfi R. K, S.Ip., MBA.', 'Lutfi R. K, S.Ip., MBA.', 'D101', '6e2a5d5e0c0963ca11ab81e24d60f77a', 'pengajar', '', '', '2015-07-23', 'L', 'Islam', '08122260269', '', '', 'http://', 'Full-time', 'N', '7t6jpabjp7prichvm2au9tv826', 40000, 'NA', 40, NULL),
(109, 'D102', 'Ratna', 'Ratna', 'D102', '48a3388273e62842ea7b9ca5906507f3', 'pengajar', 'JL. R. E. MARTADINATA N0. 196', 'TASIKMALAYA', '1987-07-12', 'P', 'Islam', '085322475176', 'yuniar_bougenville0787@yahoo.com', '', 'http://', 'Part-time', 'N', '69ooia4c73f2bjhfumas00nor2', 45000, NULL, NULL, NULL),
(111, 'D999', 'Pendidikan', '', '', 'd41d8cd98f00b204e9800998ecf8427e', 'pengajar', '', '', '2015-09-30', 'P', '0', '', '', '', 'http://', 'pendidikan', 'N', 'D999', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `registrasi_siswa`
--

CREATE TABLE IF NOT EXISTS `registrasi_siswa` (
  `id_registrasi` int(9) NOT NULL,
  `nis` varchar(50) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username_login` varchar(50) NOT NULL,
  `password_login` varchar(50) NOT NULL,
  `id_kelas` varchar(5) NOT NULL,
  `jabatan` varchar(200) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `agama` varchar(20) NOT NULL,
  `nama_ayah` varchar(100) NOT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `th_masuk` varchar(4) NOT NULL,
  `email` varchar(50) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `foto` varchar(150) NOT NULL,
  `blokir` enum('Y','N') NOT NULL,
  `id_session` varchar(100) NOT NULL,
  `id_session_soal` varchar(100) NOT NULL,
  `level` varchar(20) NOT NULL DEFAULT 'siswa'
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE IF NOT EXISTS `siswa` (
  `id_siswa` int(9) NOT NULL,
  `nim` varchar(50) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username_login` varchar(50) NOT NULL,
  `password_login` varchar(50) NOT NULL,
  `id_kelas` varchar(5) NOT NULL,
  `jabatan` varchar(200) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `agama` varchar(20) NOT NULL,
  `nama_ayah` varchar(100) NOT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `th_masuk` varchar(4) NOT NULL,
  `email` varchar(50) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `foto` varchar(150) NOT NULL,
  `blokir` enum('Y','N') NOT NULL,
  `id_session` varchar(100) NOT NULL,
  `id_session_soal` varchar(100) NOT NULL,
  `level` varchar(20) NOT NULL DEFAULT 'siswa'
) ENGINE=MyISAM AUTO_INCREMENT=736 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nim`, `nama_lengkap`, `username_login`, `password_login`, `id_kelas`, `jabatan`, `alamat`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `agama`, `nama_ayah`, `nama_ibu`, `th_masuk`, `email`, `no_telp`, `foto`, `blokir`, `id_session`, `id_session_soal`, `level`) VALUES
(734, '2011256', 'Maman Abdurahman', '-', '336d5ebc5436534e61d16e63ddfca327', '14-01', '', 'Cirebon', 'Cirebon', '2015-11-18', 'L', 'Islam', '-', '-', '2016', 'maman@gmail.com', '08965656', 'maman.jpg', 'N', '2011256', '2011256', 'siswa'),
(735, '2011257', 'Ade Fuad', '-', '336d5ebc5436534e61d16e63ddfca327', '14-01', '', '-', 'Malang', '2015-11-18', 'L', 'Islam', '-', '-', '-', 'ade@gmail.com', '08555555', 'ade.jpg', 'N', '2011257', '2011257', 'siswa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_himpunankriteria`
--

CREATE TABLE IF NOT EXISTS `tbl_himpunankriteria` (
  `id_hk` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `keterangan` varchar(15) NOT NULL,
  `nilai` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_himpunankriteria`
--

INSERT INTO `tbl_himpunankriteria` (`id_hk`, `id_kriteria`, `nama`, `keterangan`, `nilai`) VALUES
(1, 3, '1', 'Sangat Baik', 3),
(2, 3, '2', 'Baik', 2),
(3, 3, '3', 'Cukup Baik', 1),
(7, 4, '86-100', 'Sangat Baik', 5),
(8, 4, '76-85', 'Baik', 4),
(9, 4, '66-75', 'Cukup', 3),
(10, 4, '51-65', 'Kurang', 2),
(11, 4, '0-50', 'Sangat Kurang', 1),
(12, 5, '86-100', 'Sangat Baik', 5),
(13, 5, '76-85', 'Baik', 4),
(14, 5, '66-75', 'Cukup Baik', 3),
(15, 5, '51-65', 'Kurang', 2),
(16, 5, '0-50', 'Sangat Kurang', 1),
(17, 6, '86-100', 'Sangat Baik', 5),
(18, 6, '76-85', 'Baik', 4),
(19, 6, '66-75', 'Cukup', 3),
(20, 10, '86-100', 'Sangat Baik', 5),
(21, 10, '76-85', 'Baik', 4),
(22, 10, '66-75', 'Cukup', 3),
(23, 10, '51-65', 'Kurang', 2),
(24, 10, '0-50', 'Sangat Kurang', 1),
(25, 12, '86-100', 'Sangat Baik', 5),
(26, 12, '76-85', 'Baik', 4),
(27, 12, '66-75', 'Cukup', 3),
(28, 12, '51-65', 'Kurang', 2),
(29, 12, '0-50', 'Sangat Kurang', 1),
(30, 6, '51-65', 'Kurang', 2),
(31, 6, '0-50', 'Sangat Kurang', 1),
(32, 11, '86-100', 'Sangat Baik', 5),
(33, 11, '76-85', 'Baik', 4),
(34, 11, '66-75', 'Cukup', 3),
(35, 11, '51-65', 'Kurang', 2),
(36, 11, '0-50', 'Sangat Kurang', 1),
(37, 14, '86-100', 'Sangat Baik', 5),
(38, 14, '76-85', 'Baik', 4),
(39, 14, '60-75', 'Cukup', 3),
(40, 14, '55-60', 'Kurang', 2),
(41, 14, '0-54', 'Sangat Kurang', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_klasifikasi`
--

CREATE TABLE IF NOT EXISTS `tbl_klasifikasi` (
  `id_klasifikasi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_hk` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_klasifikasi`
--

INSERT INTO `tbl_klasifikasi` (`id_klasifikasi`, `id_siswa`, `id_hk`) VALUES
(38, 734, 33),
(39, 734, 1),
(40, 734, 10),
(41, 734, 14),
(42, 734, 19),
(43, 734, 24),
(44, 734, 26),
(45, 734, 37),
(46, 735, 33),
(47, 735, 3),
(48, 735, 9),
(49, 735, 14),
(50, 735, 17),
(51, 735, 22),
(52, 735, 26),
(53, 735, 40);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kriteria`
--

CREATE TABLE IF NOT EXISTS `tbl_kriteria` (
  `id` int(5) NOT NULL,
  `kriteria` varchar(50) NOT NULL,
  `bobot` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_kriteria`
--

INSERT INTO `tbl_kriteria` (`id`, `kriteria`, `bobot`) VALUES
(11, 'Kesenian', 5),
(3, 'Rangking', 3),
(4, 'Pengetahuan Umum', 5),
(5, 'Matematika', 5),
(6, 'Bahasa Indonesia', 5),
(10, 'Bahasa Inggris', 5),
(12, 'Hasta Karya ', 5),
(14, 'Pengetahuan IT', 5);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_analisa`
--
CREATE TABLE IF NOT EXISTS `v_analisa` (
`id_klasifikasi` int(11)
,`id_siswa` int(11)
,`nama_lengkap` varchar(100)
,`id_hk` int(11)
,`id_kriteria` int(11)
,`kriteria` varchar(50)
,`nama` varchar(60)
,`keterangan` varchar(15)
,`nilai` int(11)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_analisa`
--
DROP TABLE IF EXISTS `v_analisa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_analisa` AS select `tbl_klasifikasi`.`id_klasifikasi` AS `id_klasifikasi`,`tbl_klasifikasi`.`id_siswa` AS `id_siswa`,`siswa`.`nama_lengkap` AS `nama_lengkap`,`tbl_klasifikasi`.`id_hk` AS `id_hk`,`tbl_himpunankriteria`.`id_kriteria` AS `id_kriteria`,`tbl_kriteria`.`kriteria` AS `kriteria`,`tbl_himpunankriteria`.`nama` AS `nama`,`tbl_himpunankriteria`.`keterangan` AS `keterangan`,`tbl_himpunankriteria`.`nilai` AS `nilai` from (((`tbl_himpunankriteria` join `tbl_kriteria` on((`tbl_himpunankriteria`.`id_kriteria` = `tbl_kriteria`.`id`))) join `tbl_klasifikasi` on((`tbl_klasifikasi`.`id_hk` = `tbl_himpunankriteria`.`id_hk`))) join `siswa` on((`tbl_klasifikasi`.`id_siswa` = `siswa`.`id_siswa`)));

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kodematkul` (`kodematkul`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id_modul`);

--
-- Indexes for table `pengajar`
--
ALTER TABLE `pengajar`
  ADD PRIMARY KEY (`id_pengajar`);

--
-- Indexes for table `registrasi_siswa`
--
ALTER TABLE `registrasi_siswa`
  ADD PRIMARY KEY (`id_registrasi`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `tbl_himpunankriteria`
--
ALTER TABLE `tbl_himpunankriteria`
  ADD PRIMARY KEY (`id_hk`);

--
-- Indexes for table `tbl_klasifikasi`
--
ALTER TABLE `tbl_klasifikasi`
  ADD PRIMARY KEY (`id_klasifikasi`);

--
-- Indexes for table `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(3) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `modul`
--
ALTER TABLE `modul`
  MODIFY `id_modul` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT for table `pengajar`
--
ALTER TABLE `pengajar`
  MODIFY `id_pengajar` int(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `registrasi_siswa`
--
ALTER TABLE `registrasi_siswa`
  MODIFY `id_registrasi` int(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=736;
--
-- AUTO_INCREMENT for table `tbl_himpunankriteria`
--
ALTER TABLE `tbl_himpunankriteria`
  MODIFY `id_hk` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `tbl_klasifikasi`
--
ALTER TABLE `tbl_klasifikasi`
  MODIFY `id_klasifikasi` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
