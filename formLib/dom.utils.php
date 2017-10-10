<?php
//dom manipulation

class DOMUtils {

    public static function generateDOMfromFile($file, $parsePHP = true, $whitespace = false) {
        $html;
        if($parsePHP) {
            ob_start();
            include $file;
            $html = ob_get_contents();
            ob_end_clean();
        } else {
            $html = readfile($file);
        }
        $DOM = new domDocument();
        $DOM->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        if (!$whitespace) {
            $DOM->preserveWhiteSpace = false;
        }
        return $DOM;
    }

    public static function getElementsByAttributeValues($dom, $attributes) {
        $elements = $dom->getElementsByTagName("*");
        $query = self::filterByAttributeValues($elements, $attributes);
        return $query;
    }

    public static function getElementsByTags($elements, $tags) {
        $query = array();
        foreach ($tags as $tag) {
            foreach ($elements as $element) {
                if ($element->tagName == $tag) {
                    $query[] = $element;
                }
            }
        }
        return $query;
    }

    public static function filterByAttributeValues($elements, $attributes) {
        $query;
        foreach($elements as $element) {
            foreach($attributes as $attr => $value) {
                if ($element->hasAttribute($attr)) {
                    if($element->getAttribute($attr) == $value){
                        $query[] = $element;
                    }
                }    
            }
        }
        return $query;
    }

    public static function getElementsByHasAttributes($dom, $attributes) {
        $elements = $dom->getElementsByTagName("*");
        $query = self::filterByHasAttributes($elements, $attributes);
        return $query;
    }

    public static function filterByHasAttributes($elements, $attributes) {
        $query = array();
        foreach($elements as $element) {
            foreach($attributes as $attr) {
                if ($element->hasAttribute($attr)) {
                    $query[] = $element;
                }    
            }
        }

        if ($attr == "ppForm") {
            print_r($query);
        }
        
        return $query;
    }

    public static function deleteElement($element) {
        return $element->parentNode->removeChild($element);
    }

    public static function addClass($element, $class) {
        if ($element->hasAttribute("class")) {
            $newClassList = $element->getAttribute("class"). " ". $class;
            $element->setAttribute("class", $newClassList);
        } else {
            $element->setAttribute("class", $class);
        }

        return $element;
    }

    public static function getAttributesAsArray($element) {
        $attrs = array();
        if ($element->hasAttributes()){
            foreach ($element->attributes as $attr) {
                $attrs[$attr->name] = $attr->value;
            }
        }
        return $attrs;
    }
}