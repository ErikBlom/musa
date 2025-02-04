<?php 
require_once 'environment.php';
// ------------------------------------------------------
$page_title='Organisationer';
$user->admit(['super']);
// ------------------------------------------------------
$sql_table="
FROM musaOrgs
";
$sql_group="";

//$cols_visible=['org_id'=>'Org ID', 'org_name'=>'Organisationsnamn', 'org_info'=>'Organisationsinfo', 'org_created'=>'Skapad'];
//$cols=update_columns_info($cols_visible);
$cols_visible=['org_id', 'org_name', 'org_info', 'org_created'];
$cols_searchable=['org_name', 'org_info'];
$cols=get_columns_info($cols_visible);
//print("<pre>");print_r(json_encode($cols,JSON_PRETTY_PRINT));print("</pre>");


$order = "org_name";
// ------------------------------------------------------

// ------------------------------------------------------
$export_enable="A";
set_search_sort_pagination();
// ------------------------------------------------------
// do query with pagination limits for display
$rl=$db->getRecFrmQry("SELECT * $sql LIMIT $offset, $no_of_records_per_page");
// ------------------------------------------------------
//setMessage("Draft Org admin");

// ------------------------------------------------------
// display page
// ------------------------------------------------------
require_once 'header.php';

?>


    <form action="" method="get">
        <div class="form-row">
            <div class="col">
                <h1><?php print($page_title);?></h1>
            </div>
            <div class="col">
                <?php 
                export_buttons();
                ?>
            </div>
        </div>
    </form>

    <?php

    if($rl){
      print('<table class="table table-striped table-sm table-bordered border"><thead class="thead-dark"><tr>');
      foreach ($cols_visible as $col) {
        draw_header();
      }
      print('<th style="width:5em;">Action</th></tr></thead><tbody>');
      foreach ($rl as $i) {
        print('<tr>');
        foreach ($cols_visible as $col) {
            //$a='<a href="org_update.php?target_id='. $i['org_id'] .'" title="Redigera Organisation" data-toggle="tooltip"><i class="fa fa-users"></i></a> ';
            print("<td>".$i[$col]."</td>");
        }
        print('<td>');
        print('<a href="users_admin.php?org_id='. $i['org_id'] .'" title="Användare" data-toggle="tooltip"><i class="fa fa-users"></i></a> ');
        print('&nbsp| <a href="org_update.php?edit&target_id='. $i['org_id'] .'" title="Redigera" data-toggle="tooltip"><i class="fa fa-edit"></i></a> ');
        print('&nbsp| <a href="org_update.php?delete&target_id='. $i['org_id'] .'" data-toggle="tooltip" '.confOp("delete").'</a>');
        print('</td></tr>');
      }
      print('</tbody></table>');
      displayPagination($pageno,$total_pages,"&search=$search&order=$order&sort=$sort");

    ?> 
      
      
    <?php
    } else{
      echo "<p class='lead'><em>Hittar inget.</em></p>";
    }
require_once 'footer.php';
?>
