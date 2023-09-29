@extends('layout.index')
@section('title', 'WebShop')
@push('stylesheets')
<style>
    #payment-form {
        text-align: center;
        position: relative;
        margin-top: 20px
    }

    #payment-form fieldset .form-card {
        background: white;
        border: 0 none;
        border-radius: 0px;
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        padding: 20px 40px 30px 40px;
        box-sizing: border-box;
        width: 94%;
        margin: 0 3% 20px 3%;
        position: relative
    }

    #payment-form fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        width: 100%;
        margin: 0;
        padding-bottom: 20px;
        position: relative
    }

    #payment-form fieldset:not(:first-of-type) {
        display: none
    }

    #payment-form fieldset .form-card {
        text-align: left;
        color: #9E9E9E
    }

    #payment-form .action-button {
        width: 100px;
        background: skyblue;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px
    }

    #payment-form .action-button:hover,
    #payment-form .action-button:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
    }

    #payment-form .action-button-previous {
        width: 100px;
        background: #616161;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px
    }

    #payment-form .action-button-previous:hover,
    #payment-form .action-button-previous:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
    }

    select.list-dt {
        border: none;
        outline: 0;
        border-bottom: 1px solid #ccc;
        padding: 2px 5px 3px 5px;
        margin: 2px
    }

    select.list-dt:focus {
        border-bottom: 2px solid skyblue
    }

    .card {
        z-index: 0;
        border: none;
        border-radius: 0.5rem;
        position: relative
    }

    .fs-title {
        font-size: 25px;
        color: #2C3E50;
        margin-bottom: 10px;
        font-weight: bold;
        text-align: left
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey
    }

    #progressbar .active {
        color: #000000
    }

    #progressbar li {
        list-style-type: none;
        font-size: 12px;
        width: 25%;
        float: left;
        position: relative
    }

    #progressbar #account:before {
        font-family: "Font Awesome 5 Free";
        content: "\f023";
        font-weight: 900;
    }

    #progressbar #product:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f07a"
    }

    #progressbar #payment:before {
        font-family: "Font Awesome 5 Free";
        content: "\f09d"
    }

    #progressbar #confirm:before {
        font-family: "Font Awesome 5 Free";
        content: "\f00c";
        font-weight: 900;
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 18px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #000
    }

    .radio-group {
        position: relative;
        margin-bottom: 25px
    }

    .radio {
        display: inline-block;
        width: 204;
        height: 104;
        border-radius: 0;
        background: lightblue;
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        box-sizing: border-box;
        cursor: pointer;
        margin: 8px 2px
    }

    .radio:hover {
        box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
    }

    .radio.selected {
        box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
    }

    .fit-image {
        width: 100%;
        object-fit: cover
    }

    input.offers, input.payments {
        display: none;
    }

    /* Variables */
    :root {
        --body-color: rgb(247, 250, 252);
        --button-color: rgb(30, 166, 114);
        --accent-color: #0a721b;
        --link-color: #ffffff;
        --font-color: rgb(105, 115, 134);
        --body-font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        --radius: 6px;
        --form-width: 400px;
    }

    .sr-field-error {
        color: var(--font-color);
        text-align: left;
        font-size: 13px;
        line-height: 17px;
        margin-top: 12px;
    }

    /* Inputs */
    .sr-input {
        border: 1px solid var(--gray-border);
        border-radius: var(--radius);
        padding: 5px 12px;
        height: 44px;
        width: 100%;
        transition: box-shadow 0.2s ease;
        background: white;
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
    }

    .sr-input:focus,
    button:focus,
    .focused {
        box-shadow: 0 0 0 1px rgba(50, 151, 211, 0.3), 0 1px 1px 0 rgba(0, 0, 0, 0.07),
        0 0 0 4px rgba(50, 151, 211, 0.3);
        outline: none;
        z-index: 9;
    }

    .sr-input::placeholder {
        color: var(--gray-light);
    }

    .sr-combo-inputs-row {
        box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
        0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
        border-radius: 7px;
    }

    /* Stripe Element placeholder */
    .sr-card-element {
        padding-top: 12px;
    }
