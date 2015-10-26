<?php
session_start();
include '../pdo.php';
if(!isset($_SESSION["username"])){
    header("location:../index.php?from=".$_SERVER['REQUEST_URI']);
}
    $exec = $pdo->query("select privileges from guru where id_guru='".$_SESSION["username"]."'");
    foreach($exec as $row){
        if ($row[0]=="guru"){
            header("location:../guru/");
        }
    }
#insert data
if(isset($_POST['tambah'])){
    $nisn=$_POST["NIK"];
    $nama=$_POST["nama"];
    $id_kelas=$_POST["id_kelas"];
    $insql = "insert into guru values(:NIK, :nama, '1234', 'guru')";
    $insert = $pdo->prepare($insql);
    $insert->bindParam(':NIK', $nisn, PDO::PARAM_STR);
    $insert->bindParam(':nama', $nama, PDO::PARAM_STR);
    $insert->execute();
    $insql = "insert into walikelas(id_guru, id_kelas) values(:NIK, :id_kelas)";
    $insert = $pdo->prepare($insql);
    $insert->bindParam(':NIK', $nisn, PDO::PARAM_STR);
    $insert->bindParam(':id_kelas', $id_kelas, PDO::PARAM_INT);
    $insert->execute();
}
#update data
if(isset($_POST['simpan'])){
    $nisn=$_POST["NIK"];
    $nama=$_POST["nama"];
    $id_kelas=$_POST["id_kelas"];
    $id_walikelas=$_POST["id_walikelas"];
    $upsql = "update guru set nama_guru = :nama where id_guru = :NIK";
    $update = $pdo->prepare($upsql);
    $update->bindParam(':NIK', $nisn, PDO::PARAM_STR);
    $update->bindParam(':nama', $nama, PDO::PARAM_STR);
    $update->execute();
    $upsql = "update walikelas set id_kelas = :id_kelas where id_walikelas = :id_walikelas";
    $update = $pdo->prepare($upsql);
    $update->bindParam(':id_kelas', $id_kelas, PDO::PARAM_INT);
    $update->bindParam(':id_walikelas', $id_walikelas, PDO::PARAM_INT);
    $update->execute();
}
#update data
if(isset($_POST['hapus'])){
    $nisn=$_POST["NIK"];
    $delsql = "delete from walikelas where id_guru = :NIK";
    $delete = $pdo->prepare($delsql);
    $delete->bindParam(':NIK', $nisn, PDO::PARAM_STR);
    $delete->execute();
    $delsql = "delete from guru where id_guru = :NIK";
    $delete = $pdo->prepare($delsql);
    $delete->bindParam(':NIK', $nisn, PDO::PARAM_STR);
    $delete->execute();
}

