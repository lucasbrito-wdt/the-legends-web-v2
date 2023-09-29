<script>
var menu = new Array();
menu[0] = new Object();
var unloadhelper = false;

// store the values of the variable 'self.name' in the array menu
const FillMenuArray = () =>
{
    while (self.name.length > 0) {
    var mark1 = self.name.indexOf("=");
    var mark2 = self.name.indexOf("&");
    var menuItemName = self.name.substr(0, mark1);
    menu[0][menuItemName] = self.name.substring(mark1 + 1, mark2);
    self.name = self.name.substr(mark2 + 1, self.name.length);
    }
}

// hide or show the corresponding submenus
const InitializeMenu = () =>
{
    for (menuItemName in menu[0]) {
    if (menu[0][menuItemName] == "0") {
        document.getElementById(menuItemName + "_Submenu").style.visibility = "hidden";
        document.getElementById(menuItemName + "_Submenu").style.display = "none";
        document.getElementById(menuItemName + "_Extend").style.backgroundImage = "url(" + IMAGES + "/general/plus.gif)";
    } else {
        document.getElementById(menuItemName + "_Submenu").style.visibility = "visible";
        document.getElementById(menuItemName + "_Submenu").style.display = "block";
        document.getElementById(menuItemName + "_Extend").style.backgroundImage = "url(" + IMAGES + "/general/minus.gif)";
    }
    }
}

// reconstruct the variable "self.name" out of the array menu
const SaveMenuArray = () =>
{
    var stringSlices = "";
    var temp = "";
    for (menuItemName in menu[0]) {
    stringSlices = menuItemName + "=" + menu[0][menuItemName] + "&";
    temp = temp + stringSlices;
    }
    self.name = temp;
}

// onClick open or close submenus
const MenuItemAction = (sourceId) =>
{
    if (menu[0][sourceId] == 1) {
        CloseMenuItem(sourceId);
    } else {
        OpenMenuItem(sourceId);
    }
}

const OpenMenuItem = (sourceId) =>
{
    menu[0][sourceId] = 1;
    document.getElementById(sourceId + "_Submenu").style.visibility = "visible";
    document.getElementById(sourceId + "_Submenu").style.display = "block";
    document.getElementById(sourceId + "_Alert").setAttribute('title', 'Click para fechar !');
}
const CloseMenuItem = (sourceId) =>
{
    menu[0][sourceId] = 0;
    document.getElementById(sourceId + "_Submenu").style.visibility = "hidden";
    document.getElementById(sourceId + "_Submenu").style.display = "none";
    document.getElementById(sourceId + "_Alert").setAttribute('title', 'Click para abrir !');
}
</script>

