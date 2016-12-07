<?php

namespace AGR\Service;

class UploadService{

   public static function uploadImagem($imagem){
        $arquivo_tmp = $imagem[ 'tmp_name' ];
        $nome = $imagem[ 'name' ];

        // Pega a extensão
        $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );

        // Converte a extensão para minúsculo
        $extensao = strtolower ( $extensao );

        // Cria um nome único para esta imagem
        // Evita que duplique as imagens no servidor.
        // Evita nomes com acentos, espaços e caracteres não alfanuméricos
        $novoNome = uniqid ( time () ) .'.'. $extensao;

        if(!is_dir('imagens/')){
            mkdir('imagens/', 0777);
        }
        
        // Concatena a pasta com o nome
        $destino = 'imagens/' . $novoNome;

        // tenta mover o arquivo para o destino
        if (move_uploaded_file ( $arquivo_tmp, $destino ) ) {
            return $destino;
        }
        return null;
   }
}


?>