$pageini="Data Guru";

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Data Guru - Penjurusan Siswa SMA Online</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- include CSS dari sononye Broo -->
        <link href="../res/css/normalize.css" rel="stylesheet">
        <link href="../res/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="../res/css/roboto.min.css" rel="stylesheet">
        <link href="../res/css/material-fullpalette.min.css" rel="stylesheet">
        <link href="../res/css/ripples.min.css" rel="stylesheet">
        <link href="../res/css/material-design-iconic-font.min.css" rel="stylesheet">

        <!-- include CSS buatan lu sendiri Broo -->
        <link href="../res/css/sidebar.css" rel="stylesheet">

    </head>
    <body>
        <!-- Sidebarnye bro -->
        <?php include 'navbar.php'; ?>

        <!-- contentnye bro -->
        <div class="wrapper">
          <!-- Sidebar Constructor -->
            <div  id="guru" class="constructor">
                <div class="well well-sm" style="margin-top:5px">
                        <ul class="breadcrumb pull-left" style="margin-bottom:5px; background-color:#fff;">
                            <li><a href="javascript:void(0)">Master</a></li>
                            <li class="active">Data Guru</li>
                        </ul>
                        <div class="input-group text-right">
                                <input type="text" class="search form-control " style="max-width:200px;" placeholder="Cari Guru">
                                <span class="input-group-btn">
                                    <button class="sort btn btn-flat  btn-material-white btn-xs" data-sort="name" type="button" ><i class="mdi-action-search"></i></button>
                                </span>
                        </div>
                </div>


                <div class="panel">
                    <div class="panel-heading text-right">
                        <ul class="nav nav-pills pull-left" style="margin:10px 0 0 0">
                            <li>
                                <a href="javascript:void(0)">
                                    Jumlah Guru
                                    <span class="badge">
                                        <?php
                                            $exec = $pdo->query("select count(id_guru) from guru where privileges<>'admin'");
                                            echo $exec->fetchColumn();
                                        ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <div class="form-group" style="margin:0 0 -5px 0;">
                                    <button class="btn btn-material-indigo" data-toggle="modal" data-target="#myModal">
                                        <i class="zmdi zmdi-account-add"></i> Tambah Guru
                                    </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Wali Kelas</th>
                                    <th class="text-right">Edit</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php
                                    $exec = $pdo->query("select g.id_guru, g.nama_guru, k.nama_kelas, k.id_kelas, w.id_walikelas from guru g, walikelas w, kelas k where g.id_guru=w.id_guru and w.id_kelas=k.id_kelas");
                                    $i=1;
                                    foreach($exec as $row){
                                ?>
                                <tr>
                                    <td ><?php echo $i; ?></td>
                                    <td class="nisn"><?php echo $row[0]; ?></td>
                                    <td class="nama"><?php echo $row[1]; ?></td>
                                    <td class="kelas"><?php echo $row[2]; ?></td>
                                    <td class="text-right">
                                        <button class="btn btn-material-teal-A700 btn-xs <?php echo ($row[3]=="admin" ? "hidden" : ""); ?> " style="margin:-6px 0 -5px 0" data-toggle="modal" data-target="#modalupdate" data-id="<?php echo $row[0]; ?>" data-nama="<?php echo $row[1]; ?>" data-kelas="<?php echo $row[2]; ?>" data-idkelas="<?php echo $row[3]; ?>" data-idwalikelas="<?php echo $row[4]; ?>">
                                            <i class="zmdi zmdi-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-material-red btn-xs <?php echo ($row[3]=="admin" ? "hidden" : ""); ?> " style="margin:-6px 0 -5px 0" data-toggle="modal"  data-target="#modaldelete" data-id="<?php echo $row[0]; ?>" data-nama="<?php echo $row[1]; ?>" data-kelas="<?php echo $row[2]; ?>" data-idkelas="<?php echo $row[3]; ?>" data-idwalikelas="<?php echo $row[4]; ?>">
                                            <i class="zmdi zmdi-edit"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                <?php
                                        $i++;
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header" style="color:#fff;background-color:#3F51B5">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Tambah Data Guru</h4>
              </div>
                <form class="form-horizontal" action="guru.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="NISN" class="col-lg-2 control-label">NIK</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="NISN" placeholder="Nomor Induk Kepegawaian Guru" name="NIK" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama" class="col-lg-2 control-label">Nama Guru</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="nama" placeholder="Nama Guru" name="nama" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-2 control-label">Wali Kelas</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="select" name="id_kelas">
                                    <?php
                                        foreach($pdo->query("select * from kelas") as $row){
                                            echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input class="btn btn-primary btn-material-indigo" type="submit" value="Simpan" name="tambah">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalupdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header"  style="color:#fff;background-color:#00BFA5">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Ubah Data Guru</h4>
              </div>
                <form class="form-horizontal" action="guru.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="NISN" class="col-lg-2 control-label">NIK</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="NISN" placeholder="Nomor Induk Kepegawaian Guru" name="NIK" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama" class="col-lg-2 control-label">Nama</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="nama" placeholder="Nama Guru" name="nama" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kelas" class="col-lg-2 control-label">Wali Kelas</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="kelas" name="id_kelas">
                                    <?php
                                        foreach($pdo->query("select * from kelas") as $row){
                                            echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                        }
                                    ?>
                                </select>
                                <input type="hidden" class="form-control" id="id_walikelas" name="id_walikelas" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input class="btn btn-primary  btn-material-teal-A700" type="submit" value="Simpan" name="simpan">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modaldelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header"  style="color:#fff;background-color:#F44336">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Hapus Data Guru</h4>
              </div>
                <form class="form-horizontal" action="guru.php" method="post">
                    <div class="modal-body">
                        <h3>Apakah Anda yakin akan menghapus data ini?</h3>
                        <div class="form-group has-error">
                            <label for="NISN" class="col-lg-2 control-label">NIK</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="NISN" placeholder="NIK" name="NIK" readonly>
                            </div>
                        </div>
                        <div class="form-group has-error">
                            <label for="nama" class="col-lg-2 control-label">Nama</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="nama" placeholder="Nama Guru" name="nama" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kelas" class="col-lg-2 control-label">Wali Kelas</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="kelas" placeholder="Nama Guru" name="kelas" required>
                                <input type="hidden" class="form-control" id="id_kelas" placeholder="Nama Guru" name="id_kelas" required>
                                <input type="hidden" class="form-control" id="id_walikelas" placeholder="Nama Guru" name="id_walikelas" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input class="btn btn-primary btn-material-red" type="submit" value="Hapus" name="hapus">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
          </div>
        </div>

        <!-- include JSnye Broo -->
        <script src="../res/js/jquery-1.10.2.min.js"></script>
        <script src="../res/js/bootstrap.min.js"></script>
        <script src="../res/js/ripples.min.js"></script>
        <script src="../res/js/material.min.js"></script>
        <script src="../res/js/sidebar.js"></script>
        <script src="../res/js/modernizr.js"></script>
        <script src="../res/js/list.min.js"></script>

        <!-- JS wajib Broo -->
        <script>
            $(document).ready(function() {
                // This command is used to initialize some elements and make them work properly
                $.material.init();
            });
        </script>
        <script>
            $('#modalupdate').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var nisn = button.data('id') // Extract info from data-* attributes
              var nama = button.data('nama') // Extract info from data-* attributes
              var kelas = button.data('kelas') // Extract info from data-* attributes
              var idkelas = button.data('idkelas') // Extract info from data-* attributes
              var idwalikelas = button.data('idwalikelas') // Extract info from data-* attributes
              // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
              // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
              var modal = $(this)
              modal.find('.modal-body #NISN').val(nisn)
              modal.find('.modal-body #nama').val(nama)
              modal.find('.modal-body #id_kelas').val(idkelas)
              modal.find('.modal-body #id_walikelas').val(idwalikelas)
              modal.find('#kelas option').each(function(){
                if($(this).val() == idkelas){
                  $(this).attr('selected', 'selected');
                }
              });
            });
            $('#modaldelete').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var nisn = button.data('id') // Extract info from data-* attributes
              var nama = button.data('nama') // Extract info from data-* attributes
              var kelas = button.data('kelas') // Extract info from data-* attributes
              var idkelas = button.data('idkelas') // Extract info from data-* attributes
              var idwalikelas = button.data('idwalikelas') // Extract info from data-* attributes
              // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
              // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
              var modal = $(this)
              modal.find('.modal-body #NISN').val(nisn)
              modal.find('.modal-body #nama').val(nama)
              modal.find('.modal-body #id_kelas').val(idkelas)
              modal.find('.modal-body #kelas').val(kelas)
              modal.find('.modal-body #id_walikelas').val(idwalikelas)
            });
        </script>
        <script>
            var options = {
              valueNames: [ 'nisn', 'nama', 'kelas' ]
            };

            var userList = new List('guru', options);
        </script>
    </body>
</html>
