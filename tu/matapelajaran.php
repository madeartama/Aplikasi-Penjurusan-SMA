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
    $nama=$_POST["nama"];
    $insql = "insert into mata_pelajaran(nama_mapel) values(:nama)";
    $insert = $pdo->prepare($insql);
    $insert->bindParam(':nama', $nama, PDO::PARAM_STR);
    $insert->execute();
}
#update data
if(isset($_POST['simpan'])){
    $nama=$_POST["nama"];
    $insql = "update mata_pelajaran set nama_mapel=:nama where id_mapel=:id_mapel";
    $insert = $pdo->prepare($insql);
    $insert->bindParam(':id_mapel', $id_mapel, PDO::PARAM_INT);
    $insert->bindParam(':nama', $nama, PDO::PARAM_STR);
    $insert->execute();
}
#update data
if(isset($_POST['hapus'])){
    $id_mapel=$_POST["id_mapel"];
    $delsql = "delete from mata_pelajaran where id_mapel = :id_mapel";
    $delete = $pdo->prepare($delsql);
    $delete->bindParam(':id_mapel', $id_mapel, PDO::PARAM_INT);
    $delete->execute();
}

$pageini="Mata Pelajaran";

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Mata Pelajaran - Penjurusan Siswa SMA Online</title>

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
            <div id="mapel" class="constructor">
                <div class="well well-sm" style="margin-top:5px">
                        <ul class="breadcrumb pull-left" style="margin-bottom:5px; background-color:#fff;">
                            <li><a href="javascript:void(0)">Master</a></li>
                            <li class="active">Mata Pelajaran</li>
                        </ul>
                        <div class="input-group text-right">
                                <input type="text" class="search form-control" style="max-width:200px" placeholder="Cari Mata pelajaran">
                                <span class="input-group-btn">
                                    <button class="short btn btn-flat  btn-material-white btn-xs" type="button" ><i class="mdi-action-search"></i></button>
                                </span>
                        </div>
                </div>


                <div class="panel">
                    <div class="panel-heading text-right">
                        <ul class="nav nav-pills pull-left" style="margin:10px 0 0 0">
                            <li>
                                <a href="javascript:void(0)">
                                    Jumlah Mata Pelajaran
                                    <span class="badge">
                                        <?php
                                            $exec = $pdo->query("select count(id_mapel) from mata_pelajaran");
                                            echo $exec->fetchColumn();
                                        ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <div class="form-group" style="margin:0 0 -5px 0;">
                                    <button class="btn btn-material-indigo" data-toggle="modal" data-target="#myModal">
                                        <i class="zmdi zmdi-account-add"></i> Mata Pelajaran Baru
                                    </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mata Pelajaran</th>
                                    <th class="text-right">Edit</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php
                                    $exec = $pdo->query("select * from mata_pelajaran");
                                    $i=1;
                                    foreach($exec as $row){
                                ?>
                                <tr>
                                    <td class="id"><?php echo $i; ?></td>
                                    <td class="nama"><?php echo $row[1]; ?></td>
                                    <td class="text-right">
                                        <button class="btn btn-material-teal-A700 btn-xs" style="margin:-6px 0 -5px 0" data-toggle="modal" data-target="#modalupdate" data-id="<?php echo $row[0]; ?>" data-nama="<?php echo $row[1]; ?>">
                                            <i class="zmdi zmdi-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-material-red btn-xs" style="margin:-6px 0 -5px 0" data-toggle="modal"  data-target="#modaldelete" data-id="<?php echo $row[0]; ?>" data-nama="<?php echo $row[1]; ?>">
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
                <h4 class="modal-title" id="myModalLabel">Mata Pelajaran Baru</h4>
              </div>
                <form class="form-horizontal" action="matapelajaran.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="col-lg-2 control-label">Nama</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="nama" placeholder="Nama Mata Pelajaran" name="nama" required>
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
                <h4 class="modal-title" id="myModalLabel">Ubah Mata Pelajaran</h4>
              </div>
                <form class="form-horizontal" action="matapelajaran.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="col-lg-2 control-label">Nama</label>
                            <div class="col-lg-10">
                                <input type="hidden" class="form-control" id="id" placeholder="Nama Mata Pelajaran" name="id" required>
                                <input type="text" class="form-control" id="nama" placeholder="Nama Mata Pelajaran" name="nama" required>
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
                <form class="form-horizontal" action="matapelajaran.php" method="post">
                    <div class="modal-body">
                        <h3>Apakah Anda yakin akan menghapus data ini?</h3>
                        <div class="form-group has-error">
                            <label for="nama" class="col-lg-2 control-label">Nama</label>
                            <div class="col-lg-10">
                                <input type="hidden" class="form-control" id="id" placeholder="Nama Guru" name="id" readonly>
                                <input type="text" class="form-control" id="nama" placeholder="Nama Guru" name="nama" readonly>
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
              // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
              // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
              var modal = $(this)
              modal.find('.modal-body #id').val(nisn)
              modal.find('.modal-body #nama').val(nama)
            })
            $('#modaldelete').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var nisn = button.data('id') // Extract info from data-* attributes
              var nama = button.data('nama') // Extract info from data-* attributes
              // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
              // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
              var modal = $(this)
              modal.find('.modal-body #id').val(nisn)
              modal.find('.modal-body #nama').val(nama)
            })
        </script>
        <script>
            var options = {
              valueNames: [ 'nama', 'id' ]
            };

            var userList = new List('mapel', options);
        </script>
    </body>
</html>
