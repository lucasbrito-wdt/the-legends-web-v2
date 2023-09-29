<?php

use Illuminate\Database\Seeder;

class ShopOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('z_shop_offer')->insert([
            [
                'points' => 50,
                'itemid1' => 12500,
                'count1' => 20,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'item',
                'offer_description' => 'Item utilizado para metamorfose, podendo ser invocado Hazus, Chronos ou Kazard, ganhando atributos únicos, regenera life e mana a cada 2 seg baseados no fist e no magic level. Sua duração é de 30 segundos e utiliza 50 Souls Points.',
                'offer_name' => '20x Blood of God\'s',
                'pid' => 0,
                'bought' => 35
            ],
            [
                'points' => 50,
                'itemid1' => 6558,
                'count1' => 1,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'item',
                'offer_description' => 'Item utilizado para duplicar a experiencia obtida de criaturas, tem duração de 30 minutos. Obs: Se você morrer perdera todo o efeito do item.',
                'offer_name' => '1x Concentrated Demonic Blood',
                'pid' => 0,
                'bought' => 5,
            ],
            [
                'points' => 50,
                'itemid1' => 11518,
                'count1' => 50,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'item',
                'offer_description' => 'Item que acrescenta experiencia dependendo do Level + Magic Level ou Maior Skill, consumindo 250 souls a cada uso (Para level superior a 150).',
                'offer_name' => '50x Normal Elixir of Experiences',
                'pid' => 0,
                'bought' => 2,
            ],
            [
                'points' => 40,
                'itemid1' => 11517,
                'count1' => 50,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'item',
                'offer_description' => 'Item que acrescenta experiencia dependendo do Level + Magic Level ou Maior Skill, consumindo 250 souls a cada uso.',
                'offer_name' => '50x Small Elixir of Experience',
                'pid' => 0,
                'bought' => 6
            ],
            [
                'points' => 20,
                'itemid1' => 11515,
                'count1' => 100,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'item',
                'offer_description' => 'Item que quando usado, recupera 15% + 300 da mana total, delay 1 segundo. (pode ser usada em Hotkey)',
                'offer_name' => '100x Large Mana Potion',
                'pid' => 0,
                'bought' => 10
            ],
            [
                'points' => 20,
                'itemid1' => 11514,
                'count1' => 100,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'item',
                'offer_description' => 'Item que quando usado, recupera 15% + 300 da life total, delay 1 segundo. (pode ser usada em Hotkey)',
                'offer_name' => '100x Large Health Potion',
                'pid' => 0,
                'bought' => 1
            ],
            [
                'points' => 50,
                'itemid1' => 0,
                'count1' => 30,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'pacc',
                'offer_description' => '30 dias de Account premium, podendo acessar lugares, promoções, entre outras coisas onde somente premmys podem utilizar.',
                'offer_name' => 'Premium Account',
                'pid' => 0,
                'bought' => 0
            ],
            [
                'points' => 20,
                'itemid1' => 0,
                'count1' => 0,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'frags',
                'offer_description' => 'Removar todos os frags do seu personagem.',
                'offer_name' => 'Remover Frags',
                'pid' => 0,
                'bought' => 0,
            ],
            [
                'points' => 30,
                'itemid1' => 0,
                'count1' => 0,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'unban',
                'offer_description' => 'Retire o ban do seu personagem.',
                'offer_name' => 'Unban',
                'pid' => 0,
                'bought' => 0,
            ],
            [
                'points' => 20,
                'itemid1' => 0,
                'count1' => 0,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'redskull',
                'offer_description' => 'Remover seu redskull. Obs: Remove todos os seus frags.',
                'offer_name' => 'Redskull',
                'pid' => 0,
                'bought' => 0,
            ],
            [
                'points' => 100,
                'itemid1' => 0,
                'count1' => 0,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'changename',
                'offer_description' => 'Troca o nome do seu personagem. Obs: O Personagem tem que está deslogado.',
                'offer_name' => 'Change Name',
                'pid' => 0,
                'bought' => 0,
            ],
            [
                'points' => 100,
                'itemid1' => 9693,
                'count1' => 1,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'item',
                'offer_description' => 'Doll que você dizendo !addon + o nome do addon ganhar full addon. Ex: !addon warrior',
                'offer_name' => 'Addon Doll',
                'pid' => 0,
                'bought' => 3,
            ],
            [
                'points' => 100,
                'itemid1' => 9693,
                'count1' => 1,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'container',
                'offer_description' => 'Test container',
                'offer_name' => 'Test container',
                'pid' => 0,
                'bought' => 0,
            ],
            [
                'points' => 100,
                'itemid1' => 9693,
                'count1' => 1,
                'itemid2' => 0,
                'count2' => 0,
                'offer_type' => 'itemlogout',
                'offer_description' => 'Test itemlogout',
                'offer_name' => 'Test itemlogout',
                'pid' => 0,
                'bought' => 0,
            ]
        ]);
    }
}
