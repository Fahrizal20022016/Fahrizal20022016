<?php
session_start();
//Koneksi Database 
$conn = mysqli_connect("localhost","root","","stockbarang");

// register

if(isset($_POST['register'])){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password=$_POST['password'];

    $insert = mysqli_query($conn,"INSERT INTO login (firstname,lastname,email,password) values ('$fname','$lname','$email','$password')");
    if($insert){
        header('location:login.php');
    }
    else{
        echo'
        <script>
            alert("Register gagal");
            window.location.href="register.php";
        </script> ';
    }
}

// tambah barang
if(isset($_POST['addnewbarang'])){
    $namabarang=$_POST['namabarang'];
    $deskripsi=$_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "insert into stock (namabarang, deskripsi, stock) values ('$namabarang', '$deskripsi','$stock')");
    if($addtotable){
        header('location:index.php');
    }
    else{
        echo 'Gagal';
        header('location:index.php');
    }
}

// tambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];
    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganqty = $stocksekarang+$qty;

    $addtomasuk = mysqli_query($conn, "insert into masuk (idbarang, keterangan, qty) values('$barangnya', '$penerima', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganqty'where idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    }
    else{
        echo 'Gagal';
        header('location:masuk.php');
    }
}

// tambah barang keluar
if(isset($_POST['barangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];
    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganqty = $stocksekarang-$qty;

    $addtokeluar = mysqli_query($conn, "insert into keluar (idbarang, penerima, qty) values('$barangnya', '$penerima', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganqty'where idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:keluar.php');
    }
    else{
        echo 'Gagal';
        header('location:keluar.php');
    }
}

// Update barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "update stock set namabarang='$namabarang',deskripsi = '$deskripsi' where idbarang = '$idb'");
    if($update){
        header('location:index.php');
    }
    else{
        echo 'Gagal';
        header('location:index.php');
    }
}

// Delete barang
if(isset($_POST['deletebarang'])){
    $idb = $_POST['idb'];
    $hapus = mysqli_query($conn,"delete from stock where idbarang='$idb'");
    if($hapus){
        header('location:index.php');
    }
    else{
        echo 'Gagal';
        header('location:index.php');
    }
}

// Update barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];
    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg + $selisih;
        $kurangstocknya = mysqli_query($conn,"update stock set stock='$kurangin' where idbarang='$idb'");   
        $updatenya = mysqli_query($conn,"update masuk set qty='$qty',keterangan='$deskripsi' where idmasuk='$idm'");
        if($kurangstocknya&&$updatenya){
            header('location:masuk.php');
        }
        else{
            echo 'Gagal';
            header('location:masuk.php');
        }
    }
    else{
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg - $selisih;
        $kurangstocknya = mysqli_query($conn,"update stock set stock='$kurangin' where idbarang='$idb'");   
        $updatenya = mysqli_query($conn,"update masuk set qty='$qty',keterangan='$deskripsi' where idmasuk='$idm'");
        if($kurangstocknya&&$updatenya){
            header('location:masuk.php');
        }
        else{
            echo 'Gagal';
            header('location:masuk.php');
        }
    }

}

// Delete barang masuk
if(isset($_POST['deletebarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];
    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];
    $selisih = $stok+$qty;
    $update = mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from masuk where idmasuk='$idm'");
    if($kurangstocknya&&$updatenya){
        header('location:masuk.php');
    }
    else{
        echo 'Gagal';
        header('location:masuk.php');
    }

}

// Update barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];
    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg - $selisih;
        $kurangstocknya = mysqli_query($conn,"update stock set stock='$kurangin' where idbarang='$idb'");   
        $updatenya = mysqli_query($conn,"update keluar set qty='$qty',penerima='$penerima' where idkeluar='$idk'");
        if($kurangstocknya&&$updatenya){
            header('location:keluar.php');
        }
        else{
            echo 'Gagal';
            header('location:keluar.php');
        }
    }
    else{
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg + $selisih;
        $kurangstocknya = mysqli_query($conn,"update stock set stock='$kurangin' where idbarang='$idb'");   
        $updatenya = mysqli_query($conn,"update keluar set qty='$qty',penerima='$penerima' where idkeluar='$idk'");
        if($kurangstocknya&&$updatenya){
            header('location:keluar.php');
        }
        else{
            echo 'Gagal';
            header('location:keluar.php');
        }
    }

}

// Delete barang keluar
if(isset($_POST['deletebarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];
    
    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];
    $selisih = $stok+$qty;
    $update = mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from keluar where idkeluar='$idk'");
    if($update&&$hapusdata){
        header('location:keluar.php');
    }
    else{
        echo 'Gagal';
        header('location:keluar.php');
    }

}

?>