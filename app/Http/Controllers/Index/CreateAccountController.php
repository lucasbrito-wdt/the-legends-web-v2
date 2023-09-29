<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Mail\CreateAccountMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Otserver\Functions;
use Otserver\Account;
use Otserver\Website;
use Otserver\Visitor;

class CreateAccountController extends Controller
{
    public function index(Request $request)
    {
        $view = view('index.createaccount.index');

        if ($request->method() == "GET") {
            return $view;
        }

        if ($request->method() == "POST") {
            $messages = [
                'account_name.check_account_name' => 'Formato de nome de conta inválido. Use apenas A-Z e números 0-9.',
                'account_name.check_exist_name' => 'Já existe uma conta com este nome.',
                'account_email.check_mail' => 'O endereço de e-mail não está correto.',
                'account_email.check_account_email' => 'Já existe uma conta com este endereço de e-mail no banco de dados.',
                'account_password.check_password' => 'A senha contém caracteres ilegais (a-z, A-Z e 0-9 apenas!) Ou comprimento.',
                'account_password2.same' => 'As senhas não são iguais!',
                'g-recaptcha-response.recaptcha' => 'A verificação do captcha falhou'
            ];

            $rules = [
                'account_name' => 'required', 'max:50', 'check_account_name:account_name', 'check_exist_name:account_name',
                'account_email' => 'required', 'check_mail:account_email', 'check_account_email:account_email',
                'account_password' => 'required', 'max:50', 'check_password:account_password',
                'account_password2' => 'required', 'max:50', 'same:account_password',
                'account_rlname' => 'required',
                'account_location' => 'required',
                'account_rules' => 'required',
                'g-recaptcha-response' => 'required', 'recaptcha:g-recaptcha-response',
            ];

            $names = [
                'account_name' => "Conta",
                'account_email' => "E-mail",
                'account_password' => "Senha",
                'account_password2' => "Repetir Senha",
                'account_rlname' => "Nome Completo",
                'account_location' => "Localização",
                'account_rules' => "Regras",
                'g-recaptcha-response' => "Captcha",
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($names);

            Validator::extend('check_account_name', function ($attribute, $value, $parameters, $validator) {
                return Functions::check_account_name($value);
            });

            Validator::extend('check_exist_name', function ($attribute, $value, $parameters, $validator) {
                $account_db = new Account();
                $account_db->find($value);
                if ($account_db->isLoaded())
                    return false;
                return true;
            });

            Validator::extend('check_password', function ($attribute, $value, $parameters, $validator) {
                return Functions::check_password($value);
            });

            Validator::extend('check_mail', function ($attribute, $value, $parameters, $validator) {
                return Functions::check_mail($value);
            });

            Validator::extend('check_account_email', function ($attribute, $value, $parameters, $validator) {
                $test_email_account = new Account();
                $test_email_account->findByEmail($value);
                if ($test_email_account->isLoaded())
                    return false;
                return true;
            });

            if ($validator->fails()) {
                $view->withErrors($validator);
                return $view;
            }

            $reg_account = new Account();

            $reg_account->setName(request()->input('account_name'));
            $reg_account->setPassword(Functions::encryptPassword(config('otserver.server.encryptionType'), request()->input('account_password')));
            $reg_account->setEMail(request()->input('account_email'));
            $reg_account->setRLName(request()->input('account_rlname'));
            $reg_account->setLocation(request()->input('account_location'));
            $reg_account->setCreateDate(time());
            $reg_account->setCreateIP(Visitor::getIP());
            $reg_account->unblock();
            $reg_account->setFlag(Website::getCountryCode(long2ip(Visitor::getIP())));
            if (config('otserver.site.newaccount_premdays') > 0) {
                $reg_account->set("premdays", config('otserver.site.newaccount_premdays'));
                $reg_account->set("lastday", time());
            }
            Website::getDBHandle()->setPrintQueries(false);
            $reg_account->save();

            if (config('otserver.site.send_emails') && config('otserver.site.create_account_verify_mail')) {
                try {
                    @Mail::send(new CreateAccountMail([
                        'to' => [
                            'address' => $reg_account->getMail(),
                            'name' => $reg_account->getRLName()
                        ],
                        'from' => [
                            'address' => config('mail.from.address'),
                            'name' => config('otserver.server.serverName')
                        ],
                        'subject' => "Cadastro",
                        'reg_name' => request()->input('account_name'),
                        'reg_password' => request()->input('account_password'),
                    ]));
                } catch (\Exception $e) {
                }
            }

            return $view->withErrors(['success' => 'Conta criada com sucesso.']);
        }
    }

    public function checkaccountname($name = "")
    {
        if (empty($name)) {
            return response()->json(["code" => 400, "message" => "Por favor insira um nome de conta!"]);
        } else if (strlen($name) < 6) {
            return response()->json(["code" => 400, "message" => "Este nome de conta é muito curto!"]);
        } else if (strlen($name) > 30) {
            return response()->json(["code" => 400, "message" => "Este nome de conta é muito longo!"]);
        }
        $name = strtoupper($name);
        if (!ctype_alnum($name)) {
            return  response()->json(["code" => 400, "message" => "Este nome de conta tem um formato inválido. O nome da sua conta pode consistir apenas em números de 0 a 9 e letras de A a Z!"]);
        }

        $account = new Account();
        $account->find($name);
        if ($account->isLoaded())
            return response()->json(["code" => 400, "message" => "Está account já está em uso. Por favor digite outra account!"]);
        else
            return response()->json(["code" => 200, "message" => "Esse é bom nome pra conta ({$name}). Você pode criar uma conta."]);
    }

    public function checkaccountemail($email = '')
    {
        if (empty($email))
            return response()->json(["code" => 400, "message" => "Por favor, indique o seu endereço de e-mail!"]);
        elseif (strlen($email) > 49)
            return response()->json(["code" => 400, "message" => "Seu endereço de e-mail é muito longo!"]);
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return response()->json(["code" => 400, "message" => "Este endereço de e-mail tem um formato inválido. Por favor insira um endereço de e-mail correto!"]);

        $SQL = Website::getDBHandle()->query("SELECT email FROM " . Website::getDBHandle()->tableName('accounts') . " WHERE " . Website::getDBHandle()->fieldName('email') . " = " . Website::getDBHandle()->quote($email) . " LIMIT 1");
        if ($SQL->rowCount() > 0)
            return response()->json(["code" => 400, "message" => "Este endereço de email já está sendo usado. Por favor, insira outro endereço de e-mail!"]);
        else
            return response()->json(["code" => 200, "message" => "Esse e-mail é bom pra sua conta ({$email})."]);
    }
}
