<?php
    // Fonctions de triage d'ingrédient
    // Triage par type ( par variable de la table)
    function FilterRecipeByParameters($tabRecipe,$nameRecipe = '', $pIdTypeRecette = ''){
        $tabIdToRemove = [];
        if($nameRecipe != ''){
            foreach($tabRecipe as $singleRecipe){
                if(!preg_match("/{$nameRecipe}/i", $singleRecipe[2]))
                    array_push($tabIdToRemove,$singleRecipe[0]);
            }
        }
        if($pIdTypeRecette != ''){
            foreach($tabRecipe as $singleRecipe){
                if($singleRecipe[6] != $pIdTypeRecette){
                    array_push($tabIdToRemove,$singleRecipe[0]);
                }
            }
        }
        $cpt = 0;
        foreach($tabRecipe as $singleRecipe){
            if(in_array($singleRecipe[0],$tabIdToRemove))
                unset($tabRecipe[$cpt]);
            $cpt++;
        }
        return $tabRecipe;
    }
    function FilterRecipeInfoByParameters($tabRecipe,$tabInfoRecipe,$tempsPreparation = '', $nbPortions = 0){
        $tabIdToRemove = [];
        if($tempsPreparation != ''){
            foreach($tabInfoRecipe as $singleinfoRecipe){
                if($singleinfoRecipe[0] > $tempsPreparation)
                    array_push($tabIdToRemove,intval($singleinfoRecipe[4]));
            }
        }
        if($nbPortions != 0){
            foreach($tabInfoRecipe as $singleinfoRecipe){
                if($singleinfoRecipe[1] != $nbPortions)
                    array_push($tabIdToRemove,intval($singleinfoRecipe[4]));
            }
        }
        /*
        $cpt = 0;
        foreach($tabRecipe as $singleRecipe){
            if(in_array($singleRecipe[0],$tabIdToRemove))
                unset($tabRecipe[$cpt]);
            $cpt++;
        }*/
        foreach($tabIdToRemove as $idToRemove){
            $cpt = 0;
            foreach($tabRecipe as $singleRecipe){
                if(intval($singleRecipe[0]) == intval($idToRemove))
                    unset($tabRecipe[$cpt]);
                $cpt++;
            }
        }
        return $tabRecipe;
    }
    include 'db.php';
    $tab = ShowRecipe('3','','DESC');
    $tab = FilterRecipeByParameters($tab,'',3);
    $tabInfoRecipe = InfoRecipe();
    $tab = FilterRecipeInfoByParameters($tab,$tabInfoRecipe,'',4);
    foreach($tab as $info){
        echo "<p>$info[0],$info[1],$info[2],$info[3],$info[4],$info[5],$info[6] </p>";
    }
    // Triage par nom
?>