<?php

namespace App\Controller\Pages;

use \App\Utils\View;

Class Page{

    /**
     * Metedo responsavel por retornar o conteudo view da Pagina
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage($title, $content) {
       return View::render('pages/page',[
        'title' => $title,
        'content' => $content,
       ]);
    }
}