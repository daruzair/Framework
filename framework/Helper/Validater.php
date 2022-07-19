<?php
    function SanitizeField($input){
        $input = strip_tags($input);
        $input = str_replace(' ', '-', $input); // Replaces all spaces with hyphens.
        $input = preg_replace('/[^A-Za-z0-9@.\-]/', '', $input); // Removes special chars.
        return preg_replace('/-+/', '-', $input); // Replaces multiple hyphens with single one.
    }
    function isContainHtmlTags($input){
        return $input != strip_tags($input);
    }
    function isContainSpacialChar($input,$SpecialChars="~`!#$%^&*()_+{}[]|\|?'?<>+-\"',"){
        for ($i=0;$i<strlen($SpecialChars);$i++){
            $SpecialChar=$SpecialChars[$i];
            if(str_contains($input, $SpecialChar)){
                return true;
            }
        }
        return false;
    }
