<?php
include 'config.php';
if (isset($_POST['safe']))
{
    try {
        //Hier müsste dann noch isset für das datum und die Uhrzeit drüber, dann könnte man den Error ausgeben
        $Zeitraum_Tag = $_POST['datum'];
        $Zeitraum_Stunde = $_POST['uhrzeit'];
        $Autor = $_POST['Autor'];
        $Genre = $_POST['Genre'];
        $TitleStueck = $_POST['TitelStück'];

        //TODO: Hier müsste noch alles Inserted werden:

        $query1 = 'insert into drama (dra_name, gen_id, autor_id) values(?,?,?)';
        $stmt = $con->prepare($query1);

        $stmt->execute([$TitleStueck, $Genre, $Autor]);


        //$stmt = $con->prepare($query);
        //$stmt->execute();

        $query = 'select Genre.gen_id as "Nr.",
                                 Genre.gen_name as "Genre",
                                 Drama.dra_name as "Name des Stücks",
                                 concat_ws(" ", Person.per_vName, Person.per_nName) as "Autor",
                                 Dramaevent.eve_termin as "Termine"
                                 from Genre, Drama, Dramaevent, Person
                                 where Genre.gen_id = Drama.gen_id
                                 and Drama.autor_id = Person.per_id
                                 and Drama.dra_id = Dramaevent.dra_id
                                 ';
        $stmt = $con->prepare($query);
        $stmt->execute();
        $stmtNew = $stmt;

        echo '<div class="table">
          <div class="row">';
        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            echo '<div class="col"><label class="font-weight-bold">' . $stmt->getColumnMeta($i)['name'] . '</label></div>';
        }
        echo '</div>'; // row
        //Hier wird dann nur ausgegeben, was ich mir gesucht habe von den Zeiträumen aus
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            echo '<div class="row">';
                echo '<div class="col">' . $row[0] . '</div><div class="col">' . $row[1] . '</div><div class="col">' . $row[2] . '</div><div class="col">' . $row[3] . '</div><div class="col">' . $row[4] . '</div>';
            echo '</div>';
        }
        echo '</div>'; // table

        echo 'Datensatz konnte gespeichert werden';
    } catch (Exception $e)
    {
        switch ($e->getCode()) {
            case 1169:
                echo "Unique Constraint wurde verletzt";
                break;
            case 1062:
                echo "Es wurden zwei Einträge für einen Key gefunden";
        }
        echo $e->getCode().': '.$e->getMessage();
    }

} else
{
    ?>
    <h1>Theaterstück erfassen</h1>

    <form method="post">
        <div class="form-group">
            <label class="col-sm-2" for="Zeitraum">Erstaufführung am:</label>
            <input type="date" id="datum" name="datum">
        </div>

        <div class="form-group">
            <input class="col-sm-5" type="time" id="uhrzeit" name="uhrzeit">
        </div>
    </form>

    <form method="post">
    <div class="form-group">
        <label class="col-sm-2" for="vn">Titel des Stücks:</label>
        <input class="col-sm-5" type="text" id="nn" name="TitelStück" placeholder="z.B. Faust">
    </div>
    <div class="form-group">
    <label class="col-sm-2" for="Autor">Autor:</label>

    <?php
        $query = 'select per_id, per_nName from person, rolle 
                  where rolle.rol_id = 4';
        $stmt = $con->prepare($query);
        $stmt->execute();

        echo '<select name="Autor">';
        while($row = $stmt->fetch())
        {
            echo '<option value="'.$row['per_id'].'" name="Autor">'.$row['per_nName'];
        }

        echo '</option>';
        echo '</select>';
    ?>

    <label class="col-sm-2" for="Genre">Genre:</label>

    <?php
    $query = 'select gen_id, gen_name from genre';
    $stmt = $con->prepare($query);
    $stmt->execute();

    echo '<select name="Genre">';
    while($row = $stmt->fetch())
    {
        echo '<option value="'.$row['gen_id'].'" name="Genre">'.$row['gen_name'];
    }

    echo '</option>';
    echo '</select>';
    ?>


    <input type="submit" name="safe" value="Speichern">

    <?php
    echo '</form>';


}