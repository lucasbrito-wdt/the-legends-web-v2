<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Mail\GenerateReckey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Otserver\Visitor;
use Otserver\Functions;
use Otserver\Player;

class AccountManagementController extends Controller
{
    /**
     * GERENTE DE CONTAS
     *
     * @param Request $request
     * @return view
     */
    public function index()
    {
        $account_logged = Visitor::getAccount();
        $welcome_msg = 'Bem vindo a sua conta!';

        if (!empty($redirect))
            return redirect("/{$redirect}");

        return view('index.accountmanagement.index')
            ->with(compact('account_logged'))
            ->with(compact('welcome_msg'));
    }

    public function login(Request $request)
    {
        if ($request->method() == "GET") {
            return view('index.accountmanagement.login');
        }

        if ($request->method() == "POST") {
            $response = [];

            Visitor::setAccount($request->input('account_login'));
            Visitor::setPassword($request->input('password_login'));
            Visitor::login();

            switch (Visitor::getLoginState()) {
                case Visitor::LOGINSTATE_NO_ACCOUNT:
                    $response = ['exception' => 'Conta não existente.'];
                    break;
                case Visitor::LOGINSTATE_WRONG_PASSWORD:
                    $response = ['exception' => 'A senha está incorreta.'];
                    break;
            }

            if (Visitor::isLogged())
                return redirect('/accountmanagement');
            else
                return view('index.accountmanagement.login')->withErrors($response);
        }
    }

    public function logout()
    {
        Visitor::logout();
        return view('index.accountmanagement.logout');
    }

