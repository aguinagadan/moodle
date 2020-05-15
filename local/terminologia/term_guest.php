<?php
global $terminoController;

$alphabetArr = range('A', 'Z');
$mostSearchedArr = $terminoController->GetMasBuscados();
$count = 1;
?>
<div class="main-page-container-guest">
    <div class="buscados-container">
        <div class="big-title">
            Los términos más buscados
        </div>
        <div class="buscados-container-table">
            <table class="buscados-table">
                <?php foreach (range(1,3) as $r) { $count=1; ?>
              <tr>
                <?php foreach ($mostSearchedArr as $key=>$item) {?>
                    <td>
                        <?php
                        if($count == 1) {
                            $color = "#184A7D";
                        } elseif ($count == 2){
                            $color = "#4DB8E8";
                        } elseif ($count == 3){
                            $color = "#76A140";
                        }
                        ?>
                    <div id="<?php echo $item->id ?>" class="result-value" style="background-color:<?php echo $color; ?>" type="button">
                        <span style="font-weight: bold;"><?php echo strtoupper($item->nombre);echo '<br/>';?></span>
                        <span style="font-size: 80%;"><?php echo strtoupper($item->origen);?></span>
                        <?php
                        unset($mostSearchedArr[$key]);
                        if($count == 3) {
                            break;
                        }
                        $count++;
                        ?>
                    </div>
                        </td>
                    <?php } ?>
                  </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<div class="sugerencia-letter-results-container hidden">
    <div class="big-title sugerencia-title">
        ¿No encuentras el término?
    </div>
    <form id="sugerencia-add-form" action="" method="post" enctype="multipart/form-data">
        <div class="sugerencia-letter-input">
            <input id="sugerencia-input" name="termino-sugerido" class="form-control sugerencia-input" type="text" placeholder="Sugiere aquí la palabra..." required/>
        </div>
        <div class="sugerencia-letter-button">
            <input form="sugerencia-add-form" type="submit" class="btn btn-primary process-button sugerir-btn" value="Sugerir palabra"/>
        </div>
    </form>
</div>