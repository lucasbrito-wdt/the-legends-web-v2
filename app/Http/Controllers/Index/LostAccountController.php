<?php
/*
 * @Author: Lucas Brito
 * @Data: 2021-01-08 10:23:23
 * @Último Editor: Lucas Brito
 * @Última Hora da Edição: 2021-01-31 17:07:11
 * @Caminho do Arquivo: \TheLegends\app\Http\Controllers\Index\LostAccountController.php
 * @Descrição:
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Mail\LostAccountMail;
use App\Mail\SetNewPasswordMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Otserver\Account;
use Otserver\Functions;
use Otserver\Player;

class LostAccountController extends Controller
{
    public function index()
    {
        return view('index.lostaccount.index');
    }

    public function step1(Request $request)
    {
        $errorsView = false;
        $viewEmail = false;
        $viewReckey = false;
        $viewEmailContent = '';
        $viewReckeyContent = '';
        $view = 'index.lostaccount.step1';

        $nick = request()->input('nick');
        $actionType = $request->input('action_type');

        if (!Functions::check_name($nick)) {
            $errorsView = true;
            return view($view, [
                'actionType' => $actionType,
                'nick' => $nick,
                'errorsView' => $errorsView,
                'viewEmail' => $viewEmail,
                'viewReckey' => $viewReckey,
                'viewEmailContent' => $viewEmailContent,
                'viewReckeyContent' => $viewReckeyContent
            ])->withErrors('Formato de nome de jogador inválido. Se você tiver outros personagens na conta, tente com outro nome.');
        }

        $player = new Player();
        $account = new Account();
        $player->find($nick);

        if ($player->isLoaded())
            $account = $player->getAccount();

        if (!$account->isLoaded()) {
            $errorsView = true;
            return view($view, [
                'actionType' => $actionType,
                'nick' => $nick,
                'errorsView' => $errorsView,
                'viewEmail' => $viewEmail,
                'viewReckey' => $viewReckey,
                'viewEmailContent' => $viewEmailContent,
                'viewReckeyContent' => $viewReckeyContent
            ])->withErrors('Jogador ou conta do jogador <b> ' . htmlspecialchars($nick) . ' </b> não existe.');
        }

        if ($actionType == "email") {
            if ($account->getCustomField('next_email') < time()) {
                $viewEmail = true;
                return view($view, [
                    'actionType' => $actionType,
                    'nick' => $nick,
                    'errorsView' => $errorsView,
                    'viewEmail' => $viewEmail,
                    'viewReckey' => $viewReckey,
                    'viewEmailContent' => $viewEmailContent,
                    'viewReckeyContent' => $viewReckeyContent
                ]);
            } else {
                $insec = $account->getCustomField('next_email') - time();
                $minutesleft = floor($insec / 60);
                $secondsleft = $insec - ($minutesleft * 60);
                $timeleft = $minutesleft . ' minutes ' . $secondsleft . ' seconds';
                $viewEmailContent = 'Conta do personagem selecionado (<b> ' . htmlspecialchars($nick) . ' </b>) e-mail recebido por último ' . ceil(config('otserver.site.email_lai_sec_interval') / 60) . ' minutos. Você deve esperar ' . $timeleft . ' antes de usar a interface de conta perdida novamente.';

                return view($view, [
                    'actionType' => $actionType,
                    'nick' => $nick,
                    'errorsView' => $errorsView,
                    'viewEmail' => $viewEmail,
                    'viewReckey' => $viewReckey,
                    'viewEmailContent' => $viewEmailContent,
                    'viewReckeyContent' => $viewReckeyContent
                ]);
            }
        } else if ($actionType == "reckey") {
            $account_key = $account->getKey();
            if (!empty($account_key)) {
                $viewReckey = true;
                return view($view, [
                    'actionType' => $actionType,
                    'nick' => $nick,
                    'errorsView' => $errorsView,
                    'viewEmail' => $viewEmail,
                    'viewReckey' => $viewReckey,
                    'viewEmailContent' => $viewEmailContent,
                    'viewReckeyContent' => $viewReckeyContent
                ]);
            } else {
                $errorsView = true;
                return view($view, [
                    'actionType' => $actionType,
                    'nick' => $nick,
                    'errorsView' => $errorsView,
                    'viewEmail' => $viewEmail,
                    'viewReckey' => $viewReckey,
                    'viewEmailContent' => $viewEmailContent,
                    'viewReckeyContent' => $viewReckeyContent
                ])->withErrors('A conta deste personagem não tem chave de recuperação!');
            }
        }
    }

    public function step2(Request $request)
    {
        $errorsView = false;
        $view = 'index.lostaccount.step2';

        $nick = request()->input('nick');
        $rec_key = request()->input('key');

        if (!Functions::check_name($nick)) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('Formato de nome de jogador inválido. Se você tiver outros personagens na conta, tente com outro nome.');
        }

        $player = new Player();
        $account = new Account();
        $player->find($nick);

        if ($player->isLoaded())
            $account = $player->getAccount();

        if (!$account->isLoaded()) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('Jogador ou conta do jogador <b> ' . htmlspecialchars($nick) . ' </b> não existe.');
        }

        $account_key = $account->getCustomField('key');
        if (empty($account_key)) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('A conta deste personagem não tem chave de recuperação!');
        }

        if ($account_key != $rec_key) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('Chave de recuperação errada!');
        }

        return view($view, [
            'nick' => $nick,
            'rec_key' => $rec_key,
            'errorsView' => $errorsView,
        ]);
    }

    public function step3(Request $request)
    {
        $errorsView = false;
        $view = 'index.lostaccount.step3';

        $rec_key = request()->input('key');
        $nick = request()->input('nick');
        $new_pass = request()->input('passor');
        $new_email = request()->input('email');

        if (!Functions::check_name($nick)) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('Formato de nome de jogador inválido. Se você tiver outros personagens na conta, tente com outro nome.');
        }

        $player = new Player();
        $account = new Account();
        $player->find($nick);

        if ($player->isLoaded())
            $account = $player->getAccount();

        if (!$account->isLoaded()) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('Jogador ou conta do jogador <b> ' . htmlspecialchars($nick) . ' </b> não existe.');
        }

        $account_key = $account->getCustomField('key');

        if (empty($account_key)) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('A conta deste personagem não tem chave de recuperação!');
        }

        if ($account_key != $rec_key) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('Chave de recuperação errada!');
        }

        if (!Functions::check_password($new_pass)) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('Formato de senha incorreto. Use apenas a-Z, A-Z, 0-9');
        }

        if (!Functions::check_mail($new_email)) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'rec_key' => $rec_key,
                'errorsView' => $errorsView,
            ])->withErrors('Formato de e-mail incorreto.');
        }

        $account->setEMail($new_email);
        $account->setPassword($new_pass);
        $account->save();

        return view($view, [
            'new_pass' => $new_pass,
            'new_email' => $new_email,
            'account' => $account,
            'errorsView' => $errorsView,
        ]);
    }

    public function checkcode(Request $request)
    {
        $errorsView = false;
        $emptyView = false;
        $view = 'index.lostaccount.checkcode';

        $code = request()->input('code');
        $character = request()->input('character');

        if (empty($code) || empty($character)) {
            $emptyView = true;
            return view($view, [
                'emptyView' => $emptyView,
                'errorsView' => $errorsView,
            ]);
        } else {
            $player = new Player();
            $account = new Account();
            $player->find($character);

            if ($player->isLoaded())
                $account = $player->getAccount();

            if (!$account->isLoaded()) {
                $errorsView = true;
                return view($view, [
                    'errorsView' => $errorsView,
                ])->withErrors('Jogador ou conta do jogador <b> ' . htmlspecialchars($character) . ' </b> não existe.');
            }

            if ($account->getCustomField('email_code') != $code) {
                $errorsView = true;
                return view($view, [
                    'errorsView' => $errorsView,
                ])->withErrors('Código errado para alterar a senha.');
            }
        }
    }

    public function sendcode(Request $request)
    {
        $errorsView = false;
        $view = 'index.lostaccount.sendcode';

        $nick = request()->input('nick');
        $email = request()->input('email');

        if (!Functions::check_name($nick)) {
            $errorsView = true;
            return view($view, [
                'nick' => $nick,
                'errorsView' => $errorsView,
            ])->withErrors('Formato de nome de jogador inválido. Se você tiver outros personagens na conta, tente com outro nome.');
        }

        $player = new Player();
        $account = new Account();
        $player->find($nick);

        if ($player->isLoaded())
            $account = $player->getAccount();

        if (!$account->isLoaded()) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
            ])->withErrors('Jogador ou conta do jogador <b> ' . htmlspecialchars($nick) . ' </b> não existe.');
        }

        if ($account->getEMail() == $email) {
            $acceptedChars = '123456789zxcvbnmasdfghjklqwertyuiop';
            $newcode = NULL;
            for ($i = 0; $i < 30; $i++) {
                $newcode .= $acceptedChars[mt_rand(0, 33)];
            }
        } else {
        }
    }

    public function setnewpassword(Request $request)
    {
        $errorsView = false;
        $view = 'index.lostaccount.setnewpassword';

        $code = request()->input('code');
        $character = request()->input('character');
        $newpassword = request()->input('passor');

        if (empty($code) || empty($character) || empty($newpassword)) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
            ]);
        }

        $player = new Player();
        $account = new Account();
        $player->find($character);

        if ($player->isLoaded())
            $account = $player->getAccount();

        if (!$account->isLoaded()) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
            ])->withErrors('Jogador ou conta do jogador <b> ' . htmlspecialchars($character) . ' </b> não existe.');
        }

        if ($account->getCustomField('email_code') != $code) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
            ])->withErrors('Código errado para alterar a senha.');
        }

        if (!Functions::check_password($newpassword)) {
            $errorsView = true;
            return view($view, [
                'errorsView' => $errorsView,
            ])->withErrors('Formato de senha incorreto. Use apenas a-Z, A-Z, 0-9');
        }

        $account->setPassword($newpassword);
        $account->set('email_code', '');
        $account->save();

        if (config('otserver.site.send_emails')) {
            try {
                Mail::send(new SetNewPasswordMail([
                    'to' => [
                        'address' => $account->getMail(),
                        'name' => $account->getRLName()
                    ],
                    'from' => [
                        'address' => config('mail.from.address'),
                        'name' => config('otserver.server.serverName')
                    ],
                    'subject' => "Nova senha para sua conta",
                    'name' => $account->getName(),
                    'newpassword' => $newpassword
                ]));
            } catch (Exception $e) {
            }
        }

        return view($view, [
            'account' => $account,
            'newpassword' => $newpassword,
            'errorsView' => $errorsView,
        ]);
    }
}
