<?php

function display_Form($arrayForm)
{

    echo '<form class="form-signin" enctype="multipart/form-data" method="POST" action="' . $_SERVER['PHP_SELF'] . '">';
    echo '<h1 class=" h3 mb-3 font-weight-normal">Please Fill the Following.</h1>';
    foreach ($arrayForm as $element) {
        if ($element['type'] == 'text' || $element['type'] == 'email' || $element['type'] == 'password' || (($element['type'] == 'integer') && $element['name'] != 'inputSalesId') || $element['type'] == 'number') {
            echo '<label for="' . $element["name"] . '" class ="sr-only">' . $element['label'] . '</label>';
            echo '<input type="' . $element['type'] . '" id="' . $element['name'] . '" name="' . $element['name'] . '" class="form-control" placeholder="' . $element['label'] . '">';
        } else if ($element['type'] == "submit" || $element['type'] == "reset") {
            echo '<button class="btn btn-lg btn-primary btn-block" type="' . $element['type'] . '">' . $element['label'] . '</button>';
        } else if ($element['type'] == 'integer' && (!(isset($_SESSION['user']['type']) && ($_SESSION['user']['type'] == ADMIN))) && (!(isset($_SESSION['user']['type']) && ($_SESSION['user']['type'] == AGENT)))) {
            echo '<label for="' . $element["name"] . '" class ="sr-only">' . $element['label'] . '</label>';
            echo '<input type="' . $element['type'] . '" id="' . $element['name'] . '" name="' . $element['name'] . '" class="form-control" placeholder="' . $element['label'] . '">';
        } else if ($element['type'] == "select") {
            if (isset($_SESSION['user']) && ($_SESSION['user']['type'] == ADMIN)) {
                echo '<select name="' . $element['name'] . '" id ="' . $element['name'] . '"class="form-control">';

                $result = user_type_select(AGENT);

                echo '<option value ="-1"> Select Sales Person </option>';

                for ($i = 0; $i < pg_num_rows($result); $i++) {
                    $user = pg_fetch_assoc($result, $i);
                    echo '<option value=' . $user["id"] . '>' . $user["firstname"] . '' . $user["lastname"] . ': ' . $user["emailaddress"] . ' </option>';
                }

                echo '</select>';
            }
        } else if ($element['type'] == "file") {
            echo '<label for="' . $element['name'] . '" class="sr-only">' . $element['label'] . '</label>';
            echo '<input type="' . $element['type'] . '" name="' . $element['name'] . '" id="' . $element['name'] .
                '" class="form-control" placeholder="' . $element['label'] . '">';
        }
    }
    echo '</form>';
}

function display_table($arrFieldTable, $client_select_all, $agent_count, $page)
{
    echo '<div class="table-responsive">';
    echo '<table class="table table-striped table-sm">';
    echo '<thead>';
    echo '<tr>';
    foreach ($arrFieldTable as $key => $value) {
        echo '<th>' . $value . '</th>';
    }
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    for ($i = 0; $i < count($client_select_all); $i++) {
        echo '<tr>';
        foreach ($client_select_all[$i] as $key1 => $value1) {
            if ($key1 == 'logoPath') {
                echo '<td><img src="' . $value1 . '"alt=NO LOGO available" width ="30"></td>';
            } else {
                echo '<td>' . $value1 . '</td>';
            }
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '<nav aria-label="Page navigation">';
    echo '<ul class="pagination">';
    echo '<li class="page-item"><a class="page-link" href="#">Previous</a></li>';
    for ($i = 0; $i < $agent_count / RECORDS; $i++) {
        echo '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
    }
    echo '<li class="page-item"><a class="page-link" href="#">Next</a></li>';
    echo '</ul>';
    echo '</nav>';
    echo '</div>';
}
