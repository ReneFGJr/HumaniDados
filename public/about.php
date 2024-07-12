<?php
require("header/cab.php");
?>
<div class="container">
    <div class="row">
        <div class="col-lg-10">
            <h1>Sobre o Projeto HumaniDados</h1>
            <p>Perante a representatividade da área de Humanidades e a necessidade de uma valoração mais equilibrada das atividades acadêmicas, de atuação ou produção do saber, reitera-se que, a comunidade científica brasileira em humanidades é o objeto de pesquisa.</p>
            <p>Na formalização do projeto empregou-se o uso de seis módulos, integralizados durante o processo de construção, descritos: 1) Módulo de identificação de pesquisadores e coleta de dados: associação de uma determinada área prioritária a um pesquisador. 2) Módulo de padronização e criação do conjunto de dados: uso de diferentes fontes de dados; casamento de informações. 3) Módulo de deduplicação e harmonização de dados: apresentação das produções de forma única; eliminação de duplicatas de produções bibliográficas feitas em coautoria. 4) Módulo de identificação de colaboração/coautoria: representação em redes/grafos de colaboração para produções em coautoria. 5) Módulo de caracterização e enriquecimento de dados: integração de dados a fim de ter mais informação coletada. E 6) Módulo de geração de relatórios: criação de interface web que permita apresentar os dados coletados e investigar com detalhe toda a produção dos pesquisadores.</p>
            <p>Em fase inicial, realizou-se uma pesquisa exploratória na área de humanidades, a saber mais sobre os pesquisadores e suas produções intelectuais. A princípio, foi considerada a área das Artes para análise, geração de indicadores, desenvolvimento do protótipo e validação da plataforma. Artes, seria um dos grandes campos de vulnerabilidade representativa tendo em vista seus produtos artístico-culturais, uma vez que dados bibliográficos, são mais facilmente contabilizados que dados técnicos e artísticos. Dessa forma, a área de Artes foi a escolhida como primeiro campo de análise, visando desenvolver o protótipo do Portal “HumaniDados”. Nesta fase, as plataformas Capes e Lattes/CNPq foram as fontes primárias de informação.</p>
            <p>Para possíveis extrapolações, foram elaborados três esquemas dos campos Artes Cênicas, Música e Artes Visuais, respectivamente. Nas figuras 1, 2 e 3 é possível visualizar a natureza, o tipo de evento e a atividade dos autores sobre a produção artística/cultural conforme encontram-se nos campos do Lattes.</p>
            <div class="text-center">
                <img class="img-fluid" src="/assets/img/diagrama-artitica-cultural.png"><br />
                <img class="img-fluid" src="/assets/img/diagrama-artitica-cultural-artesvisuais.png"><br />
                <img class="img-fluid" src="/assets/img/diagrama-artitica-cultural-musica.png"><br />
            </div>
            <p>A critérios de padronização da nomenclatura da produção intelectual, houveram conflitos entre os termos utilizados por ambas plataformas. Para isso, mapeou-se os tipos de produção e produtos que integram cada uma; os produtos descritos pela Capes e a disposição em abas e campos do Lattes, para melhor entendimento e utilização de termos padrão no Portal.
            <p>A respeito da coleta de dados, extraiu-se os currículos acadêmicos do Lattes, quanto ao tratamento, os dados foram organizados, refinados e passados por um processo de limpeza e eliminação de duplicatas, em adequação aos padrões de indicadores bibliométricos. Com fins de caracterização, utilizou-se uma lista adaptada do conjunto de indicadores qualitativos de pesquisa em Humanidades, que contempla as seguintes dimensões: bibliográfica, referencial, técnica, internacional, web e reconhecimento.
            <p>Para o desenvolvimento dos indicadores esperados, as dimensões foram consideradas como unidades de análise da pesquisa, entretanto, algumas delas demarcaram os limites do estudo. Reconhecimento – foi observada a falta de dados concretos no Lattes sobre o preenchimento dos campos a respeito de financiamento e prêmios na submissão do currículo, conjuntamente, a possibilidade de uso de outro recurso foi um limite de pesquisa. Web – verificou-se uma baixa presença ou existência de canais oficiais e criação de conteúdos. A condicional encontrada é a participação do pesquisador em canais institucionais, televisivos ou midiáticos. Foram consultados os bolsistas de produtividade do CNPq nas plataformas Wikipedia, Spotify, Deezer, Apple, Youtube e Enciclopédia Itaú Cultural.</p>
            <p>Ademais, pretendeu-se apresentar indicadores humanos de índice social (faixa etária, impacto, função), porém, a captação desses dados é dificultada em campos de busca (título, natureza, evento, atividade) pela tendência dos pesquisadores ao preenchimento do campo “outro”, que denota alta subjetividade. O preenchimento é realizado de forma arbitrária e despadronizada, o que torna o processo de filtragem estritamente manual, contrário ao grande volume de dados extraídos, sendo necessária uma verificação de registro em registro. Esse tipo de dado torna-se obsoleto sem um processo de automação e atualização constante da plataforma, recaindo sobre a não integridade dos dados. Por tais motivos, as unidades de análise explicitadas foram descontinuadas.</p>
            <p>Sucessivamente, correlacionou-se os dados obtidos por meio de cruzamentos como pesquisador e pesquisador, instituição e pesquisador, campo de atuação e pesquisador etc. Assim, os indicadores produzidos na criação do Portal foram: bibliográfica, formação, colaborações e produção técnica e artística. A primeira versão do HumaniDados, será disponibilizada para as entidades representativas (pessoas e instituições) do campo das Artes. Considerando aspectos de usabilidade e funcionalidade, a comunidade avaliará o Portal e, com o retorno desse processo, será possível obter a validação da plataforma e realizar ajustes com o feedback.
        </div>

        <div class="col-lg-12">
            Processo: 421072/2022-9<br />
            Chamada nº 40/2022 - Linha 1B - Projetos em Rede - Pesquisa em temas livres em Ciências Humanas, Ciências Sociais<br />
            Aplicadas e Linguística, Letras e Artes<br />
            SIGLA: Pro-Humanidades 2022<br />
        </div>

    </div>

    <?php
    require("header/foot.php");
    ?>