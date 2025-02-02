-- Buat tabel pelatihann (perhatikan nama tabel dengan 2 huruf 'n')
CREATE TABLE IF NOT EXISTS pelatihann (
    id_pelatihan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelatihan VARCHAR(255) NOT NULL,
    tgl_pelaksanaan DATE NOT NULL,
    jam_pelaksanaan TIME NOT NULL,
    durasi_pelatihan VARCHAR(50) NOT NULL,
    lok_pelatihan VARCHAR(255) NOT NULL,
    pem_pelatihan VARCHAR(255) NOT NULL,
    status_pelatihan ENUM('terlaksana', 'belum terlaksana', 'tidak terlaksana') DEFAULT 'belum terlaksana'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

