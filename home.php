<?php

require "dbBroker.php";
require "model/prijava.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$podaci = Prijava::getAll($conn);
if (!$podaci) {
    echo "Nastala je greška pri preuzimanju podataka";
    die();
}
if ($podaci->num_rows == 0) {
    echo "Nema prijava na kolokvijume";
    die();
} else {

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <link rel="shortcut icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/home.css">
    <title>FON: Prijava kolokvijuma</title>

</head>

<body>


    <div class="jumbotron" style="color: black;">
        <h1>Fakultet organizacionih nauka</h1>
    </div>

    <div class="row" style="background-color: rgba(225, 225, 208, 0.5);">
        <div class="col-md-4">
            <button id="btn" class="btn btn-info btn-block" style="background-color: teal !important; border: 1px solid white; "> Prikazi kolokvijum</button>
        </div>
        <div class="col-md-4">
            <button id="btn-dodaj" type="button" class="btn btn-success btn-block" style="background-color: teal; border: 1px solid white;" data-toggle="modal" data-target="#myModal"> Zakazi kolokvijum</button>

        </div>
        <div class="col-md-4">
            <button id="btn-pretraga" class="btn btn-warning btn-block" style="background-color:  teal; border: 1px solid white;"> Pretrazi kolokvijum</button>
            <input type="text" id="myInput" onkeyup="funkcijaZaPretragu()" placeholder="Pretrazi kolokvijume po predmetu" hidden>
        </div>
    </div>

    <div id="pregled" class="panel panel-success" style="margin-top: 1%;">

        <div class="panel-body">
            <table id="myTable" class="table table-hover table-striped" style="color: black; background-color: grey;">
                <thead class="thead">
                    <tr>
                        <th scope="col">Predmet</th>
                        <th scope="col">Katedra</th>
                        <th scope="col">Sala</th>
                        <th scope="col">Datum Kolokvijuma</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($red = $podaci->fetch_array()) :
                    ?>
                        <tr>
                            <td><?php echo $red["predmet"] ?></td>
                            <td><?php echo $red["katedra"] ?></td>
                            <td><?php echo $red["sala"] ?></td>
                            <td><?php echo $red["datum"] ?></td>
                            <td>
                                <label class="custom-radio-btn">
                                    <input type="radio" name="checked-donut" value=<?php echo $red["id"] ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </td>

                        </tr>
                <?php
                    endwhile;
                } #zatvaranje elsa otvorenog na liniji 21
                ?>

                </tbody>
            </table>
            <div class="row">
                <div class="col-md-1" style="text-align: right">
                    <button id="btn-izmeni" class="btn btn-warning" data-toggle="modal" data-target="#izmeniModal">Izmeni</button>

                </div>

                <div class="col-md-12" style="text-align: right">
                    <button id="btn-obrisi" formmethod="post" class="btn btn-danger" style="background-color: red; border: 1px solid white;">Obrisi</button>
                </div>

                <div class="col-md-2" style="text-align: right;">
                    <button id="btn-sortiraj" class="btn btn-normal" onclick="sortTable()">Sortiraj</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- zakazi modal -->
            <!--Sadrzaj modala-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container prijava-form">
                        <form action="#" method="post" id="dodajForm">
                            <h3 style="color: black; text-align: center">Zakazi kolokvijum</h3>
                            <div class="row">
                                <div class="col-md-11 ">
                                    <div class="form-group">
                                        <label for="">Predmet</label>
                                        <input type="text" style="border: 1px solid black" name="predmet" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="">Katedra</label>
                                        <input type="text" style="border: 1px solid black" name="katedra" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="sala">Sala</label>
                                        <input type="sala" style="border: 1px solid black" name="sala" class="form-control" />
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Datum</label>
                                            <input type="date" style="border: 1px solid black" name="datum" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button id="btnDodaj" type="submit" class="btn btn-success btn-block" tyle="background-color: orange; border: 1px solid black;">Zakazi</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>



    </div>
    <!-- Modal -->
    <div class="modal fade" id="izmeniModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal sadrzaj-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container prijava-form">
                        <form action="#" method="post" id="izmeniForm">
                            <h3 style="color: black">Izmeni kolokvijum</h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input id="id" type="text" name="id" class="form-control" placeholder="Id *" value="" readonly />
                                    </div>
                                    <div class="form-group">
                                        <input id="predmet" type="text" name="predmet" class="form-control" placeholder="Predmet*" value="" />
                                    </div>
                                    <div class="form-group">
                                        <input id="katedra" type="text" name="katedra" class="form-control" placeholder="Katedra *" value="" />
                                    </div>
                                    <div class="form-group">
                                        <input id="sala" type="text" name="sala" class="form-control" placeholder="Sala *" value="" />
                                    </div>
                                    <div class="form-group">
                                        <input id="datum" type="date" name="datum" class="form-control" placeholder="Datum *" value="" />
                                    </div>
                                    <div class="form-group">
                                        <button id="btnIzmeni" type="submit" class="btn btn-success btn-block" style="color: white; background-color: orange; border: 1px solid white"> Izmeni
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                </div>
            </div>



        </div>

    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        function sortTable() {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("myTable");
            switching = true;

            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[1];
                    y = rows[i + 1].getElementsByTagName("TD")[1];
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }

        function funkcijaZaPretragu() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>


</body>

</html>