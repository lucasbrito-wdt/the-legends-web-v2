<?php

use Otserver\Website;
use Otserver\ConfigLUA;
use Otserver\ConfigPHP;

$config = [
    'server' => ['url' => 'thelegend'],
    'towns_list' => [
        0 => [
            1 => "Edoras",
            2 => "RookGuard",
            3 => "Pandora",
            4 => "Minas Tirith",
            5 => "Godoor",
            6 => "Carlin",
            7 => "Bree"
        ]
    ],
    'site' => [
        # General Options
        'serverIP' => '127.0.0.1',
        'serverName' => "The LegenD's ATS",
        'serverPath' => 'D:\\Projects\\Tibia\\The_Legends/',
        'useServerConfigCache' => false,
        'worlds' => [0 => "Terra Média"],
        'lang' => "pt-BR",
        'publickey' => "6LcJnB4aAAAAAN5__P8syMwdQJ54VIJsKxuc0hk-",
        'privkey' => "6LcJnB4aAAAAAMyiQl3pTJf8REGiZB2vHS4H3zO3",
        'langSystem' => 1,
        'chooseLang' => 'br',
        'passwordsEncryptions' => ['plain' => 'plain', 'md5' => 'md5', 'sha1' => 'sha1', 'sha256' => 'sha256', 'sha512' => 'sha512', 'vahash' => 'vahash'],

        # ACC Maker Options
        'access_news' => 6, // Nível de acesso necessário para editar notícias
        'access_tickers' => 6, // Nível de acesso necessário para criar / editar tickers
        'access_admin_panel' => 6, // Nível de acesso necessário para abrir o painel de administração
        'news_big_limit' => 6, // Limite de notícias sobre as últimas páginas de notícias
        'news_ticks_limit' => 6, // Limite de notícias ticker na última página de notícias
        'support_group_id' => 2, // Ele domina carta game show jogadores com ID de grupo 2 ou superior
        'show_creationdate' => 1, // Mostrar data de criação do personagem 1 = sim, 0 = não (quando o uso Procurar Player)
        'worldtransfer' => 1, //1-Activar / Desactivar Transferência mundo 0-Character
        'worldtransferprice' => 10, //Preço para Character Transferência do Mundo
        'transfermonths' => 6, //Meses para o qual você não pode fazer a transferência Mundo com um personagem

        # Create Account Options
        'one_email' => 1,
        'create_account_verify_mail' => 1, // USE-SE SOMENTE SE CONFIGURAR EMAIL E TRABALHAR
        'verify_code' => true, //Ativa o recaptcha? 1 = true, 0 = false
        'email_days_to_change' => 7, // Um e-mail pode ser usado apenas para criar uma conta 0/1
        'newaccount_premdays' => 7,
        'send_register_email' => 1, // Envie e-mail com chave rec (a chave é exibida na página de qualquer maneira quando gerar), configure 0 se alguém abusar para enviar spam

        # Create Character Options
        'newchar_looktype' => 136, //
        'newchar_cap' => 250,
        'newchar_town' => 2,
        'newchar_posx' => 873,
        'newchar_posy' => 866,
        'newchar_posz' => 7,
        'newchar_vocations' => [0 => [0 => 'Rook Sample']], // CHARACTER config, format: ID_of_vocation => 'Nome do personagem para copiar', carregue o nome da vocação de $ vocation_name [0] (abaixo)
        'newchar_towns' => [0 => [2 => 'Rook Guard']], // Amostra, se apenas rook: ['newchar_vocations'][0 => array(0 => 'Rook Sample');
        'max_players_per_account' => 7,  // max. Número de caracteres na conta

        # Emails Config
        'send_emails' => 1, // Envia e-mails ?

        # PAGE: characters.php
        'item_images_url' => 'images/items/', // URL das images dos Item
        'item_images_extension' => '.gif', // Extensão das imagens dos Item
        'outfit_images_url' => 'images/outfits/animoutfit.php', // URL de imagens das Outfit
        'flag_images_url' => 'images/flags/', // URL das Flags
        'flag_images_extension' => '.png', // Extensão das imagens das Flags
        'quests' => ['Annihilator' => 3737, 'Demon Helmet' => 5001, 'Pits of Inferno' => 3738],
        'show_marriage_info' => 0, // Mostrar casamento, 1 = yes, 0 = no
        'show_skills_info' => 1, //Jogadores mostram habilidades, 1 = yes, 0 = no
        'show_vip_status' => 0, // Mostrar status vip, 1 = yes, 0 = no
        'show_vip_storage' => 0, // O armazenamento de VIP
        'show_outfit' => 1, // Mostrar Roupas, 1 = yes, 0 = no
        'show_signature' => 1, // Mostrar Assinatura, 1 = yes, 0 = no

        # PAGE: accountmanagement.php
        'send_mail_when_change_password' => 1, // Você pode obter alguns Pontos Premium para a nova chave de rec
        'send_mail_when_generate_reckey' => 1, // Envie e-mail com nova senha ao alterar a senha para a conta, configure 0 se alguém abusar para enviar spam
        'generate_new_reckey' => 1, // Quando criar um jogador da conta deve usar o e-mail direito, ele receberá uma senha aleatória para conta como na Tíbia RL, 1 = sim, 0 = não
        'generate_new_reckey_price' => 15, // Deixe o jogador gerar nova chave de recuperação, ele receberá e-mail com nova chave de rec (não exibida na página, o hacker não pode gerar a chave de rec)

        # PAGE: guilds.php
        'guild_need_level' => 50, // Nível mínimo para criar Guild
        'guild_need_pacc' => 1,  // Precisa de conta premio para criar Guild
        'guild_image_size_kb' => 7500, // Tamanho máximo de imagem em KB
        'guild_description_chars_limit' => 1000, // Limite de descrição da guild
        'guild_description_lines_limit' => 6, // Limite de linhas, descrição, se ele tem mais linhas serão contanto Mostrou texto, sem 'entra'
        'guild_motd_chars_limit' => 150, // Limite de MOTD (no game show?)

        # PAGE: latestnews.php
        'news_limit' => 6,
        'screenoftheday' => 1, // Mostrar uma imagem do dia

        # PAGE: killstatistics.php
        'last_deaths_limit' => 40, // Máx. número de morte na morte última página

        # PAGE: team.php
        'groups_support' => [2, 3, 4, 5, 6], // Grupos de apoio

        # PAGE: highscores.php
        'groups_hidden' => [2, 3, 4, 5, 6], // Grupos escondidos
        'accounts_hidden' => [1], // Contas escondidas
        'players_group_id_block' => 4, // Não mostram estatísticas de jogadores com ID de grupo superior (ou igual), então (mostra tutores, professores seniores e jogadores normais)

        # PAGE: shopsystem.php
        'shop_system' => 1, // Mostrar a página loja do servidor? 1 = sim, 0 = não, usar somente se você instalou os scripts Lua de loja
        'verify_code_shop' => 0, // Mostrar o código verificar quando o jogador tentar verificar um código de prémio

        # PAGE: lostaccount.php
        'email_lai_sec_interval' => 180, // Tempo em segundos entre e-mails desta conta que perdeu interface de conta, bloquear spam

        # PAGE : download.php
        'download_windows' => '',
        'download_linux' => '',

        # Layout Config
        'download_page' => 1, // Mostrar a página de download? 1 = sim, 0 = não
        'serverinfo_page' => 1, // Mostrar informações página do servidor? 1 = Sim, 0 = Não
        'gallery_page' => 1, // Mostrar galeria página? 1 = yes, 0 = no
        'monsters_page' => 0, // Mostar monsters página? 1 = yes, 0 = no
    ],
    'pagseguro' => [
        'urlRedirect' => "http://localhost/The-LegenD's/index.php?p=donate",
        'urlNotification' => "http://localhost/The-LegenD's/index.php?p=donate",
        'productName' => 'The LegenDs Points',
        'productSymbol' => 'TLP',
        'productValue' => 1.00,
        'doublePoints' => false,
    ],
    'status' => []
];

if (Website::getWebsiteConfig()->getValue('useServerConfigCache')) {
    // use cache to make website load faster
    if (Website::fileExists('config/server.php')) {
        $tmp_php_config = new ConfigPHP('config/server.php');
        $config['server'] = $tmp_php_config->getConfig();
    } else {
        // if file isn't cached we should load .lua file and make .php cache
        $tmp_lua_config = new ConfigLUA($config['site']['serverPath'] . 'config.lua');
        $config['server'] = $tmp_lua_config->getConfig();
        $tmp_php_config = new ConfigPHP();
        $tmp_php_config->setConfig($tmp_lua_config->getConfig());
        $tmp_php_config->saveToFile('config/server.php');
    }
} else {
    $tmp_lua_config = new ConfigLUA(Website::getWebsiteConfig()->getValue('serverPath') . 'config.lua');
    $config['server'] = $tmp_lua_config->getConfig();
}

return $config;
