<?php
    // Fonctions de triage d'ingrédient
    // Triage par type ( par variable de la table)

    function FilterRecipe($tabRecipe,$tabInfoRecipe,$nameRecipe = '', $pIdTypeRecette = '',$tempsPreparation = 0, $nbPortions = 0){
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
        if($tempsPreparation != 0){
            foreach($tabRecipe as $singleRecipe){
                foreach($tabInfoRecipe as $singleinfoRecipe){
                    if($singleRecipe[0] == $singleinfoRecipe[4]){
                        if(intval($singleinfoRecipe[0]) > intval($tempsPreparation))
                            unset($tabRecipe[array_search($singleRecipe,$tabRecipe)]);
                    }
                }
            }
        }
        if($nbPortions != 0){
            foreach($tabRecipe as $singleRecipe){
                foreach($tabInfoRecipe as $singleinfoRecipe){
                    if($singleRecipe[0] == $singleinfoRecipe[4]){
                        if($singleinfoRecipe[1] != $nbPortions)
                            unset($tabRecipe[array_search($singleRecipe,$tabRecipe)]);
                    }
                }
            }
        }
        return $tabRecipe;
    }
    
    //Filter by name or type ingredient //AllIngredientInfo
    function FilterIngredient($tabIngredient, $nameIngredient = '', $idTypeIngredient = ''){
        if($nameIngredient != ''){
            foreach($tabIngredient as $singleIngredient){
                if(!preg_match("/{$nameIngredient}/i",$singleIngredient[1]))
                {
                    unset($tabIngredient[array_search($singleIngredient,$tabIngredient)]);
                }
            }
        }
        

        if($idTypeIngredient != ''){
            foreach($tabIngredient as $singleIngredient){
                if($singleIngredient[3] != $idTypeIngredient)
                    unset($tabIngredient[array_search($singleIngredient,$tabIngredient)]);
            }
        }

        return $tabIngredient;
    }
    function FilterUsers($tabUser, $nameUser = ''){
        if($nameUser != ''){
            foreach($tabUser as $singleUser){
                if(!preg_match("/{$nameUser}/i",$singleUser[1]) && !preg_match("/{$nameUser}/i",$singleUser[2]))
                {
                    unset($tabUser[array_search($singleUser,$tabUser)]);
                }
            }
        }
        return $tabUser;
    }
?>