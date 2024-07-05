<?php

/**
 * Renderiza um submenu HTML a partir de um array associativo.
 *
 * Esta função recebe um array associativo contendo os itens do submenu, onde as chaves são os textos dos itens e os valores são os URLs ou submenus aninhados.
 *
 * @param array $subMenu Um array associativo contendo os itens do submenu.
 * @param string|null $additionalContent Conteúdo adicional a ser adicionado ao final do submenu. Pode ser HTML ou texto simples.
 * @author Gabrielli
 */

function renderSubMenu($subMenu = [], $additionalContent = null) {
    echo '<ul>';
    foreach ($subMenu as $key => $value) {
        echo '<li>';
        if (is_array($value)) {
            echo $key;
            renderSubMenu($value); 
        } else {
            echo '<a href="' . $value . '">' . $key . '</a>';
        }
        echo '</li>';
    }
    if (!empty($additionalContent)) {
        echo '<li>' . $additionalContent . '</li>';
    }
    echo '</ul>';
}
?>