</style>
@endpush
@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
let stripe;
stripe = Stripe("{{ config('services.stripe.test.key') }}", {
    locale: 'pt-BR'
});
const checkoutButton = document.getElementById("checkout-button");
checkoutButton.addEventListener("click", function () {
    fetch("{{ route('stripe.checkout') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": $('input[name="_token"]').val()
        },
        body: JSON.stringify(orderData)
    })
    .then(function (response) {
        return response.json();
    })
    .then(function (session) {
        return stripe.redirectToCheckout({ sessionId: session.id });
    })
    .then(function (result) {
        // If redirectToCheckout fails due to a browser or network
        // error, you should display the localized error message to your
        // customer using error.message.
        if (result.error) {
            alert(result.error.message);
        }
    })
    .catch(function (error) {
        console.error("Error:", error);
    });
});
</script>
<script>
    let orderData = {
        items: [],
        currency: 'brl',
        cancelPage: '{{ route('webshop.index') }}',
        successPage: '{{ route('webshop.finish') }}'
    };

    $(document).ready(function(){
        let current_fs, next_fs, previous_fs; //fieldsets
        var opacity;

        $(".next").click(function(){
            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            let curInputs = current_fs.find("input"),
                  isValid = true;

            for(var i=0; i<curInputs.length; i++){
                if (!curInputs[i].validity.valid){
                    isValid = false;
                }
            }

            if(isValid){
                //Add Class Active
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();

                //hide the current fieldset with style
                current_fs.animate({opacity: 0}, {
                    step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;
                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });

                    if(current_fs.find('.alert').length > 0)
                        current_fs.find('.alert').remove();

                    next_fs.css({'opacity': opacity});
                    }, duration: 600
                });
            } else {
                if(current_fs.find('.alert').length == 0){
                    switch($("fieldset").index(current_fs)){
                        case 0:
                            current_fs.prepend('<div class="alert alert-danger" role="alert">Você precisa selecionar uma oferta!</div>')
                        break;
                        case 1:
                            current_fs.prepend('<div class="alert alert-danger" role="alert">Você precisa selecionar uma forma de pagamento!</div>')
                        break;
                        case 2:
                            current_fs.prepend('<div class="alert alert-danger" role="alert">Você precisa preencher todos os dados!</div>')
                        break;
                    }
                }
            }
        });

        $(".previous").click(function(){
            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
                //show the previous fieldset
                previous_fs.show();
                //hide the current fieldset with style
                current_fs.animate({opacity: 0}, {
                    step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;
                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({'opacity': opacity});
                }, duration: 600
            });
        });

        $('input.offers').click(function(){
            const offer = $(this).data('check-id')
            orderData.items = [];
            orderData.items.push({
                id: $(this).data('payment-id'),
                label: $(this).data('payment-label'),
                image: $(this).data('payment-image'),
                detail: $(this).data('payment-detail'),
                amount: $(this).data('payment-amount'),
                points: $(this).data('payment-points')
            })

            $("#product-selected").text($(this).data('payment-label'))
            $('#summary-product').text($(this).data('payment-label'));

            $('.offer-check').hide();
            $(`.${offer}`).show();
        });

        $('input.payments').click(function(){
            const offer = $(this).data('payment-id');
            $("#summary-payment").text($(this).val())
            $('.payments-check').hide();
            $(`.${offer}`).show();
        });

        $("#buy_name").on('change', function(){
            $("#summary-to").text($(this).val())
        });

        $("#buy_from").on('keypress blur', function(){
            $("#summary-from").text($(this).val())
            orderData.accountFrom = $(this).val()
        });
    });
