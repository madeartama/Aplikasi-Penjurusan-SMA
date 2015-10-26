<?php
session_start();
include '../pdo.php';
if(!isset($_SESSION["username"])){
    header("location:../index.php?from=".$_SERVER['REQUEST_URI']);
}
    $exec = $pdo->query("select privileges from guru where id_guru='".$_SESSION["username"]."'");
    foreach($exec as $row){
        if ($row[0]=="admin"){
            header("location:../tu/");
        }
    }

$pageini="Nilai Pelajaran";

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $pageini; ?> - Penjurusan Siswa SMA Online</title>

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
            <div id="murid" class="constructor">
                <div class="well well-sm" style="margin-top:5px">
                        <ul class="breadcrumb pull-left" style="margin-bottom:5px; background-color:#fff;">
                            <li><a href="javascript:void(0)">Daftar Murid Kelas</a></li>
                            <li class="active">Nilai Pelajaran</li>
                        </ul>
                        <div class="input-group text-right">
                                <input type="text" class="search form-control" style="max-width:200px;" placeholder="Cari Murid">
                                <span class="input-group-btn">
                                    <button class="btn btn-flat  btn-material-white btn-xs" type="button" ><i class="mdi-action-search"></i></button>
                                </span>
                        </div>
                </div>


                <div class="panel">
                    <div class="panel-heading text-right">
                        <ul class="nav nav-pills" style="margin:10px 0 0 0">
                            <li>
                                <a href="javascript:void(0)">
                                    Jumlah Murid
                                    <span class="badge">
                                        <?php
                                            $exec = $pdo->query("select count(id_murid) from murid");
                                            echo $exec->fetchColumn();
                                        ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <div class="form-group" style="margin:0 0 -5px 0;">
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Induk</th>
                                    <th>Nama</th>
                                    <th class="text-right">Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php
                                    $exec = $pdo->query("select m.id_murid, m.nama_murid from murid m, walikelas w where m.id_walikelas=w.id_walikelas and w.id_guru='".$_SESSION["username"]."'");
                                    $i=1;
                                    foreach($exec as $row){
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td class="nisn"><?php echo $row[0]; ?></td>
                                    <td class="nama"><?php echo $row[1]; ?></td>
                                    <td class="text-right">
                                        <?php
                                            if ($pdo->query("select count(id_nilai) from nilai_mapel where id_murid='".$row[0]."'")->fetchColumn() > 0) {
                                        ?>
                                        <a href="insert.php?nisn=<?php echo $row[0]; ?>" class="btn btn-material-indigo btn-xs" style="margin:-6px 0 -5px 0">
                                            <i class="zmdi zmdi-edit"></i> Update Nilai
                                        </a>
                                        <?php
                                            }else{
                                        ?>
                                        <a href="insert.php?nisn=<?php echo $row[0]; ?>" class="btn btn-material-teal-A700 btn-xs" style="margin:-6px 0 -5px 0">
                                            <i class="zmdi zmdi-edit"></i> Insert Nilai&nbsp;
                                        </a>
                                        <?php
                                            }
                                        ?>
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

        <!-- Modal Nye broo -->
        <div class="modal fade" id="modalupdate" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"  style="color:#fff;background-color:#00BFA5">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <iframe frame style="width:100%;height:500px; margin:-20px -50px -15px -20px; border:0" id="iframenya" src="http://www.w3schools.com"></iframe>
                </div>
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
              var nisn = button.data('nisn') // Extract info from data-* attributes
              var nama = button.data('nama') // Extract info from data-* attributesD
              // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
              // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
              var modal = $(this)
              modal.find('.modal-header #myModalLabel').text('Nilai Murid | '+nisn+' - '+nama)
              modal.find('.modal-body #iframenya').attr('src', 'insert.php?nisn='+nisn)
            })
        </script>
        <script>
            var options = {
              valueNames: [ 'nisn', 'nama', 'kelas' ]
            };

            var userList = new List('murid', options);
        </script>
    </body>
</html>
