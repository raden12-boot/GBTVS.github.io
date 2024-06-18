<?php

$conn = mysqli_connect("localhost", "root", "", "projekbesar");

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambah($data) {
    global $conn;

    $nama = htmlspecialchars($data["nama"]);
    $harga = htmlspecialchars($data["harga"]);
    $Stok = htmlspecialchars($data["Stok"]);

    // Upload gambar dulu
    $gambar = upload();
    if ($gambar === false) {
        return false;
    }

    // Query insert data
    $query = "INSERT INTO paribill VALUES ('', '$nama', '$harga', '$gambar', '$Stok')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function upload() {
    // Implementasi fungsi upload gambar
    // Contoh sederhana:
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Cek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
        return false;
    }

    // Cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'HEIF'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('Yang Anda upload bukan gambar!');</script>";
        return false;
    }

    // Cek jika ukurannya terlalu besar
    if ($ukuranFile > 10000) {
        echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
        return false;
    }

    // Lolos pengecekan, gambar siap diupload
    // Generate nama gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;
}

function hapus($id) {
    global $conn;

    mysqli_query($conn, "DELETE FROM paribill WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function ubah($data) {
    global $conn;

    $id = $data["id"];
    $nama = htmlspecialchars($data["nama"]);
    $harga = htmlspecialchars($data["harga"]);
    $Stok = htmlspecialchars($data["Stok"]);
    $gambar = htmlspecialchars($data["gambar"]);

    $query = "UPDATE paribill SET
                nama = '$nama',
                harga = '$harga',
                gambar = '$gambar',
                Stok = '$Stok'
              WHERE id = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function cari($keyword) {
    $query = "SELECT * FROM paribill
              WHERE
              nama LIKE '%$keyword%' OR
              harga LIKE '%$keyword%' OR
              Stok LIKE '%$keyword%'";
    return query($query);
}

?>
