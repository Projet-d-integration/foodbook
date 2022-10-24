<?php
    // Fonctions de triage d'ingrédient
    // Triage par type ( par variable de la table)

    function FilterRecipe($tabRecipe,$tabInfoRecipe,$nameRecipe = '', $pIdTypeRecette = '',$tempsPreparation = '', $nbPortions = 0){
        $tabIdToRemove = [];
        
        if($nameRecipe != ''){
            foreach($tabRecipe as $singleRecipe){
                if(!preg_match("/{$nameRecipe}/i", $singleRecipe[2])){
                    unset($tabRecipe[array_search($singleRecipe,$tabRecipe)]);
                }
            }
        }

        if($pIdTypeRecette != ''){
            foreach($tabRecipe as $singleRecipe){
                if($singleRecipe[6] != $pIdTypeRecette)
                    unset($tabRecipe[array_search($singleRecipe,$tabRecipe)]);
            }
        }

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

        foreach($tabIdToRemove as $idToRemove){
            foreach($tabRecipe as $singleRecipe){
                if(intval($singleRecipe[0]) == intval($idToRemove))
                unset($tabRecipe[array_search($singleRecipe,$tabRecipe)]);
            }
        }
        
        return $tabRecipe;
    }
    
    //Filter by name or type ingredient //AllIngredientInfo
    function FilterIngredient($tabIngredient, $nameIngredient = '', $idTypeIngredient = '', ){
        $tabIngredient = [];

        if($nameIngredient != ''){
            foreach($tabIngredient as $singleIngredient){
                if(!preg_match("/{}"))
                {
                    null
                }
            }
        }
    }

    include 'db.php';
    $tab = AllIngredientInfo('ASC');
    foreach($tab as $info){
        echo "<p>$info[0],$info[1],$info[2],$info[3]</p>";
    }
?>