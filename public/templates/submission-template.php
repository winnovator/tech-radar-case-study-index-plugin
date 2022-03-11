<?php
    include_once(plugin_dir_path(__DIR__) . "data/submission.php");

    $formID = isset($_GET['form-id']) ? (int)$_GET['form-id'] : null;

    $subObj = new Submissions();
    $nfSubFields = $subObj->getFieldsFromFieldModelByFormID($formID);
    $nfSubData = $subObj->getSubsFromSubModelByFormID($formID);
    $convertedSubDataArr = $subObj->convertSubArr($nfSubData);
?>

<div class="wrap">
    <h1>Submissions</h1>
    <div id="table-wrap">
        <form action="" method="post">
            <table id="nfFormTable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Form ID</th>
                        <?php

                        foreach ($nfSubFields as $nfSubField) {
                            if ($nfSubField->get_setting('key') == 'submit' || $nfSubField->get_setting('label') == 'Submit') { continue; }
                            echo '<th>' . $nfSubField->get_setting('label') . '</th>';
                        }

                        ?>
                        <th>Selector</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                foreach ($convertedSubDataArr as $convertedSubArr) {
                    echo '<tr>';

                    foreach ($convertedSubArr as $convertedSubValue) {
                        echo "<td> $convertedSubValue </td>";
                        
                    }
                    
                    echo '<td> <input type="checkbox" name="submissions" value="' . $convertedSubArr[0] . '"> </td>';
                    echo '</tr>';
                }

                ?>
                </tbody>
            </table>
            <div id='submit-wrap'>
                <input id='submit-button' class="button action" type='submit' value='Submit'>
            </div>
        </form>
    </div>
</div>