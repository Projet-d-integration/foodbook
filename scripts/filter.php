<?php
    // Fonctions de triage d'ingrédient
    
    // Triage par type ( par variable de la table)
    function FilterRecipeByParameters($tabRecipe,$nameRecipe = '', $pIdTypeRecette = ''){
        if($nameRecipe != ''){
            foreach($tabRecipe as $singleRecipe){
                if(!strpos($singleRecipe[2],$nameRecipe))
                    unset($tabRecipe[$singleRecipe]);
            }
        }
        if($pIdTypeRecette != ''){
            foreach($tabRecipe as $singleRecipe){
                if($singleRecipe[6] != $pIdTypeRecette)
                    unset($tabRecipe[$singleRecipe]);
            }
        }
        return $tabRecipe;
    }
    function FilterRecipeInfoByParameters($tabRecipe,$tabInfoRecipe,$tempsPreparation = '', $nbPortions = ''){
        $tabIdToRemove = [];
        if($tempsPreparation != ''){
            foreach($tabInfoRecipe as $singleinfoRecipe){
                if($singleinfoRecipe[0] > $tempsPreparation)
                    array_push($tabIdToRemove,$singleinfoRecipe[4]);
            }
        }
        if($nbPortions != ''){
            foreach($tabInfoRecipe as $singleinfoRecipe){
                if($singleinfoRecipe[1] != $nbPortions){}
                    array_push($tabIdToRemove,$singleinfoRecipe[4]);
            }
        }
        foreach($tabRecipe as $singleRecipe){
            if(in_array($singleRecipe[0],$tabIdToRemove))
                unset($tabRecipe[$singleRecipe]);
        }
        return $tabRecipe;
    }
    // Triage par nom
?>