<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai Pemrograman Internet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #007BFF;
            /* Warna biru */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #007BFF;
            /* Warna biru */
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
            /* Warna biru gelap saat hover */
        }

        .result {
            background-color: #e7f3ff;
            /* Background biru muda */
            border: 1px solid #007BFF;
            /* Border biru */
            border-radius: 5px;
            padding: 10px;
            margin-top: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
    <script>
        function validateForm() {
            var nilai = document.forms["nilaiForm"]["nilai"].value;
            if (nilai < 0 || nilai > 100) {
                alert("Nilai harus antara 0 dan 100!");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>

    <div class="container">
        <h2>Input Nilai Mata Kuliah Pemrograman Internet</h2>
        <form name="nilaiForm" action="" method="post" onsubmit="return validateForm()">
            Nama: <input type="text" name="nama" required><br>
            NIM: <input type="text" name="nim" required><br>
            Nilai Angka (0-100): <input type="number" name="nilai" min="0" max="100" required><br>
            <input type="submit" name="submit" value="Submit">
        </form>

        <?php
        // Koneksi ke database
        $servername = "localhost"; // Ganti sesuai dengan konfigurasi server Anda
        $username = "root";        // Ganti dengan username MySQL Anda
        $password = "";            // Ganti dengan password MySQL Anda
        $dbname = "mahasiswa_db";  // Nama database yang telah dibuat

        // Buat koneksi
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Cek koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        if (isset($_POST['submit'])) {
            $nama = $_POST['nama'];
            $nim = $_POST['nim'];
            $nilai = $_POST['nilai'];

            // Validasi nilai di sisi server
            if ($nilai < 0 || $nilai > 100) {
                echo "<p class='error'>Nilai harus antara 0 dan 100!</p>";
            } else {
                // Fungsi untuk mengonversi nilai angka ke nilai huruf
                function konversiNilaiHuruf($nilai)
                {
                    if ($nilai >= 85) {
                        return "A";
                    } elseif ($nilai >= 80) {
                        return "B+";
                    } elseif ($nilai >= 75) {
                        return "B";
                    } elseif ($nilai >= 70) {
                        return "C+";
                    } elseif ($nilai >= 65) {
                        return "C";
                    } elseif ($nilai >= 50) {
                        return "D";
                    } else {
                        return "E";
                    }
                }

                // Panggil fungsi konversi
                $nilaiHuruf = konversiNilaiHuruf($nilai);

                // Simpan data ke database
                $stmt = $conn->prepare("INSERT INTO nilai_mahasiswa (nama, nim, nilai_angka, nilai_huruf) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $nama, $nim, $nilai, $nilaiHuruf);

                if ($stmt->execute()) {
                    echo "<div class='result'>";
                    echo "<h3>Data berhasil disimpan!</h3>";
                    echo "Nama: " . htmlspecialchars($nama) . "<br>";
                    echo "NIM: " . htmlspecialchars($nim) . "<br>";
                    echo "Nilai Angka: " . $nilai . "<br>";
                    echo "Nilai Huruf: " . $nilaiHuruf . "<br>";
                    echo "</div>";
                } else {
                    echo "<p class='error'>Terjadi kesalahan saat menyimpan data: " . $stmt->error . "</p>";
                }

                $stmt->close();
            }
        }

        // Tutup koneksi
        $conn->close();
        ?>
    </div>

</body>

</html>