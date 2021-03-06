<?php $title = 'Création des chapitres';
$config = include ('././config/config.php');
$url = $config[ 'url' ];?>

<?php ob_start(); ?>
<div id="administrationChapter">
    <!--Editeur de texte -->
    <article id="create">
        <?php
//        if (isset ($resume2)){
//            $chapter=$resume2;
//        }
        if (!isset($message)){$message="";}
        ?>
        <form action="./index.php?action=saveNew&&message=<?=$message?>" method="post" value="save" class="chapitre">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h2> Créer un nouveau chapitre : </h2>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <button type="submit" role="submit" class="btn btn-primary">
                    <h5> Enregistrer<br /></h5>
                    <i class="far fa-save"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="title">
                        <h3> Titre : </h3>
                    </label>
                    <input type="text" id="title" name="title" value="<?=$chapter['title']?>" />
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <label for="id_chapter">
                        <h3> Numéro : </h3>
                    </label>
                    <input type="text" id="number_chapter" name="number_chapter" value="<?=$chapter['number_chapter']?>" />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <textarea id="mytextarea" name="mytextarea" rows="25"><?=$chapter['content']?></textarea>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <button type="submit" role="submit" class="btn btn-primary">
                    <h5> Enregistrer<br /></h5>
                    <i class="far fa-save"></i>
                </button>
            </div>
        </form>
    </article>
    <!--résumé des derniers chapitres -->
    <article id="fast" class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <h3>Chapitres déjà enregistés:</h3>
        </div>
        <table class="table table-striped chapters">
            <thead>
                <tr>
                    <th scope="col">chapitre n°</th>
                    <th scope="col">titre</th>
                    <th scope="col">Résumé</th>
                    <th scope="col">Date de publication</th>
                    <th scope="col">Dernière modification</th>
                    <th scope="col">Editer</th>
                </tr>
            </thead>
            <tbody>
                <?php
            while ($res = $resume->fetch()){
                $limit=500;
                $res['content']= strip_tags($res['content']);
                if (strlen($res['content'])>=$limit){
                    $res['content']=substr($res['content'],0,$limit);
                    $space=strrpos($res['content'],' ');
                    $res['content']=substr($res['content'],0,$space)."...";
                }
            ?>
                <tr>
                    <th scope="row">
                        <?= ($res['id_chapter'])?>
                    </th>
                    <th scope="row">
                        <?= strip_tags($res['title'])?>
                    </th>
                    <th>
                        <?=strip_tags($res['content'])?>
                    </th>

                    <th>
                        <?= ($res['publication_date'])?>
                    </th>
                    <th>
                        <?= ($res['modification_date'])?>
                    </th>
                    <th><a role="button" class="btn btn-light" href="$url?action=edit&&id_chapter=<?=$res['id_chapter']?>" role="button"><i class="far fa-edit"></i></a></th>
                </tr>
                <?php
            }
            $resume->closeCursor();
            ?>
            </tbody>
        </table>
    </article>
</div>


<?php $content=ob_get_clean(); ?>
<?php require('templateBack.php'); ?>