    /**
     * CHANGE PASSWORD
     *
     * @param Request $request
     * @return view
     */
    public function changePassword(Request $request)
    {
        $view = view('index.accountmanagement.changepassword');

        if ($request->method() == "GET") {
            return $view;
        }

        if ($request->method() == "POST") {
            $old_password = $request->input('oldpassword');
            $new_password = $request->input('newpassword');
            $new_password2 = $request->input('newpassword2');

            $messages = [
                'oldpassword.is_valid_password' => 'A senha atual está incorreta!',
                'newpassword.check_password' => 'Nova senha contém caracteres ilegais (a-z, A-Z e 0-9 apenas!) Ou comprimento.',
                'newpassword2.same' => "As novas senhas não são iguais!",
            ];

            $rules = [
                'oldpassword' => ['required', 'min:6', 'max:29', 'is_valid_password:oldpassword'],
                'newpassword' => ['required', 'min:6', 'max:29', 'check_password:newpassword'],
                'newpassword2' => ['required', 'min:6', 'max:29', 'same:newpassword'],
            ];

            $names = [
                'oldpassword' => "Senha Atual",
                'newpassword' => "Nova Senha",
                'newpassword2' => "Nova senha novamente"
            ];

            Validator::extend('check_password', function ($attribute, $value, $parameters, $validator) {
                return Functions::check_password($value);
            });

            Validator::extend('is_valid_password', function ($attribute, $value, $parameters, $validator) {
                return Visitor::getAccount()->isValidPassword($value);
            });

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                $view->with('new_password', $new_password)
                    ->with('new_password2', $new_password2)
                    ->with('old_password', $old_password)
                    ->withErrors($validator);
                return $view;
            }

            Visitor::getAccount()->setPassword($new_password);
            Visitor::setPassword($new_password);
            Visitor::getAccount()->save();

            return $view->withErrors('Senha atualizada com sucesso!');
        }
    }

    /**
     * CHANGE E-MAIL
     *
     * @param Request $request
     * @return view
     */
    public function changeemail(Request $request)
    {
        $view = view('index.accountmanagement.changeemail');
        $account_logged = Visitor::getAccount();
        $change_email_errors = [];
        $success = false;

        $account_email_new_time = $account_logged->getCustomField("email_new_time");

        if ($account_email_new_time > 10) {
            $account_email_new = $account_logged->getCustomField("email_new");
        }

        if ($request->method() == "GET") {
            return $view
                ->with(compact('account_email_new'))
                ->with(compact('account_email_new_time'))
                ->with(compact('account_logged'))
                ->with(compact('success'));
        }

        if ($request->method() == "POST") {

            $changeemailsave = $request->input('changeemailsave');
            $emailchangecancel = $request->input('emailchangecancel');
            $account_email_new = $request->input('new_email');
            $post_password = $request->input('password');

            if ($emailchangecancel == 1) {
                $account_logged->setCustomField("email_new", "");
                $account_logged->setCustomField("email_new_time", 0);
                $account_logged->save();
            }

            $messages = [
                'new_email.check_email' => 'E-mail não é correto.',
                'password.is_valid_password' => 'Sua senha da conta está incorreta.'
            ];

            $rules = [
                'new_email' => ['required', 'check_email:new_email'],
                'password' => ['required', 'min:6', 'max:29', 'is_valid_password:password'],
            ];

            $names = [
                'new_email' => "Novo E-mail",
                'password' => "Senha"
            ];

            Validator::extend('check_email', function ($attribute, $value, $parameters, $validator) {
                return Functions::check_mail($value);
            });

            Validator::extend('is_valid_password', function ($attribute, $value, $parameters, $validator) {
                return Visitor::getAccount()->isValidPassword($value);
            });

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                $view->with('new_email', $account_email_new)
                    ->with('password', $post_password)
                    ->with(compact('account_email_new'))
                    ->with(compact('account_email_new_time'))
                    ->with(compact('success'))
                    ->withErrors($validator);
                return $view;
            }

            if ($account_email_new_time < 10) {
                if (empty($change_email_errors)) {
                    $account_email_new_time = time() + config('otserver.site.email_days_to_change') * 24 * 3600;
                    $account_logged->setCustomField("email_new", $account_email_new);
                    $account_logged->setCustomField("email_new_time", $account_email_new_time);
                }
            } else {
                if ($account_email_new_time < time()) {
                    $account_logged->setCustomField("email_new", "");
                    $account_logged->setCustomField("email_new_time", 0);
                    $account_logged->setEmail($account_email_new);
                    $account_logged->save();
                }
            }

            $success = true;

            return $view
                ->with(compact('success'))
                ->with(compact('changeemailsave'))
                ->with(compact('emailchangecancel'))
                ->with(compact('account_email_new'))
                ->with(compact('account_email_new_time'))
                ->with(compact('account_logged'))
                ->withErrors($change_email_errors);
        }
    }

    /**
     * CHANGE PUBLIC INFORMATION (about account owner)
     *
     * @param Request $request
     * @return view
     */
    public function changeinfo(Request $request)
    {
        $view = view('index.accountmanagement.changeinfo');
        $account_logged = Visitor::getAccount();
        $account_rlname = $account_logged->getRLName();
        $account_location = $account_logged->getLocation();

        if ($request->method() == "GET") {
            return $view
                ->with(compact('account_rlname'))
                ->with(compact('account_location'));
        }

        if ($request->method() == "POST") {
            $info_rlname = $request->input('info_rlname');
            $info_location = $request->input('info_location');

            $rules = [
                'info_rlname' => ['required', 'max:50'],
                'info_location' => ['required', 'max:50'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $view->with('account_rlname', $info_rlname)
                    ->with('account_location', $info_location)
                    ->withErrors($validator);
                return $view;
            }

            $account_logged->setRLName($info_rlname);
            $account_logged->setLocation($info_location);
            $account_logged->save();

            Visitor::setAccountLoaded($account_logged);

            return redirect("accountmanagement/changeinfo")->with('account_rlname', $info_rlname)
                ->with('account_location', $info_location)
                ->withErrors(['Suas informações pública foi alterada com sucesso.']);
        }
    }

    /**
     * GENERATE RECOVERY KEY
     *
     * @param Request $request
     * @return view
     */
    public function registeraccount(Request $request)
    {
        $view = view("index.accountmanagement.registeraccount");
        $success = false;
        $account_logged = Visitor::getAccount();
        $old_key = $account_logged->getRecoveryKey();

        if ($request->method() == "GET") {
            return $view->with(compact('old_key'))
                ->with(compact('success'));
        }

        if ($request->method() == "POST") {
            $requestAll = $request->all();
            $requestAll = array_merge($requestAll, ['old_key' => $old_key]);

            $messages = [
                'reg_password.is_valid_password' => 'Sua senha da conta está incorreta.',
                'old_key.is_rec' => 'Sua conta já está registrada.'
            ];

            $rules = [
                'reg_password' => ['required', 'min:6', 'max:29', 'is_valid_password:reg_password'],
                'old_key' => ['is_rec:old_key']
            ];

            $names = [
                'reg_password' => "Senha",
                'old_key' => "Chave de recuperação"
            ];

            Validator::extend('is_rec', function ($attribute, $value, $parameters, $validator) {
                if (empty($value))
                    return true;
                return false;
            });

            Validator::extend('is_valid_password', function ($attribute, $value, $parameters, $validator) {
                return Visitor::getAccount()->isValidPassword($value);
            });

            $validator = Validator::make($requestAll, $rules, $messages);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                $view->with(compact('success'))
                    ->withErrors($validator);
                return $view;
            }

            $acceptedChars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
            $max = strlen($acceptedChars) - 1;
            $new_rec_key = NULL;
            for ($i = 0; $i < 10; $i++) {
                $new_rec_key .= $acceptedChars[mt_rand(0, $max)];
            }

            $account_logged->setRecoveryKey($new_rec_key);
            $account_logged->save();

            if (config('otserver.site.send_emails') && config('otserver.site.send_mail_when_generate_reckey')) {
                @Mail::send(new GenerateReckey([
                    'to' => [
                        'address' => $account_logged->getMail(),
                        'name' => $account_logged->getRLName()
                    ],
                    'from' => [
                        'address' => config('mail.from.address'),
                        'name' => config('otserver.server.serverName')
                    ],
                    'subject' => "Chave de Recuperação",
                    'new_rec_key' => $new_rec_key
                ]));
            }

            $success = true;

            return view("index.accountmanagement.registeraccount")
                ->with('new_rec_key', $new_rec_key)
                ->with(compact('success'))
                ->withErrors(['Suas informações pública foi alterada com sucesso.']);
        }
    }

    /**
     * GENERATE NEW RECOVERY KEY
     *
     * @param Request $request
     * @return view
     */
    public function newreckey(Request $request)
    {
        $view = view("index.accountmanagement.newreckey");
        $success = false;
        $account_logged = Visitor::getAccount();
        $reckey = $account_logged->getRecoveryKey();
        $points = $account_logged->getCustomField('premium_points');

        if ($request->method() == "GET") {
            return $view->with(compact('reckey'))
                ->with(compact('points'))
                ->with(compact('success'));
        }

        if ($request->method() == "POST") {
            $requestAll = $request->all();
            $requestAll = array_merge($requestAll, ['reckey' => $reckey, 'points' => $points]);

            $messages = [
                'reg_password.is_valid_password' => 'Sua senha da conta está incorreta.',
                'points.check_points' => "Você precisa de " . config('otserver.site.generate_new_reckey_price') . " " . config('otserver.pagseguro.productName') . " para gerar uma nova chave de recuperação. Você tem {$points} " . config('otserver.pagseguro.productName')
            ];

            $rules = [
                'reg_password' => ['required', 'min:6', 'max:29', 'is_valid_password:reg_password'],
                'points' => ['check_points:points']
            ];

            $names = [
                'reg_password' => "Senha",
                'points' => "Chave de recuperação"
            ];

            Validator::extend('check_points', function ($attribute, $value, $parameters, $validator) {
                if ($value >= config('otserver.site.generate_new_reckey_price'))
                    return true;
                return false;
            });

            Validator::extend('is_valid_password', function ($attribute, $value, $parameters, $validator) {
                return Visitor::getAccount()->isValidPassword($value);
            });

            $validator = Validator::make($requestAll, $rules, $messages);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                $view->with(compact('reckey'))
                    ->with(compact('points'))
                    ->with(compact('success'))
                    ->withErrors($validator);
                return $view;
            }

            $acceptedChars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
            $max = strlen($acceptedChars) - 1;
            $new_rec_key = NULL;
            for ($i = 0; $i < 10; $i++) {
                $new_rec_key .= $acceptedChars[mt_rand(0, $max)];
            }
            $account_logged->setRecoveryKey($new_rec_key);
            $account_logged->set("premium_points", $account_logged->get("premium_points") - config('otserver.site.generate_new_reckey_price'));
            $account_logged->save();

            if (config('otserver.site.send_emails') && config('otserver.site.send_mail_when_generate_reckey')) {
                @Mail::send(new GenerateReckey([
                    'to' => [
                        'address' => $account_logged->getMail(),
                        'name' => $account_logged->getRLName()
                    ],
                    'from' => [
                        'address' => config('mail.from.address'),
                        'name' => config('otserver.server.serverName')
                    ],
                    'subject' => "Chave de Recuperação",
                    'new_rec_key' => $new_rec_key
                ]));
            }

            $success = true;

            return $view
                ->with('reckey', $new_rec_key)
                ->with(compact('points'))
                ->with(compact('success'))
                ->withErrors(['Suas informações pública foi alterada com sucesso.']);
        }
    }

    /**
     * CHANGE CHARACTER COMMENT
     *
     * @param Request $request
     * @return view
     */
    public function changecomment(Request $request, $name)
    {
        $view = view("index.accountmanagement.changecomment");
        $success = false;
        $account_logged = Visitor::getAccount();
        $player_name = urldecode($name);

        $player = new Player();
        $player->find($player_name);

        if ($player->isLoaded()) {
            $player_account = $player->getAccount();
            if ($account_logged->getId() != $player_account->getId()) {
                $success = true;
                $view->with(compact('success'))
                    ->with(compact('player_name'))
                    ->withErrors("Personagem com nome: {$player_name} não é da sua conta.");
            }
        } else {
            $success = true;
            $view->with(compact('success'))
                ->with(compact('player_name'))
                ->withErrors("Personagem com nome: {$player_name} não existe.");
        }

        if ($request->method() == "GET") {
            return $view->with(compact('success'))
                ->with(compact('player_name'))
                ->with(compact('player'));
        }

        if ($request->method() == "POST") {
            $new_comment = $request->input('comment');
            $new_hideacc = $request->input('new_hideacc');

            $rules = [
                'new_hideacc' => ['required'],
                'comment' => ['max:2000']
            ];

            $names = [
                'new_hideacc' => "Ocultar as informações do personagem",
                'comment' => "Comentário"
            ];

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                return $view->with(compact('success'))
                    ->withErrors($validator);
            }

            $player->setHidden($new_hideacc);
            $player->setComment($new_comment);
            $player->save();

            return redirect(route('accountmanagement.changecomment', ['name' => $player_name]))->with(compact('success'))
                ->with(compact('player_name'))
                ->with(compact('player'))
                ->withErrors(['As informações do personagem foi alterada com sucesso.']);
        }
    }

    /**
     * NEW NICK - Definir nova proposta de nick
     *
     * @param Request $request
     * @return view
     */
    public function newnick(Request $request, $name)
    {
        $view = view("index.accountmanagement.newnick");
        $success = false;
        $account_logged = Visitor::getAccount();
        $name = urldecode($name);

        $player = new Player();
        $player->find($name);

        if ($player->isLoaded() && $player->isNameLocked()) {
            $success = true;
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors('Personagem com este nome não existe ou não é o nome bloqueado.');
        }

        if ($player->isLoaded()) {
            $player_account = $player->getAccount();
            if ($account_logged->getId() != $player_account->getId()) {
                $success = true;
                return $view->with(compact('success'))
                    ->with(compact('name'))
                    ->withErrors("Personagem com nome: {$name} não é da sua conta.");
            }
        } else {
            $success = true;
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors("Personagem com nome: {$name} não existe.");
        }

        if ($player->getOldName()) {
            $success = true;
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors("Você já definido novo nome para esse personagem ({$player->getOldName()}). Você deve esperar até que GM aceitar/rejeitar a sua proposta.");
        }

        if ($player->isOnline()) {
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors("Este personagem está online.");
        }

        if ($request->method() == "GET") {
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->with(compact('player'));
        }

        if ($request->method() == "POST") {
            $name_new = stripslashes(ucwords(strtolower(trim($request->input('name_new')))));

            $rules = [
                'name_new' => ['required'],
            ];

            $names = [
                'name_new' => "Novo Nome",
            ];

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                return $view->with(compact('success'))
                    ->with(compact('name'))
                    ->with(compact('player'))
                    ->withErrors($validator);
            }

            $player->setOldName($name_new);
            $player->save();

            return $view->with(compact('success'))
                ->with(compact('name'))
                ->with(compact('player'))
                ->withErrors("Nome alterado com sucesso!");
        }
    }

    /**
     * DELETE Personagem da conta
     *
     * @param Request $request
     * @return view
     */
    public function deletecharacter(Request $request, $name)
    {
        $view = view("index.accountmanagement.deletecharacter");
        $success = false;
        $account_logged = Visitor::getAccount();
        $name = urldecode($name);

        $player = new Player();
        $player->find($name);

        if ($player->isLoaded()) {
            $player_account = $player->getAccount();
            if ($account_logged->getId() != $player_account->getId()) {
                $success = true;
                return $view->with(compact('success'))
                    ->with(compact('name'))
                    ->withErrors("Personagem com nome: {$name} não é da sua conta.");
            }
        } else {
            $success = true;
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors("Personagem com nome: {$name} não existe.");
        }

        if ($player->isOnline()) {
            $success = true;
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors("Este personagem está online.");
        }

        if ($player->isDeleted()) {
            $success = true;
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors("Este personagem já foi solicitado a exclusão.");
        }

        if ($request->method() == "GET") {
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->with(compact('player'));
        }

        if ($request->method() == "POST") {
            $password = $request->input('password');

            $messages = [
                'password.is_valid_password' => 'Sua senha da conta está incorreta.',
            ];

            $rules = [
                'password' => ['required', 'min:6', 'max:29', 'is_valid_password:password'],
            ];

            $names = [
                'password' => "Senha",
            ];

            Validator::extend('is_valid_password', function ($attribute, $value, $parameters, $validator) {
                return Visitor::getAccount()->isValidPassword($value);
            });

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                return $view->with(compact('success'))
                    ->with(compact('name'))
                    ->withErrors($validator);
            }

            $player->setDeleted(1);
            $player->save();

            return $view->with(compact('success'))
                ->with(compact('name'))
                ->with(compact('player'))
                ->withErrors("O personagem {$name} foi excluído.");
        }
    }

    /**
     * UNDELETE Personagem da conta
     *
     * @param Request $request
     * @return view
     */
    public function undelete(Request $request, $name)
    {
        $view = view("index.accountmanagement.undelete");
        $success = false;
        $account_logged = Visitor::getAccount();
        $name = urldecode($name);

        $player = new Player();
        $player->find($name);

        if ($player->isLoaded()) {
            $player_account = $player->getAccount();
            if ($account_logged->getId() != $player_account->getId()) {
                $success = true;
                return $view->with(compact('success'))
                    ->with(compact('name'))
                    ->withErrors("Personagem com nome: {$name} não é da sua conta.");
            }
        } else {
            $success = true;
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors("Personagem com nome: {$name} não existe.");
        }

        if ($player->isOnline()) {
            $success = true;
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors("Este personagem está online.");
        }

        if (!$player->isDeleted()) {
            $success = true;
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->withErrors("Este personagem já foi desfeita a exclusão.");
        }

        if ($request->method() == "GET") {
            return $view->with(compact('success'))
                ->with(compact('name'))
                ->with(compact('player'));
        }

        if ($request->method() == "POST") {
            $password = $request->input('password');

            $messages = [
                'password.is_valid_password' => 'Sua senha da conta está incorreta.',
            ];

            $rules = [
                'password' => ['required', 'min:6', 'max:29', 'is_valid_password:password'],
            ];

            $names = [
                'password' => "Senha",
            ];

            Validator::extend('is_valid_password', function ($attribute, $value, $parameters, $validator) {
                return Visitor::getAccount()->isValidPassword($value);
            });

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                return $view->with(compact('success'))
                    ->with(compact('name'))
                    ->withErrors($validator);
            }

            $player->setDeleted(0);
            $player->save();

            return redirect(route('index.accountmanagement.undelete'))->with(compact('success'))
                ->with(compact('name'))
                ->with(compact('player'))
                ->withErrors("A exclusão do personagem {$name} foi desfeita.");
        }
    }

    /**
     * CREATE CHARACTER on account
     *
     * @param Request $request
     * @return view
     */
    public function createcharacter(Request $request, $world = 0)
    {
        $view = view("index.accountmanagement.createcharacter");
        $success = false;
        $account_logged = Visitor::getAccount();
        $number_of_players_on_account = $account_logged->getPlayers(true)->count();

        if (!array_key_exists($world, config('otserver.site.worlds'))) {
            $success = true;
            $view->with(compact('success'))
                ->with(compact('world'))
                ->withErrors("Mundo não existe.");
        } else {
            $world = 0;
        }

        if ($request->method() == "GET") {
            return $view->with(compact('success'))
                ->with(compact('world'))
                ->with(compact('account_logged'));
        }

        if ($request->method() == "POST") {
            $newchar_name = $request->input('newchar_name');
            $newchar_sex = $request->input('newchar_sex', 0);
            $newchar_vocation = $request->input('newchar_vocation', 0);
            $newchar_town = $request->input('newchar_town', 0);

            $check_name = $this->checkname($request, $newchar_name);
            if ($check_name->getData()->code == 400) {
                return $view->with(compact('success'))
                    ->with(compact('world'))
                    ->with(compact('account_logged'))
                    ->withErrors($check_name->getData()->message);
            }

            $requestAll = [
                "newchar_name" => $newchar_name,
                "newchar_sex" => $newchar_sex,
                "newchar_vocation" => $newchar_vocation,
                "newchar_town" => $newchar_town,
                "max_players_per_account" => $number_of_players_on_account,
                "world" => $world
            ];

            $messages = [
                'newchar_name.is_check_player_exist' => "Este nome já está sendo usado . Por favor, escolha outro nome !",
                'newchar_sex.is_sex' => "Sexo deve ser igual 0 (Mulher) ou 1 (Homem).",
                'newchar_vocation.is_vocation' => "Por favor, selecione uma vocação para o seu personagem.",
                'newchar_vocation.is_valid_vocation' => "Vocação desconhecido. Por favor, preencha formulário novamente.",
                'newchar_town.is_town' => "Por favor seleccione uma cidade para o seu personagem.",
                'newchar_town.is_valid_town' => 'Por favor seleccione cidade válida.',
                'max_players_per_account.is_max_players_per_account' => "Você tem muitos personagens em sua conta ({$number_of_players_on_account}/" . config('otserver.site.max_players_per_account') . ")",
                'world.is_check_world' => "Mundo não existe."
            ];

            $rules = [
                'newchar_name' => ['required', 'min:8', 'max:20', "is_check_player_exist:newchar_name"],
                'newchar_sex' => ['required', "is_sex:newchar_sex"],
                'newchar_vocation' => ["is_vocation:newchar_vocation,{$world}", "is_valid_vocation:newchar_vocation,{$world}"],
                'newchar_town' => ["is_town:newchar_town,{$world}", "is_valid_town:newchar_town,{$world}"],
                'max_players_per_account' => ["is_max_players_per_account:max_players_per_account"],
                'world' => ["is_check_world:world"]
            ];

            $names = [
                'newchar_name' => "Nome do personagem",
                'newchar_sex' => "Sexo",
                'newchar_vocation' => "Vocação"
            ];

            Validator::extend('is_sex', function ($attribute, $value, $parameters, $validator) {
                if ($value != "0" && $value != "1")
                    return false;
                return true;
            });

            Validator::extend('is_vocation', function ($attribute, $value, $parameters, $validator) {
                if (!array_key_exists($parameters[1], config('otserver.site.worlds')))
                    return false;
                if (count(config('otserver.site.newchar_vocations')[$parameters[1]]) > 1) {
                    if (empty($value))
                        return false;
                } else {
                    return true;
                }
            });

            Validator::extend('is_valid_vocation', function ($attribute, $value, $parameters, $validator) {
                if (!array_key_exists($parameters[1], config('otserver.site.worlds')))
                    return false;
                if (count(config('otserver.site.newchar_vocations')[$parameters[1]]) > 2) {
                    $newchar_vocation_check = false;
                    foreach (config('otserver.site.newchar_vocations')[$parameters[1]] as $char_vocation_key => $sample_char)
                        if ($value == $char_vocation_key)
                            $newchar_vocation_check = true;
                    if (!$newchar_vocation_check)
                        return false;
                } else {
                    if ($value > 0)
                        return false;
                }
                return true;
            });

            Validator::extend('is_town', function ($attribute, $value, $parameters, $validator) {
                if (!array_key_exists($parameters[1], config('otserver.site.worlds')))
                    return false;
                if (count(config('otserver.site.newchar_towns')[$parameters[1]]) > 1) {
                    if (empty($parameters[0]))
                        return false;
                } else {
                    return true;
                }
            });

            Validator::extend('is_valid_town', function ($attribute, $value, $parameters, $validator) {
                if (!array_key_exists($parameters[1], config('otserver.site.worlds')))
                    return false;
                if (!array_key_exists((int)$value, config('otserver.site.newchar_towns')[$parameters[1]]))
                    return false;
                return true;
            });

            Validator::extend('is_check_player_exist', function ($attribute, $value, $parameters, $validator) {
                $check_name_in_database = new Player();
                $check_name_in_database->find($value);
                if ($check_name_in_database->isLoaded())
                    return false;
                return true;
            });

            Validator::extend('is_max_players_per_account', function ($attribute, $value, $parameters, $validator) {
                if ($value >= config('otserver.site.max_players_per_account'))
                    return false;
                return true;
            });

            Validator::extend('is_check_world', function ($attribute, $value, $parameters, $validator) {
                if (!array_key_exists($value, config('otserver.site.worlds')))
                    return false;
                return true;
            });

            $validator = Validator::make($requestAll, $rules, $messages);
            $validator->setAttributeNames($names);

            if ($validator->fails()) {
                return $view->with(compact('success'))
                    ->with(compact('world'))
                    ->with(compact('account_logged'))
                    ->withErrors($validator);
            }

            $player = new Player();
            $result = $player->createdCharacter([
                "name" => $newchar_name,
                "world_id" => (int) $world,
                "account_id" => $account_logged->getID(),
                "level" => "1",
                "vocation" => $newchar_vocation,
                "looktype" => config('otserver.site.newchar_looktype'),
                "town_id" => config('otserver.site.newchar_town'),
                "posx" => config('otserver.site.newchar_posx'),
                "posy" => config('otserver.site.newchar_posy'),
                "posz" => config('otserver.site.newchar_posz'),
                "conditions" => '0',
                "cap" => config('otserver.site.newchar_cap'),
                "sex" => $newchar_sex,
                "created" => time(),
                "comment" => '',
                "lastip" => 0,
                "lastlogin" => 0,
                "lastlogout" => 0,
                "save" => 1
            ]);

            return $view->with(compact('success'))
                ->with(compact('world'))
                ->with(compact('account_logged'))
                ->withErrors("O personagem {$newchar_name} foi criado com sucesso.\n Vejo você no " . htmlspecialchars_decode(config('otserver.server.serverName')) . "!");
        }
    }

    public function checkname(Request $request, $name = "")
    {
        //first word can't be:
        $first_words_blocked = array('drugovich', 'drugo', 'adm', 'gm ', 'cm ', 'god ', 'tutor ', "'", '-');
        //names blocked:
        $names_blocked = array('puta', 'simoni', 'simone', 'porra', 'buceta', 'caralho', 'thunder', 'training', 'trimera', 'serve', 'drugovich', 'drugo', 'adm', 'gm', 'cm', 'god', 'tutor');
        //name can't contain:
        $words_blocked = array('gamemaster', 'game master', 'game-master', "game'master", '  ', '--', "''", "' ", " '", '- ', ' -', "-'", "'-", 'fuck', 'sux', 'suck', 'noob', 'tutor');

        if (empty($name)) {
            return response()->json(["code" => 400, "message" => '<font color="red">Por favor insira um novo nome de personagem.</font>']);
            exit;
        }

        $temp = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM- '");

        if ($temp != strlen($name)) {
            return response()->json(["code" => 400, "message" => '<font color="red">O nome contém letras ilegais. Use apenas: <b>qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM- \'</b></font>']);
            exit;
        }

        if (strlen($name) > 19) {
            return response()->json(["code" => 400, "message" => '<font color="red">Nome muito longo. Máx. comprimento <b>19</b> letras.</font>']);
            exit;
        }

        if (in_array(strtolower($name), $names_blocked)) {
            if (count($names_blocked) > 1) {
                return response()->json(["code" => 400, "message" => "<font color=red>Nomes bloqueados: <b>" . implode(', ', $names_blocked) . "</b></font>"]);
                exit;
            } else {
                return response()->json(["code" => 400, "message" => "<font color=red>Nomes bloqueados: <b>" . $names_blocked[0] . "</b></font>"]);
                exit;
            }
        }

        /*
        foreach ($config['site']['monsters'] as $word)
            if ($word == $name) {
                echo '<font color="red"><b>You can not use monster name.</b></font>';
                exit;
            }
        */
        /*
        foreach ($config['site']['npc'] as $word)
            if ($word == $name) {
                echo '<font color="red"><b>You can not use NPC name.</b></font>';
                exit;
            }
        */

        foreach ($first_words_blocked as $word) {
            if ($word == strtolower(substr($name, 0, strlen($word)))) {
                if (count($first_words_blocked) > 1) {
                    return response()->json(["code" => 400, "message" => "As primeiras letras do nome não podem ser: " . implode(', ', $first_words_blocked)]);
                    exit;
                } else {
                    return response()->json(["code" => 400, "message" => "As primeiras letras do nome não podem ser: " . $first_words_blocked[0]]);
                    exit;
                }
            }
        }

        if (substr($name, -1) == "'" || substr($name, -1) == "-") {
            return response()->json(["code" => 400, "message" => 'A última letra não pode ser \' ou -']);
            exit;
        }

        if (in_array($name, $words_blocked)) {
            if (count($words_blocked) > 1) {
                return response()->json(["code" => 400, "message" => "O nome não pode conter palavras: " . implode(', ', $words_blocked)]);
                exit;
            } else {
                return response()->json(["code" => 400, "message" => "O nome não pode conter palavras: " . $words_blocked[0]]);
                exit;
            }
        }

        for ($i = 0; $i < strlen($name); $i++) {
            if ($name[$i - 1] == ' ' && $name[$i + 1] == ' ') {
                return response()->json(["code" => 400, "message" => '<font color="red">Use o formato de nome normal.</font><br /><font color="green"><u>Bom:</u> <b>Gesior</b></font><font color="red"><br />Errado: <b>G e s ior</b></font>']);
                exit;
            }
        }

        if (substr($name, 1, 1) == ' ') {
            return response()->json(["code" => 400, "message" => '<font color="red">Use o formato de nome normal.</font><br /><font color="green"><u>Bom:</u> <b>Gesior</b></font><font color="red"><br />Errado: <b>G esior</b></font>']);
            exit;
        }

        if (substr($name, -2, 1) == " ") {
            return response()->json(["code" => 400, "message" => '<font color="red">Use o formato de nome normal.</font><br /><font color="green"><u>Bom:</u> <b>Gesior</b></font><font color="red"><br />Errado: <b>Gesio r</b></font>']);
            exit;
        } else {
            $player = new Player();
            $player->find(urldecode($name));

            if ($player->isLoaded()) {
                return response()->json(["code" => 400, "message" => "<b style=color:red>Já existe um jogador com este nome.</b>"]);
                exit;
            } else {
                return response()->json(["code" => 200, "message" => '<font color="green">Bom. Seu nome será:<br />"<b>' . ucwords($name) . '</b>"</font>']);
                exit;
            }
        }
    }
}
