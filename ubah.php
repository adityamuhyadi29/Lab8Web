
<?php
error_reporting(E_ALL);
include_once 'koneksi.php';

if (isset($_POST['submit']))
{
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga_jual = $_POST['harga_jual'];
    $harga_beli = $_POST['harga_beli'];
    $stok = $_POST['stok'];

    $file_gambar = $_FILES['file_gambar'];
    $gambar = null;

    if ($file_gambar['error'] == 0)
    {
        $filename = str_replace(' ', '_', $file_gambar['name']);
        $destination = dirname(__FILE__) . '/gambar/' . $filename;

        if (move_uploaded_file($file_gambar['tmp_name'], $destination))
        {
            $gambar = 'gambar/' . $filename;
        }
    }

    $id = mysqli_real_escape_string($conn, $id);
    $nama = mysqli_real_escape_string($conn, $nama);
    $kategori = mysqli_real_escape_string($conn, $kategori);
    $harga_jual = mysqli_real_escape_string($conn, $harga_jual);
    $harga_beli = mysqli_real_escape_string($conn, $harga_beli);
    $stok = mysqli_real_escape_string($conn, $stok);

    $sql = 'UPDATE data_barang SET ';
    $sql .= "nama = '{$nama}', kategori = '{$kategori}', ";
    $sql .= "harga_jual = '{$harga_jual}', harga_beli = '{$harga_beli}', ";
    $sql .= "stok = '{$stok}' ";

    if (!empty($gambar))
    {
        $gambar = mysqli_real_escape_string($conn, $gambar);
        $sql .= ", gambar = '{$gambar}' ";
    }

    $sql .= "WHERE id_barang = '{$id}'";

    $result = mysqli_query($conn, $sql);

    if ($result)
    {
        header('location: index.php');
        exit;
    }
    else
    {
        die('Error: ' . mysqli_error($conn));
    }
}

// Mengambil ID barang dari URL
if (!isset($_GET['id']))
{
    die('ID barang tidak ditemukan!');
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT * FROM data_barang WHERE id_barang = '{$id}'";
$result = mysqli_query($conn, $sql);

if (!$result)
{
    die('Error: Data tidak tersedia');
}

$data = mysqli_fetch_array($result);

if (!$data)
{
    die('Data barang tidak ditemukan!');
}

function is_select($var, $val) {
    if ($var == $val) return 'selected="selected"';
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet" type="text/css" />
    <title>Ubah Barang</title>
</head>
<body>
<div class="container">
    <h1>Ubah Barang</h1>

    <div class="main">
        <form method="post"
              action="ubah.php?id=<?php echo $data['id_barang']; ?>"
              enctype="multipart/form-data">
            <table>
                <tr>
                    <td><label>Nama Barang</label></td>
                    <td>
                        <input type="text" name="nama"
                        value="<?php echo htmlspecialchars($data['nama']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td><label>Kategori</label></td>
                    <td>
                        <select name="kategori">
                            <option <?php echo is_select('Komputer', $data['kategori']); ?> value="Komputer">Komputer</option>
                            <option <?php echo is_select('Elektronik', $data['kategori']); ?> value="Elektronik">Elektronik</option>
                            <option <?php echo is_select('Hand Phone', $data['kategori']); ?> value="Hand Phone">Hand Phone</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label>Harga Jual</label></td>
                    <td>
                        <input type="text" name="harga_jual"
                        value="<?php echo htmlspecialchars($data['harga_jual']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td><label>Harga Beli</label></td>
                    <td>
                        <input type="text" name="harga_beli"
                        value="<?php echo htmlspecialchars($data['harga_beli']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td><label>Stok</label></td>
                    <td>
                        <input type="text" name="stok"
                        value="<?php echo htmlspecialchars($data['stok']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td><label>File Gambar</label></td>
                    <td>
                        <input type="file" name="file_gambar" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="id"
                        value="<?php echo $data['id_barang']; ?>" />

                        <input type="submit" name="submit" value="Simpan" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>