<div id="sidebar">
    <div class="menu">
        <div id="conta" class="menu-item">
          <span onClick="MenuItemAction('conta')">
            <div id="conta_Alert" class="menu-button" data-bs-toggle="tooltip" title="Click para fechar !">
              <img src="{{ asset('images/icons/icon-account.gif') }}" class="menu-icon">
              <span class="menu-label">Conta</span>
            </div>
          </span>
          <div id="conta_Submenu" class="submenu">
            <ul class="ul-sidebar">
              @if(Visitor::isLogged())
              <li>
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <a href="{{ route('accountmanagement.index') }}" class="nav-link">Minha Conta</a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('accountmanagement.logout') }}" class="nav-link">Sair</a>
                    </li>
                </ul>
              </li>
              @else
              <li class="sidebar-group">
                    <form action="{{ route('accountmanagement.login') }}" method="POST" class="sidebar-group form-group" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group mb-1">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input name="account_login" type="text" class="form-control text-white" required="required" placeholder="Usuario">
                        </div>
                        <div class="input-group mb-1">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input name="password_login" type="password" class="form-control text-white" required="required" placeholder="Senha">
                        </div>
                        <button type="submit" class="btn btn-sm btn-dark w-100" style="width: 70px">Login</button>
                    </form>
                </li>
                <li>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                        <a href="{{ route('createaccount.index') }}" class="nav-link">Cadastre - se</a>
                        </li>
                        <li class="nav-item">
                        <a href="{{ route('lostaccount.index') }}" class="nav-link">Recuperar Senha</a>
                        </li>
                    </ul>
                </li>
              @endif
            </ul>
          </div>
        </div>

        <div class="menu-item">
            <span onClick="MenuItemAction('publico')">
                <div id="publico_Alert" class="menu-button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="Click para fechar !">
                <img src="{{ asset("images/icons/guilds.gif") }}" class="menu-icon">
                <span class="menu-label">Público</span>
                </div>
            </span>
            <div id="publico_Submenu" class="submenu">
                <ul class="ul-sidebar">
                <li>
                    <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('characters.index') }}" class="nav-link">Personagens</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ranking.index') }}" class="nav-link">Ranking</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('killstatistics.index') }}" class="nav-link">Ultimas Mortes</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('guilds.index') }}" class="nav-link">Guilds</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('whoisonline.index') }}" class="nav-link">Quem está Online?</a>
                    </li>
                    </ul>
                </ul>
            </div>
        </div>

        <div class="menu-item">
          <span onClick="MenuItemAction('biblioteca')">
            <div id="biblioteca_Alert" class="menu-button" data-bs-toggle="tooltip" title="Click para fechar !">
              <img src="{{ asset('images/icons/icon-library.gif') }}" class="menu-icon">
              <span class="menu-label">Biblioteca</span>
            </div>
          </span>
          <div id="biblioteca_Submenu" class="submenu">
            <ul class="ul-sidebar">
              <li>
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a href="{{ route('vocations.index') }}" class="nav-link">Lista de Vocações</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('spells.index') }}" class="nav-link">Lista de Magias</a>
                  </li>
                  @if((bool)config('otserver.site.monsters_page'))
                  <li class="nav-item">
                    <a href="" class="nav-link">Lista de Monstros</a>
                  </li>
                  @endif
                  <li class="nav-item">
                    <a href="{{ route('houses.index') }}" class="nav-link">Lista de Houses</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('experiencetable.index') }}" class="nav-link">Lista de Experiência</a>
                  </li>
                </ul>
            </ul>
          </div>
        </div>

        <div class="menu-item">
            <span onClick="MenuItemAction('news')">
                <div id="news_Alert" class="menu-button" data-bs-toggle="tooltip" title="Click para fechar !">
                <img src="{{ asset('images/icons/icon-news.gif') }}" class="menu-icon">
                <span class="menu-label">Game Info</span>
                </div>
            </span>
            <div id="news_Submenu" class="submenu">
                <ul class="ul-sidebar">
                <li>
                    <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link">Experience: {{ config('otserver.server.rateExperience') }}x</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Skill: {{ config('otserver.server.rateSkill') }}x</a>
                    </li class="nav-item">
                    <li class="nav-item">
                        <a class="nav-link">Magic: {{ config('otserver.server.rateMagic') }}x</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Loot: {{ config('otserver.server.rateLoot') }}x</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Spawn: {{ config('otserver.server.rateSpawn') }}x</a>
                    </li>
                    </ul>
                </ul>
            </div>
        </div>

        <div class="menu-item">
          <span onClick="MenuItemAction('suporte')">
            <div id="suporte_Alert" class="menu-button" data-bs-toggle="tooltip" title="Click para fechar !">
              <img src="{{ asset('images/icons/icon-support.gif') }}" class="menu-icon">
              <span class="menu-label">Suporte</span>
            </div>
          </span>
          <div id="suporte_Submenu" class="submenu">
            <ul class="ul-sidebar">
              <li>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('rule.index') }}" class="nav-link">Regras</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('privacy.index') }}" class="nav-link">Contrato</a>
                    </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
</div>
