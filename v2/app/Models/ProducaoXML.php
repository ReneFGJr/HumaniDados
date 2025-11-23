<?php

namespace App\Models;

use CodeIgniter\Model;

class ProducaoXML extends Model
{

    function zeraDados($idlattes)
    {
        $ProducaoArtisticaModel = model('ProducaoArtisticaModel');
        $ProducaoArtisticaModel->where('id_lattes',$idlattes)->delete();
        return true;
    }

    function le($idLattes)
        {
            $dt = $this->where('id_lattes',$idLattes)->findAll();
            return $dt;
        }

    function dadosBasicos($idlattes, $D,$prefixo,$tipo)
    {
        $ProducaoArtisticaModel = model('ProducaoArtisticaModel');
        echo 'DADOS-BASICOS' . $prefixo.'<hr>';
        echo 'DETALHAMENTO' . $prefixo . '<hr>';
        //try {
        $basic = $D->{'DADOS-BASICOS'.$prefixo};
        $detal = $D->{'DETALHAMENTO'.$prefixo};
        $basic = (array)$basic->attributes();
        $detal = (array)$detal->attributes();

        $basic = $basic['@attributes'] ?? [];
        $detal = $detal['@attributes'] ?? [];
        $base = array_merge($basic, $detal);
        $base['tipo'] = $tipo;
        $base['id_lattes'] = $idlattes;

        $base['tipo'] = $tipo;
        $base['titulo'] = $base['TITULO'] ?? $base['titulo'];
        $base['natureza'] = $base['NATUREZA'] ?? null;
        $base['atividade'] = $base['ATIVIDADE-DOS-AUTORES'] ?? null;
        $base['ano'] = $base['ANO'] ?? null;
        $base['pais'] = $base['PAIS'] ?? null;
        $base['idioma'] = $base['IDIOMA'] ?? null;
        $base['flag_relevancia'] = $base['FLAG-RELEVANCIA'] ?? null;
        $base['titulo_ingles'] = $base['TITULO-INGLES'] ?? null;
        $base['meio_divulgacao'] = $base['MEIO-DIVULGACAO'] ?? null;
        $base['home_page'] = $base['HOME-PAGE'] ?? null;
        $base['flag_divulgacao_cientifica'] = $base['FLAG-DIVULGACAO-CIENTIFICA'] ?? null;
        $base['premiacao'] = $base['PREMIACAO'] ?? null;
        $base['atividade_autores'] = $base['ATIVIDADE-AUTORES'] ?? null;
        $base['instituicao_evento'] = $base['INSTITUICAO-EVENTO'] ?? null;
        $base['local_evento'] = $base['LOCAL-EVENTO'] ?? null;
        $base['cidade_evento'] = $base['CIDADE-EVENTO'] ?? null;
        $base['temporada'] = $base['TEMPORADA'] ?? null;
        $base['informacoes_adicionais'] = $base['INFORMACOES-ADICIONAIS'] ?? null;
        $idProducao = $ProducaoArtisticaModel->insert($base);

        /*
        } catch (\Exception $e) {
            echo '<h1>Erro ao processar produção artística</h1>';
            pre($D);
            // Log the exception or handle it as needed
            return null; // or some error code
        }
        */
        pre($D,false);

        return $idProducao;
    }
}