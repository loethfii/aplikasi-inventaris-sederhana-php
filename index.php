<?php
         //Koneksi database
         $server = "localhost";
         $username = "root";
         $password = "root";  
         $db_name = "dbcrud2022";

         //cek koneksi

         $koneksi = mysqli_connect($server,$username,$password,$db_name) or die(mysqli_error($koneksi));

         //kode otomatis
         $q = mysqli_query($koneksi, "SELECT kode FROM tbarang ORDER BY kode desc limit 1");
         $datax = mysqli_fetch_array($q);
         if($datax){
              $no_terakhir = substr($datax['kode'], -3 );
              $no = $no_terakhir + 1;

              if($no > 0 and $no < 10){
                $kode = "00".$no;
              }elseif($no > 10 and $no < 100){
                $kode = "0". $no;
              }else if($no > 100){
                $kode = $no;
              }
         }else{
          $kode = '001';
         }

         $tahun = date('Y');
         $vkode = "INV-" . $tahun ."-".$kode;




        //Jika tombol diklik
        if(isset($_POST["bsimpan"])){
              //pengujian apakah data akan diedit atau disimpan baru
              if(isset($_GET['hal']) == "edit"){
                  //data akan di edit 
                  $edit  = mysqli_query($koneksi, "UPDATE tbarang SET
                                                                                      nama = '$_POST[tnama]',
                                                                                      asal = '$_POST[asal_barang]',
                                                                                      jumlah = '$_POST[jumlah_barang]',
                                                                                      satuan = '$_POST[satuan]',
                                                                                      tanggal_diterima = '$_POST[tgl_diterima]'
                                                                          WHERE id_barang = '$_GET[id] '
                                                                      ");
                      //jika data pengujian
                  if($edit){
                    echo "
                    <script>
                            alert('Edit Data Sukses');
                            document.location = 'index.php';
                    </script>";
                  }else {
                    echo "
                    <script>
                            alert('Edit Gagal');
                            document.location = 'index.php';
                    </script>";
                  }  
              }else {
                //Data akan disiman baru
                    $insert_table = "INSERT INTO tbarang (kode,nama,asal,jumlah,satuan,tanggal_diterima)
                                              VALUES (' $vkode',
                                              '$_POST[tnama]', 
                                              '$_POST[asal_barang]',
                                              '$_POST[jumlah_barang]',
                                              '$_POST[satuan]',
                                              '$_POST[tgl_diterima]' )" ;
                    $simpan = mysqli_query($koneksi, $insert_table);

                    //jika data pengujian
                    if($simpan === true){
                      echo "
                      <script>
                              alert('Simpan Data Sukses');
                              document.location = 'index.php';
                      </script>";
                    }else {
                      echo "
                      <script>
                              alert('Simpan Gagal');
                              document.location = 'index.php';
                      </script>";
                    }  
              }
              
        }

        //deklarasi variable data yang akan di edit
        
        $vnama = "";
        $vasal = "";
        $vjumlah = "";
        $vsatuan = "";
        $vtanggal_diterima = "";

    

        // tampilkan data siap edit  

        if(isset($_GET['hal'])){
          //pengujian jika edit data
          if($_GET['hal'] === "edit"){
              //tampilkan data yang akan di edit
              $tampil = mysqli_query($koneksi, "SELECT * FROM tbarang WHERE id_barang = '$_GET[id]'");
              $data = mysqli_fetch_array($tampil);
              if($data){
                //JIka data ditemukan maka  data ditampung ke dalam variabel
                $vkode = $data['kode'];
                $vnama = $data['nama'];
                $vasal = $data['asal'];
                $vjumlah = $data['jumlah'];
                $vsatuan = $data['satuan'];
                $vtanggal_diterima = $data['tanggal_diterima'];
              }
            }else if ($_GET['hal'] === "hapus"){
                 $hapus_data = mysqli_query($koneksi, " DELETE FROM tbarang where id_barang = '$_GET[id]'
                 ");
                //  Cek Hapus data konfirmasi
                 if($hapus_data === true){
                  echo "
                  <script>
                          alert('Hapus Data Sukses');
                          document.location = 'index.php';
                  </script>";
                }else {
                  echo "
                  <script>
                          alert('Hapus Gagal');
                          document.location = 'index.php';
                  </script>";
                }  
            }
        };


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CRUD Belajar</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css"
    />
    <style>
      .tebal {
        font-weight: bold;
      }
    </style>
  </head>
  <body>
    <!-- start con -->
    <div class="container">
      <h3 class="text-center">Data inventaris</h3>

      <div class="row">
        <div class="col-md-8 mx-auto">
          <div class="card">
            <div class="card-header bg-info text-light tebal">
              Form input barang
            </div>
            <div class="card-body">
              <!-- card body -->
              <form method="POST">
                <!-- Kode barang name :  tkode-->
                <div class="mb-3">
                  <label class="form-label">Kode Barang</label>
                  <input
                    type="text"
                    <?= empty($vkode) ?  "e" 
                    :  "disabled" ?>
                    value="<?= $vkode  ?>"
                    class="form-control"
                    placeholder="Masukkan Kode Barang"
                    name="tkode"
                    required
                  />
                </div>

                <!-- Nama Barang  name : tnama-->
                <div class="mb-3">
                  <label class="form-label">Nama Barang</label>
                  <input
                    type="text"
                    value="<?=$vnama ?>"
                    class="form-control"
                    placeholder="Masukkan Nama Barang"
                    name="tnama"
                    required
                  />
                </div>

                <!-- Asal Barang  name : asal_barang-->
                <div class="mb-3">
                  <label class="form-label">Asal Barang</label>
                  <select
                    
                    class="form-select form-select-md mb-3"
                    name="asal_barang"
                    required
                  >
                    <option value="<?= $vasal ?>"><?= ($vasal) ? $vasal : "Pilih Opsi" ?></option>
                    <option value="Pembelian">Pembelian</option>
                    <option value="Hibah">Hibah</option>
                    <option value="Bantuan">Bantuan</option>
                    <option value="Sumbangan">Sumbangan</option>
                  </select>
                </div>

                <div class="row">
                  <div class="col">
                    <!-- jumlah barang name : jumlah_barang-->
                    <div class="mb-3">
                      <label class="form-label">Jumlah Barang</label>
                      <input
                        type="number"
                        value="<?=$vjumlah ?>"
                        class="form-control"
                        placeholder="Masukkan Nama Barang"
                        name="jumlah_barang"
                        required
                      />
                    </div>
                  </div>
                  <div class="col">
                    <!-- Satuan Name : satuan -->
                    <div class="mb-3">
                      <label class="form-label">Satuan</label>
                      <select
                        class="form-select form-select-md mb-3"
                        
                        name="satuan"
                        required
                      >
                        <option value="<?=$vsatuan ?>"><?=$vsatuan ?></option>
                        <option value="Unit">Unit</option>
                        <option value="Kotak">Kotak</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Pak">Pak</option>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <!-- Tanggal diterima : tgl_diterima -->
                    <div class="mb-3">
                      <label class="form-label">Tanggal Diterima</label>
                      <input
                        type="date"
                        value="<?= $vtanggal_diterima ?>"
                        class="form-control"
                        placeholder="Masukkan Nama Barang"
                        name="tgl_diterima"
                        required
                      />
                    </div>
                  </div>
                </div>
                <div class="text-center">
                  <hr />
                  <button class="btn btn-primary" name="bsimpan" type="submit">
                    <i class="bi bi-save"></i>
                    Simpan
                  </button>
                  <button class="btn btn-danger" name="bkosongkan" type="reset">
                    <i class="bi bi-backspace"></i>
                    Kosongkan
                  </button>
                </div>
                <!-- end card body -->
              </form>
            </div>
            <div class="card-footer text-muted bg-info text-white">
              &copy; Luthfi
            </div>
          </div>
        </div>
      </div>

      <!-- Data Barang cuyy -->
      <div class="row mt-5">
        <div class="col-md-10 mx-auto">
          <div class="card">
            <div class="card-header bg-info text-light tebal">Data barang</div>
            <div class="card-body">
              <!-- card body -->
              <div class="col-md-6 mx-auto">
                <form method="POST">
                  <div class="input-group mb-3">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Masukkan Kata Kunci Kode, Nama"
                      name="cari"
                      value="<?= isset($_POST['cari']) ? $_POST['cari'] : '' ?>"
                      id=""
                    />
                    <button class="btn btn-primary" name="bcari" type="submit">
                      <i class="bi bi-search"></i>
                      Cari
                    </button>
            
                    <button class="btn btn-danger" name="breset" type="reset" onclick="clearInput()">
                      <i class="bi bi-backspace"></i>
                      Reset
                    </button>

                   
                  </div>
                </form>
              </div>
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Asal Barang</th>
                    <th>Jumlah</th>
                    <!-- <th>Satuan</th> -->
                    <th>Tanggal Diterima</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                            //Pencarian data
                            //jika tombol cari di klik
                           if(isset($_POST['bcari'])){
                                  //tampilkan data yang dicari
                                  $keyword =  $_POST['cari'];
                                  $q = "SELECT * FROM  tbarang WHERE kode like '%$keyword%' or nama like '%$keyword%' ORDER BY id_barang DESC"; 
                           }elseif(isset($_POST['bcari']) == null){
                                  $q = "SELECT * FROM tbarang ORDER BY  id_barang desc";
                           }

                            //Menampilkan data
                            $up = 1;
                            // $panggil = "SELECT * FROM tbarang ORDER BY  id_barang desc";
                            $tampil = mysqli_query($koneksi, $q);
                    if(mysqli_num_rows($tampil) > 0) {
                            while($data = mysqli_fetch_array($tampil)) :
                  ?>
                  <tr>
                    <td><?php echo $up++ ?></td>
                    <td><?php echo $data["kode"] ?></td>
                    <td><?php echo $data["nama"] ?></td>
                    <td><?php echo $data["asal"] ?></td>
                    <td><?php echo $data["jumlah"] . " " . $data["satuan"] ?></td>
                    <td><?php echo $data["tanggal_diterima"] ?></td>
                    <td>
                      <a href="index.php?hal=edit&id=<?=$data['id_barang']?>" 
                        class="btn btn-warning"
                      ><i class="bi bi-scissors"></i>Edit</a
                      >
                      <a href="index.php?hal=hapus&id=<?=$data['id_barang']?>" 
                      class="btn btn-danger"
                      onclick="return confirm('Apakah anda yakin hapus')"
                      ><i class="bi bi-trash2"></i>Hapus</a
                      >
                    </td>
                  </tr>
                  <?php endwhile; ?>
                  <?php } else {
                    echo "<tr>
                    <td colspan='7'>Data yang dicari gk ada kemungkinan kata kunci salah</td>
                  </tr>";
                  } ?>
                
                  
                </tbody>
               
              </table>
              <tr>
                <td ></td>
              </tr>

              <!-- end card body -->
            </div>
            <div class="card-footer text-muted">&copy; Luthfi</div>
          </div>
        </div>
      </div>
    </div>
    <!-- end container -->

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
