@extends('layout.index')
@section('title', 'Regras')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Regras</span>
    </div>
    <div class="bg-content">
        <div class="container">
            <div class="bg-title">Acordo de Serviço do {{ htmlspecialchars(config('otserver.site.serverName')) }}</div>
            <div id="rules" class="main-content">
                <p>{{ htmlspecialchars(config('otserver.site.serverName')) }} é um jogo RPG online no qual milhares de jogadores de todo o mundo se reúnem todos os dias. A fim de garantir que o jogo é divertido para todos, a {{ htmlspecialchars(config('otserver.site.serverName')) }} espera que todos os jogadores se comportem de maneira razoável e respeitosa.</p>
                <p>{{ htmlspecialchars(config('otserver.site.serverName')) }} se reserva o direito de parar comportamento destrutivo no jogo, no site oficial ou em qualquer outra parte dos serviços da {{ htmlspecialchars(config('otserver.site.serverName')) }}. Tal comportamento inclui, mas não está limitado a, os seguintes factos:</p>
                <ul class="list-unstyled">
                    <li>1. Nomes</li>
                    <ul>
                        <li class="fw-bold">A) Nome ofensivo</li>
                        <li>Nomes que são um insulto, racista, sexual, relacionadas à drogas, embaraçosas ou indesejável.</li>

                        <li class="fw-bold">B) Formato inválido Nome</li>
                        <li>Nomes que contêm partes de sentenças (exceto para nomes de guilda), palavras mal formatadas ou combinações de letras sem sentido.</li>

                        <li class="fw-bold">C) Nome que contém Forbidden Publicidade</li>
                        <li>Nomes que anunciam marcas, produtos ou serviços de terceiros, conteúdo que não está relacionado ao jogo ou negoceiam por dinheiro real.</li>

                        <li class="fw-bold">D) Nome inadequados</li>
                        <li>Nomes que expressam opiniões políticas ou religiosas ou geralmente não se encaixam na temática de fantasia medieval do {{ htmlspecialchars(config('otserver.site.serverName')) }}.</li>

                        <li class="fw-bold">E) Nome apoiando violação da regra</li>
                        <li>Nomes que apoio, incitam, anunciam ou implicam uma violação das regras do jogo.</li>
                    </ul>
                </ul>
                <ul class="list-unstyled">
                    <li>2. Demonstrações</li>
                    <ul>
                        <li class="fw-bold">A) Declaração ofensivo</li>
                        <li>Insultuoso, racista, sexual, Drogas, embaraçosas ou censuráveis de alguma forma.</li>

                        <li class="fw-bold">B) Spamming</li>
                        <li>Excessivamente repetindo declarações idênticas ou similares, ou o uso de texto mal formatado ou sem sentido.</li>

                        <li class="fw-bold">C) Proibida publicidade</li>
                        <li>Marcas de publicidade, produtos ou serviços de terceiros, conteúdo que não está relacionado com o jogo ou troca por dinheiro real.</li>

                        <li class="fw-bold">D) Off-Topic declaração pública</li>
                        <li>Declarações públicas religiosas ou políticas ou outras declarações públicas que não estão relacionados com o tema do canal ou cartão utilizado.</li>

                        <li class="fw-bold">E) A violação restrição idioma</li>
                        <li>Nomes que apoio, incitam, anunciam ou implicam uma violação das regras do jogo.</li>

                        <li class="fw-bold">F) A divulgação de dados pessoais dos outros</li>
                        <li>Divulgação de dados pessoais de outras pessoas.</li>

                        <li class="fw-bold">G) Apoiar a violação da regra</li>
                        <li>As declarações que apoio, incitam, anunciam ou implicam uma violação das regras do jogo.</li>
                    </ul>
                </ul>
                <ul class="list-unstyled">
                    <li>3. Cheating</li>
                    <ul>
                        <li class="fw-bold">A) Abuso de bug</li>
                        <li>Explorando erros óbvios do jogo ou qualquer outra parte de serviços da {{ htmlspecialchars(config('otserver.site.serverName')) }}.</li>

                        <li class="fw-bold">B) Uso de Software não-oficial para jogar</li>
                        <li>Manipulando o programa oficial cliente ou utilizando software adicional para jogar o jogo.</li>
                    </ul>
                </ul>
                <ul class="list-unstyled">
                    <li>4. {{ htmlspecialchars(config('otserver.site.serverName')) }}</li>
                    <ul>
                        <li class="fw-bold">A) Fingindo ser {{ htmlspecialchars(config('otserver.site.serverName')) }}</li>
                        <li>Fingindo ser um representante da {{ htmlspecialchars(config('otserver.site.serverName')) }} ou ter a sua legitimação ou poderes.</li>

                        <li class="fw-bold">B) Caluniar ou agitação contra {{ htmlspecialchars(config('otserver.site.serverName')) }}</li>
                        <li>Manipulando o programa oficial cliente ou utilizando software adicional para jogar o jogo.</li>

                        <li class="fw-bold">C) Informações falsas para {{ htmlspecialchars(config('otserver.site.serverName')) }}</li>
                        <li>Intencionalmente dando informações erradas ou enganadoras para {{ htmlspecialchars(config('otserver.site.serverName')) }} em relatórios sobre violações de regras, reclamações, relatórios de bugs ou pedidos de apoio.</li>
                    </ul>
                </ul>
                <ul class="list-unstyled">
                    <li>5. Questões legais</li>
                    <ul>
                        <li class="fw-bold">A) Negociação Conta ou Sharing</li>
                        <li>Oferecendo dados de conta para os outros jogadores, aceitando dados de contas de outros jogadores ou permitir que outros jogadores para usar sua conta.</li>

                        <li class="fw-bold">B) Hacking</li>
                        <li>Roubar conta de outros jogadores ou dados pessoais.</li>

                        <li class="fw-bold">C) Atacar Serviço do {{ htmlspecialchars(config('otserver.site.serverName')) }}</li>
                        <li>Atacando, interrompendo ou prejudicando o funcionamento de qualquer servidor {{ htmlspecialchars(config('otserver.site.serverName')) }}, o jogo ou qualquer outra parte de serviços da Web Design Technologies.</li>

                        <li class="fw-bold">D) Violar a lei ou regulamentos</li>
                        <li>Violar qualquer lei aplicável, o Contrato de Serviço The LegenD's ou direitos de terceiros.</li>
                    </ul>
                </ul>
                <p>Violar ou tentar violar as regras do jogo pode levar a uma suspensão temporária de personagens e contas. Em casos graves, a remoção ou modificação de habilidades do personagem, atributos e pertences, bem como a remoção permanente de personagens e contas, sem qualquer compensação pode ser considerada. A sanção é baseada na gravidade da violação da regra e os registros anteriores do jogador. É determinada a critério da {{ htmlspecialchars(config('otserver.site.serverName')) }} e pode ser imposta sem qualquer aviso prévio.</p>
                <p>Estas regras podem ser alteradas a qualquer momento. Todas as mudanças serão anunciadas no site oficial.</p>
            </div>
        </div>
    </div>
</div>