</script>
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>WebShop</span>
    </div>
    <div class="bg-content" style="padding-top: 26px;padding-bottom: 26px;">
        <div class="container">
            <div class="bg-title m-0">WebShop no {{ config('otserver.server.serverName') }}</div>
            <div class="main-content">
                @if($errorsView)
                    @foreach($errors->all() as $message)
                    <div class="text-center m-0">{!! $message !!}</div>
                    @endforeach
                </div>
                @else
                <!-- MultiStep Form -->
                <div class="row justify-content-center mt-0">
                    <div class="col-8 text-center p-0">
                        <div class="card">
                            <p>Preencha todos os campos do formulário para ir para a próxima etapa</p>
                            <div class="row">
                                <div class="col-12 mx-0">
                                    <form id="payment-form" class="sr-payment-form">
                                        @csrf
                                        <!-- progressbar -->
                                        <ul id="progressbar">
                                            <li class="active" id="product"><strong>Produto</strong></li>
                                            <li id="account"><strong>Conta</strong></li>
                                            <li id="payment"><strong>Metodo de Pagamento</strong></li>
                                            <li id="confirm"><strong>Finalizado</strong></li>
                                        </ul> <!-- fieldsets -->
                                        <fieldset class="p-0">
                                            <div class="form-card">
                                                <h2 class="fs-title text-center mb-5">Informações sobre ofertas</h2>
                                                <div class="row">
                                                    @for ($i = 1; $i <= 10; $i++)
                                                    <label for="offer-{{$i}}" class="col-4 p-2 offer-{{$i}}">
                                                        <div class="card text-center">
                                                            <i class="offer-check offer-check-{{$i}} position-absolute fas fa-check text-success fw-bold" style="top: 43px;right: 5px;font-size: 30px;display:none"></i>
                                                            <div class="card-header bg-dark bg-gradient">{{ $i * 20 }} {{ config('otserver.pagseguro.productName') }}</div>
                                                            <div class="card-body bg-secondary bg-gradient">
                                                                <p class="card-text"><img src="{{ asset('images/donate/points.png') }}" alt="" height="80"></p>
                                                            </div>
                                                            <div class="card-footer bg-dark bg-gradient text-muted">R$ {{ number_format(($i * 20) * 0.25, 2, ',', '')}}</div>
                                                        </div>
                                                    </label>
                                                    <input type="radio" class="offers" name="offers" id="offer-{{$i}}"
                                                        data-check-id="offer-check-{{$i}}"
                                                        data-payment-id="baisc"
                                                        data-payment-label="{{ $i * 20 }} {{ config('otserver.pagseguro.productName') }}"
                                                        data-payment-detail=""
                                                        data-payment-image="{{ asset('images/donate/points.png') }}"
                                                        data-payment-amount="{{ number_format(($i * 20) * 0.25, 2, '.', '') }}"
                                                        data-payment-points="{{ $i * 20 }}"
                                                        required>
                                                    @endfor
                                                </div>
                                            </div>
                                            <input type="button" name="next" class="next action-button" value="Próximo" />
                                        </fieldset>
                                        <fieldset class="p-0">
                                            <div class="form-card">
                                                <h2 class="fs-title text-center mb-5">Informação da conta</h2>
                                                <div class="card mb-1">
                                                    <div class="card-header text-white bg-dark">Oferta selecionada</div>
                                                    <div class="card-body border rounded-end">
                                                      <div class="card-text">
                                                        <div class="row">
                                                            <div class="col-2">Produto:</div>
                                                            <div id="product-selected" class="col"></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-2">Nome:</div>
                                                            <div class="col">{{ $account_logged->getRLName() }}</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-2">E-mail:</div>
                                                            <div class="col">{{ $account_logged->getEmail() }}</div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>

                                                <div class="card">
                                                    <div class="card-header text-white bg-dark">Doação</div>
                                                    <div class="card-body border rounded-end">
                                                      <div class="card-text">
                                                            <div class="row mb-1">
                                                                <div class="col-2 d-flex align-items-center">De:</div>
                                                                <div class="col">
                                                                    <div class="form-floating">
                                                                        <select class="form-select" id="buy_name" name="buy_name" aria-label="Selecione um personagem">
                                                                            <option selected="">Selecione um personagem</option>
                                                                            @foreach ($account_logged->getPlayers(true) as $player)
                                                                                <option value="{{ urlencode($player->getName()) }}">{{ $player->getName() }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <label for="buy_name">Selecione um personagem</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-2 d-flex align-items-center">Para:</div>
                                                                <div class="col">
                                                                    <div class="form-floating">
                                                                        <input type="text" class="form-control" id="buy_from" name="buy_from" placeholder="Digite um nome do jogador">
                                                                        <label for="buy_from">Digite um nome do jogador</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                            <input type="button" name="next" class="next action-button" value="Próximo" />
                                        </fieldset>
                                        <fieldset class="p-0">
                                            <div class="form-card">
                                                <h2 class="fs-title text-center mb-5">Informação de pagamento</h2>
                                                <div class="row">
                                                    <label for="payments-stripe" class="col-4 p-2 payments-stripe">
                                                        <div class="card text-center">
                                                            <i class="payments-check payments-check-stripe position-absolute fas fa-check text-success fw-bold" style="top: 43px;right: 5px;font-size: 30px;display:none"></i>
                                                            <div class="card-header bg-dark bg-gradient">Stripe</div>
                                                            <div class="card-body bg-secondary bg-gradient">
                                                                <p class="card-text"> <i class="fab fa-cc-stripe text-white" style="font-size: 80px"></i> </p>
                                                            </div>
                                                            <div class="card-footer bg-dark bg-gradient text-muted">Checkout</div>
                                                        </div>
                                                    </label>
                                                    <input type="radio" class="payments" name="payments" id="payments-stripe" data-payment-id="payments-check-stripe" value="Stripe" required>
                                                </div>
                                            </div>
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                            <input type="button" name="next" class="next action-button" value="Próximo" />
                                        </fieldset>
                                        <fieldset class="p-0">
                                            <div class="form-card sr-result">
                                                <h2 class="fs-title text-center mb-5">Summary</h2>

                                                <div class="card">
                                                    <div class="card-header text-white bg-dark">Resumo</div>
                                                    <div class="card-body">
                                                        <div class="card-text">
                                                            <div class="row">
                                                                <div class="col-3">Produto:</div>
                                                                <div id="summary-product" class="col"></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-3">Nome:</div>
                                                                <div id="summary-name" class="col">{{ $account_logged->getRLName() }}</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-3">E-mail:</div>
                                                                <div id="summary-email" class="col">{{ $account_logged->getEmail() }}</div>
                                                            </div>

                                                            <div class="row mt-1">
                                                                <div class="col-12 text-white bg-dark rounded-top p-2">Doação:</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-3">De:</div>
                                                                <div id="summary-to" class="col"></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-3">Para:</div>
                                                                <div id="summary-from" class="col"></div>
                                                            </div>

                                                            <div class="row mt-1">
                                                                <div class="col-12 text-white bg-dark rounded-top p-2">Forma de Pagamento:</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-3">Forma de Pagamento:</div>
                                                                <div id="summary-payment" class="col"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                            <input type="button" id="checkout-button" name="next" class="action-button" value="Finalizar" />
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
