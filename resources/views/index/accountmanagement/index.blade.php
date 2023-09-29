@extends('layout.index')
@section('title', 'Conta')
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Minha Conta</span>
    </div>
    <div class="bg-content">
        <div class="container">
            @foreach($errors->all() as $message)
                <x-notification message="{{ $message }}" class="mt-3"/>
            @endforeach

            <div id="top" class="mt-3">
                <div class="container d-flex flex-row justify-content-center align-items-center">
                    <img src="{{asset('images/account/headline-bracer-left.gif')}}" class="img-fluid me-4">
                    <h2 class="m-0" style="font-family: 'Aclonica', sans-serif;">{{ $welcome_msg }}</h2>
                    <img src="{{ asset('images/account/headline-bracer-right.gif') }}" class="img-fluid ms-4">
                </div>
            </div>

            <div id="statusAccount" class="container">
                <div class="bg-title">Status da Conta</div>
                <div class="main-content">
                    <div class="row">
                        <div class="d-flex flex-row align-items-center">
                            <div class="col-10 d-flex flex-row">
                                @if($account_logged->isPremium())
                                    <img src="{{asset('images/account/account-status_green.gif')}}" alt="Conta Premium" width="52" height="52" class="img-fluid me-2">
                                    <div class="fw-bold fs-5 text-success d-flex align-items-center">Conta Premium, termina {{ $account_logged->getPremDays() }} dias restantes.</div>
                                @else
                                    <img src="{{asset('images/account/account-status_red.gif')}}" alt="Conta gratis" width="52" height="52" class="img-fluid me-2">
                                    <div class="d-flex flex-column">
                                        <div class="fw-bold fs-5 text-danger">Conta Grátis</div>
                                        <p class="m-0 text-muted">O benefício de nossos grandes recursos premium, obter Premium Time para a sua conta.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-2">
                                <div class="d-flex flex-column align-items-center">
                                    <a href="shop" class="sbutton-green">Obter Prémio</a>
                                    <a href="{{ route('accountmanagement.logout') }}" class="sbutton-red">Sair</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-center mt-3 text-dark">
                            <a href="#infoGeneral" class="text-dark fw-bold">[Informações Geral]</a>, <a href="#infoPublic" class="text-dark fw-bold">[Informações Publicas]</a>, <a href="#infoPremiumTime" class="text-dark fw-bold">[Informações do Premium Time]</a>, <a href="#infoShop" class="text-dark fw-bold">[Informações de Compra]</a>, <a href="#infoPlayers" class="text-dark fw-bold">[Personagens]</a>.
                        </div>
                    </div>
                </div>
            </div>

            @if(empty($account_logged->getCustomField("key")))
                <div class="container">
                    <div class="bg-title">Dica <a class="sbutton-top" href="#top"></a></div>
                    <div class="main-content">
                        <div class="row">
                            <div class="col-10">Você pode registrar sua conta para maior proteção. Clicando em <b>"Registrar Conta"</b> para obter a sua chave de recuperação gratuita!</div>
                            <div class="col-2">
                                <a href="{{ route('accountmanagement.registeraccount') }}" class="sbutton-blue">Registrar Conta</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($account_logged->getCustomField("email_new_time") > 1)
                <div class="container">
                    <div class="bg-title">Dica <a class="sbutton-top" href="#top"></a></div>
                    <div class="main-content">
                        <div class="row">
                            <div class="col-10">O pedido de alteração do e-mail foi enviado para <b>{{ $account_logged->getCustomField("email_new") }}</b>. depois <b>{{ date("j F Y, G:i:s", $account_logged->getCustomField("email_new_time")) }}</b> você pode aceitar o novo e-mail e concluir o processo. Por favor, se você não quer que seu e-mail seja mudado! Também cancelar o pedido, se você não tem acesso ao e-mail novo!!</div>
                            <div class="col-2">
                                <a href="accountmanagement/changeemail" class="sbutton-blue">Editar</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div id="infoGeneral" class="container">
                <div class="bg-title">Informações Geral <a class="sbutton-top" href="#top"></a></div>
                <div class="main-content">
                    <div class="row">
                        <div class="col-3 text-start">E-mail</div>
                        <div class="col-9 text-start">{{ $account_logged->getCustomField("email") }}</div>
                        <div class="col-3 text-start">Criada</div>
                        <div class="col-9 text-start">{{ \Carbon\Carbon::createFromTimestamp($account_logged->getCreateDate())->isoFormat('A, d [de] MMMM [de] Y') }}</div>
                        <div class="col-3 text-start">Ultimo Login</div>
                        <div class="col-9 text-start">{{ \Carbon\Carbon::createFromTimestamp($account_logged->getLastLogin())->isoFormat('A, d [de] MMMM [de] Y') }}</div>
                        <div class="col-3 text-start">Status da conta</div>
                        <div class="col-9 text-start">
                        @if($account_logged->isPremium())
                            <div class="fw-bold text-success">Conta Premium, termina {{ $account_logged->getPremDays() }} dias restantes.</div>
                        @else
                            <div class="fw-bold text-danger">Conta Grátis</div>
                        @endif
                        </div>
                        <div class="col-3 text-start">Registrada</div>
                        <div class="col-9 text-start">
                          @if (empty($account_logged->getRecoveryKey()))
                            <p class="m-0 text-danger fw-bold">Não</p>
                          @else
                                @if (config('otserver.site.generate_new_reckey'))
                                    <p class="m-0"><span class="text-success fw-bold">Sim</span> ( <a href="{{ route('accountmanagement.newreckey') }}" class="text-dark fw-bold"> Compre nova Key </a> )</p>
                                @else
                                    <p class="m-0 text-success fw-bold">Sim</p>
                                @endif
                          @endif
                        </div>
                        <div class="col-3 text-start">Points</div>
                        <div class="col-9 text-start">Você tem <b>{{ $account_logged->getCustomField('premium_points') }} {{ config('otserver.pagseguro.productName') }}</b></div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        <a class="sbutton-blue" data-bs-toggle="tooltip" data-bs-placement="top" title="Essa opção é para alterar a senha da sua conta." href="{{ route("accountmanagement.changepassword") }}">Alterar Senha</a>
                        <a class="sbutton-blue" data-bs-toggle="tooltip" data-bs-placement="top" title="Essa opção é para alterar o e-mail da sua conta." href="{{ route("accountmanagement.changeemail") }}">Alterar Email</a>
                        @if(empty($account_logged->getRecoveryKey()))
                            <a class="sbutton-blue" data-bs-toggle="tooltip" data-bs-placement="top" title="Essa opção é para gerar uma chave de recuperação para sua conta." href="{{ route('accountmanagement.registeraccount') }}">Registrar Conta</a>
                        @endif
                    </div>
                </div>
            </div>

            <div id="infoPublic" class="container">
                <div class="bg-title">Informações Publicas <a class="sbutton-top" href="#top"></a></div>
                <div class="main-content">
                    <div class="row">
                        <div class="col-3 text-start">Nome Completo</div>
                        <div class="col-9 text-start">{{ $account_logged->getRLName() }}</div>
                        <div class="col-3 text-start">Localização</div>
                        <div class="col-9 text-start">{{ $account_logged->getLocation() }}</div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        <a class="sbutton-blue" data-bs-toggle="tooltip" data-bs-placement="top" title="Essa opção é para alterar as informações da sua conta." href="{{ route('accountmanagement.changeinfo') }}">Alterar Informações</a>
                    </div>
                </div>
            </div>

            <div id="infoPremiumTime" class="container">
                <div class="bg-title">Informações da Premium Time <a class="sbutton-top" href="#top"></a></div>
                <div class="main-content p-1">
                    <div class="row m-0 mt-1">
                        <div class="col-2 ps-0 text-start d-flex justify-content-center">
                            @if($account_logged->getPremiumPoints() > 0)
                                <a class="sbutton-blue" data-bs-toggle="tooltip" data-bs-placement="top" title="Essa opção é para comprar Premium Time para sua conta." href="shop">Comprar Premium</a>
                            @else
                                <a class="sbutton-blue" data-bs-toggle="tooltip" data-bs-placement="top" title="Essa opção é para comprar {{ config('otserver.pagseguro.productName') }} para sua conta." href="donate">Comprar Coins</a>
                            @endif
                        </div>
                        <div class="col-10 text-start d-flex align-items-center">
                            @if($account_logged->getPremiumPoints() > 0)
                                Você possuí {{ $account_logged->getPremiumPoints() }} {{ config('otserver.pagseguro.productName') }}, Compre seu premium time para poder usar todas as vantagens.
                            @else
                                Vejo que você não possuí <b>{{ config('otserver.pagseguro.productName') }}</b>, adquira já e logo em seguida o <b>Premium Time</b> para poder usar todas as vantagens.
                            @endif
                        </div>
                        <hr class="my-2">
                        <div class="col-12 p-0">
                            <table class="table table-striped table-bordered m-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">Descrição</th>
                                        <th scope="col">Data da Compra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($account_logged->getHistoryPacc(true) as $item)
                                    <tr>
                                        <td scope="row">Premium Time {{ $item->getPaccDays() }} dias</td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($item->getTransStart())->isoFormat('A, d [de] MMMM [de] Y') }}</td>
                                    </tr>
                                    @endforeach
                                    @if(count($account_logged->getHistoryPacc(true)) == 0)
                                    <tr>
                                        <td colspan='2'>Você não realizou nenhuma compra de Premium Time.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="infoShop" class="container">
                <div class="bg-title">Informações de Compra <a class="sbutton-top" href="#top"></a></div>
                <div class="main-content p-1">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-bordered m-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">Descrição</th>
                                        <th scope="col">Metodo</th>
                                        <th scope="col">Points</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($account_logged->getTransactions(true) as $item)
                                      <tr>
                                        <td scope="row">{{ $item->getPoints().' '.config('otserver.pagseguro.productName') }}</td>
                                        <td>{{ $item->getPaymentMethod() }}</td>
                                        <td>{{ $item->getPoints() }}</td>
                                        <td>{{ $item->getCreatedAt() }}</td>
                                        <td>{{ $item->getStatus() }}</td>
                                      </tr>
                                      @endforeach
                                      @if(count($account_logged->getTransactions(true)) == 0)
                                      <tr>
                                        <td colspan='5'>Você não realizou nenhuma compra de {{ config('otserver.pagseguro.productName') }}.</td>
                                      </tr>
                                      @endif
                                  </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="infoPlayers" class="container">
                <div class="bg-title">Personagens <a class="sbutton-top" href="#top"></a></div>
                <div class="main-content p-1">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-bordered align-middle m-0 text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">*</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Level</th>
                                        <th scope="col">Vocações</th>
                                        <th scope="col">Status</th>
                                        <th scope="col"><i class="fa fa-cogs"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($account_logged->getPlayers(true) as $item)
                                    <tr>
                                        <td scope="row">{{ $loop->index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('characters.index', ['name' => urlencode($item->getName())]) }}" class="text-dark fw-bold text-decoration-none">
                                                {{ $item->getName() }}
                                                @if($item->isDeleted())
                                                    <span class="text-danger fw-bold">{{ ' [DELETADO]' }}</span>
                                                @endif
                                            </a>
                                        </td>
                                        <td>{{ $item->getLevel() }}</td>
                                        <td>{{ Website::getVocationName($item->getVocation()) }}</td>
                                        <td>
                                            @if($item->isOnline())
                                                <span class="text-success fw-bold">Online</span>
                                            @else
                                                <span class="text-danger fw-bold">Offline</span>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="m-0">[<a href="{{ route('accountmanagement.changecomment', ['name' => urlencode($item->getName())]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Essa opção você editar as informções do seu personagem." class="text-dark fw-bold">Editar</a>]</span>
                                                @if ($item->isDeleted())
                                                <p class="m-0">
                                                    <span>[<a href="{{ route('accountmanagement.undelete', ['name' => urlencode($item->getName())]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Essa opção desfazer a exclusão do seu personagem. Só será valida por 90 dias." class="text-dark fw-bold">Desfazer</a>]</span>
                                                </p>
                                                @else
                                                <p class="m-0">
                                                    <span>[<a href="{{ route('accountmanagement.deletecharacter', ['name' => urlencode($item->getName())]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Essa opção você deleta seu personagem." class="text-dark fw-bold">Deletar</a>]</span>
                                                </p>
                                                @endif
                                            @if ($item->isNameLocked())
                                            <p class="m-0">
                                                @if($item->getOldName())
                                                    <span class="text-danger">[Nome Alterado: {{ $item->getOldName() }}]</span>
                                                @else
                                                    <span class="text-dark fw-bold">[<a href="{{ route('accountmanagement.newnick', ['name' => urlencode($item->getName())]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Digite aqui novo nick">Alterar Nome</a>]</span>
                                                @endif
                                            </p>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if(count($account_logged->getPlayers(true)) == 0)
                                    <tr>
                                        <td colspan='6'>Ainda não existe nenhum personagem.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        <a class="sbutton-blue" href="{{ route('accountmanagement.createcharacter') }}">Criar Personagem</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
  </div>
@endsection
@push('scripts')
    <script>
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this).attr('href');

            const des = $(target).offset().top - 120;
            console.log(des);

            if(target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: des
                }, 500);
            }
        });
    </script>
@endpush
